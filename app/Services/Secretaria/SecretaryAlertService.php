<?php

namespace App\Services\Secretaria;

use App\Models\GuardianShip;
use App\Models\Person;
use App\Models\SystemAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service para geração de alertas internos da Secretaria
 *
 * Responsável por detectar e criar alertas automáticos com base nas regras do sistema:
 * - Crianças próximas dos 11 anos
 * - Menores sem responsável ativo
 * - Pessoas sem família
 * - Cadastro incompleto
 * - Responsabilidade com data de fim próxima
 * - Responsabilidade vencida
 */
class SecretaryAlertService
{
    /**
     * Regera todos os alertas com base nas regras atuais
     * Não duplica alertas abertos iguais
     */
    public function regenerateAlerts(): void
    {
        // Limpa alertas pending duplicados antes de regenerar
        $this->removeDuplicatePendingAlerts();

        $this->generateChildTurning11Alerts();
        $this->generateMinorWithoutGuardianAlerts();
        $this->generatePersonWithoutFamilyAlerts();
        $this->generateIncompleteRegistrationAlerts();
        $this->generateGuardianshipEndingSoonAlerts();
        $this->generateGuardianshipExpiredAlerts();
    }

    /**
     * Remove alertas pending duplicados mantendo apenas o mais recente
     */
    protected function removeDuplicatePendingAlerts(): void
    {
        // Para alertas de pessoa
        $duplicatePersonAlerts = DB::table('system_alerts')
            ->select('type', 'related_person_id', DB::raw('MAX(id) as keep_id'))
            ->where('status', 'pending')
            ->whereNotNull('related_person_id')
            ->groupBy('type', 'related_person_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicatePersonAlerts as $duplicate) {
            DB::table('system_alerts')
                ->where('type', $duplicate->type)
                ->where('related_person_id', $duplicate->related_person_id)
                ->where('status', 'pending')
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        // Para alertas de família
        $duplicateFamilyAlerts = DB::table('system_alerts')
            ->select('type', 'related_family_id', DB::raw('MAX(id) as keep_id'))
            ->where('status', 'pending')
            ->whereNotNull('related_family_id')
            ->groupBy('type', 'related_family_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateFamilyAlerts as $duplicate) {
            DB::table('system_alerts')
                ->where('type', $duplicate->type)
                ->where('related_family_id', $duplicate->related_family_id)
                ->where('status', 'pending')
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }
    }

    /**
     * Cria ou atualiza alerta pending sem duplicar
     * Chave lógica: type + related_person_id + status pending
     * ou type + related_family_id + status pending
     */
    protected function createOrUpdatePendingAlert(array $data): SystemAlert
    {
        $query = SystemAlert::query()
            ->where('type', $data['type'])
            ->where('status', 'pending');

        if (! empty($data['related_person_id'])) {
            $query->where('related_person_id', $data['related_person_id']);
        }

        if (! empty($data['related_family_id'])) {
            $query->where('related_family_id', $data['related_family_id']);
        }

        $alert = $query->first();

        if ($alert) {
            $alert->update([
                'title' => $data['title'],
                'message' => $data['message'],
                'severity' => $data['severity'],
            ]);

            return $alert;
        }

        return SystemAlert::create($data);
    }

    /**
     * Gera alertas para crianças que completarão 11 anos nos próximos 60 dias
     * type: child_turning_11
     * severity: low (informativo)
     * status: pending
     */
    protected function generateChildTurning11Alerts(): void
    {
        $turns11Start = Carbon::today()->subYears(11)->toDateString();
        $turns11End = Carbon::today()->addDays(60)->subYears(11)->toDateString();

        // Buscar crianças menores de 11 anos que completarão 11 nos próximos 60 dias
        $childrenTurning11 = Person::whereNotNull('birth_date')
            ->whereBetween('birth_date', [$turns11Start, $turns11End])
            ->whereNull('deleted_at')
            ->get();

        foreach ($childrenTurning11 as $child) {
            $age = Carbon::parse($child->birth_date)->age;
            $turns11At = Carbon::parse($child->birth_date)->addYears(11)->format('d/m/Y');

            $this->createOrUpdatePendingAlert([
                'type' => 'child_turning_11',
                'title' => 'Criança completará 11 anos em breve',
                'message' => "{$child->full_name} ({$age} anos) completará 11 anos em {$turns11At}. Revise o cadastro para possível transição para Júnior.",
                'severity' => 'low',
                'status' => 'pending',
                'related_person_id' => $child->id,
            ]);
        }
    }

    /**
     * Gera alertas para menores sem responsável ativo
     * type: minor_without_guardian
     * severity: critical (perigo)
     * status: pending
     */
    protected function generateMinorWithoutGuardianAlerts(): void
    {
        $adultCutoff = Carbon::today()->subYears(18)->toDateString();

        // Buscar pessoas menores de 18 anos sem guardianship ativo
        $minorsWithoutGuardian = Person::whereNotNull('birth_date')
            ->whereDate('birth_date', '>', $adultCutoff)
            ->whereDoesntHave('guardianshipsAsMinor', function ($query) {
                $query->where('status', 'active');
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($minorsWithoutGuardian as $minor) {
            $age = Carbon::parse($minor->birth_date)->age;

            $this->createOrUpdatePendingAlert([
                'type' => 'minor_without_guardian',
                'title' => 'Menor sem responsável ativo',
                'message' => "{$minor->full_name} ({$age} anos) não possui responsável legal ativo. É necessário vincular um responsável.",
                'severity' => 'critical',
                'status' => 'pending',
                'related_person_id' => $minor->id,
            ]);
        }
    }

    /**
     * Gera alertas para pessoas sem família
     * type: person_without_family
     * severity: medium (atenção)
     * status: pending
     */
    protected function generatePersonWithoutFamilyAlerts(): void
    {
        // Buscar pessoas sem vínculo familiar ativo
        $peopleWithoutFamily = Person::whereDoesntHave('families', function ($query) {
            $query->whereNull('family_members.left_at');
        })
            ->whereNull('deleted_at')
            ->get();

        foreach ($peopleWithoutFamily as $person) {
            $this->createOrUpdatePendingAlert([
                'type' => 'person_without_family',
                'title' => 'Pessoa sem família',
                'message' => "{$person->full_name} não está vinculado a nenhuma família. Considere vincular a uma família existente.",
                'severity' => 'medium',
                'status' => 'pending',
                'related_person_id' => $person->id,
            ]);
        }
    }

    /**
     * Gera alertas para cadastros incompletos
     * type: incomplete_registration
     * severity: medium (atenção)
     * status: pending
     */
    protected function generateIncompleteRegistrationAlerts(): void
    {
        // Buscar pessoas com cadastro incompleto
        $people = Person::whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNull('birth_date')
                    ->orWhereNull('primary_phone')
                    ->orWhere(function ($q) {
                        $q->whereNull('email')
                            ->whereNull('primary_phone');
                    });
            })
            ->get();

        foreach ($people as $person) {
            $missingFields = [];
            $missingLabels = [];

            if (is_null($person->birth_date)) {
                $missingFields[] = 'birth_date';
                $missingLabels[] = 'data de nascimento';
            }

            if (is_null($person->primary_phone)) {
                $missingFields[] = 'primary_phone';
                $missingLabels[] = 'telefone principal';
            }

            if (is_null($person->email) && is_null($person->primary_phone)) {
                // Se não tem email nem telefone, já está coberto pelo primary_phone
                // Não adiciona email duplicado na lista
            }

            if (empty($missingFields)) {
                continue;
            }

            // Criar mensagem agrupada
            if (count($missingLabels) === 1) {
                $message = "{$person->full_name} não possui {$missingLabels[0]}. Este campo é importante para o cadastro.";
            } else {
                $lastLabel = array_pop($missingLabels);
                $fieldsText = implode(', ', $missingLabels).' e '.$lastLabel;
                $message = "{$person->full_name} não possui {$fieldsText}. Revise o cadastro.";
            }

            $this->createOrUpdatePendingAlert([
                'type' => 'incomplete_registration',
                'title' => 'Cadastro incompleto',
                'message' => $message,
                'severity' => 'medium',
                'status' => 'pending',
                'related_person_id' => $person->id,
            ]);
        }
    }

    /**
     * Gera alertas para responsabilidades com data de fim próxima (30 dias)
     * type: guardianship_ending_soon
     * severity: medium (atenção)
     * status: pending
     */
    protected function generateGuardianshipEndingSoonAlerts(): void
    {
        // Buscar guardianships ativos com ends_at nos próximos 30 dias
        $guardianshipsEndingSoon = GuardianShip::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', Carbon::now()->addDays(30))
            ->where('ends_at', '>', Carbon::now())
            ->get();

        foreach ($guardianshipsEndingSoon as $guardianship) {
            $minor = $guardianship->minor;
            $guardian = $guardianship->guardian;
            $endsAt = Carbon::parse($guardianship->ends_at)->format('d/m/Y');

            if (! $minor || ! $guardian) {
                continue;
            }

            $this->createOrUpdatePendingAlert([
                'type' => 'guardianship_ending_soon',
                'title' => 'Responsabilidade com data de fim próxima',
                'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} termina em {$endsAt}. Revise se deve ser estendida.",
                'severity' => 'medium',
                'status' => 'pending',
                'related_person_id' => $minor->id,
            ]);
        }
    }

    /**
     * Gera alertas para responsabilidades vencidas
     * type: guardianship_expired
     * severity: critical (perigo)
     * status: pending
     */
    protected function generateGuardianshipExpiredAlerts(): void
    {
        // Buscar guardianships ativos com ends_at menor que hoje
        $guardianshipsExpired = GuardianShip::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', Carbon::now())
            ->get();

        foreach ($guardianshipsExpired as $guardianship) {
            $minor = $guardianship->minor;
            $guardian = $guardianship->guardian;
            $endsAt = Carbon::parse($guardianship->ends_at)->format('d/m/Y');

            if (! $minor || ! $guardian) {
                continue;
            }

            $this->createOrUpdatePendingAlert([
                'type' => 'guardianship_expired',
                'title' => 'Responsabilidade vencida',
                'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} venceu em {$endsAt}. Revise se deve ser estendida ou encerrada.",
                'severity' => 'critical',
                'status' => 'pending',
                'related_person_id' => $minor->id,
            ]);
        }
    }

    /**
     * Verifica se um alerta foi realmente resolvido
     *
     * @return array ['resolved' => bool, 'message' => string]
     */
    public function isAlertActuallyResolved(SystemAlert $alert): array
    {
        switch ($alert->type) {
            case 'incomplete_registration':
                return $this->verifyIncompleteRegistrationResolved($alert);

            case 'person_without_family':
                return $this->verifyPersonWithoutFamilyResolved($alert);

            case 'minor_without_guardian':
                return $this->verifyMinorWithoutGuardianResolved($alert);

            case 'child_turning_11':
                // Este alerta é de revisão manual
                // Se o usuário clicou em verificar resolução, consideramos como confirmado
                return [
                    'resolved' => true,
                    'message' => 'Revisão confirmada pela Secretaria.',
                ];

            case 'guardianship_ending_soon':
                return $this->verifyGuardianshipEndingSoonResolved($alert);

            case 'guardianship_expired':
                return $this->verifyGuardianshipExpiredResolved($alert);

            default:
                return [
                    'resolved' => false,
                    'message' => 'Tipo de alerta desconhecido.',
                ];
        }
    }

    /**
     * Verifica se cadastro incompleto foi corrigido
     */
    protected function verifyIncompleteRegistrationResolved(SystemAlert $alert): array
    {
        if (! $alert->relatedPerson) {
            return [
                'resolved' => false,
                'message' => 'Pessoa relacionada não encontrada.',
            ];
        }

        $person = $alert->relatedPerson;
        $missingFields = [];

        // Verificar campos faltantes baseados na mensagem do alerta
        if (str_contains($alert->message, 'data de nascimento') && is_null($person->birth_date)) {
            $missingFields[] = 'data de nascimento';
        }

        if (str_contains($alert->message, 'telefone') && is_null($person->primary_phone)) {
            $missingFields[] = 'telefone principal';
        }

        if (str_contains($alert->message, 'email') && is_null($person->email)) {
            // Se não tem email mas tem telefone, pode ser aceito
            if (is_null($person->primary_phone)) {
                $missingFields[] = 'email ou telefone';
            }
        }

        if (! empty($missingFields)) {
            $fieldsText = implode(', ', $missingFields);

            return [
                'resolved' => false,
                'message' => "Este cadastro ainda possui pendências: {$fieldsText}.",
            ];
        }

        return [
            'resolved' => true,
            'message' => 'Cadastro completo verificado.',
        ];
    }

    /**
     * Verifica se pessoa sem família foi vinculada
     */
    protected function verifyPersonWithoutFamilyResolved(SystemAlert $alert): array
    {
        if (! $alert->relatedPerson) {
            return [
                'resolved' => false,
                'message' => 'Pessoa relacionada não encontrada.',
            ];
        }

        $person = $alert->relatedPerson;

        // Verificar se tem vínculo familiar ativo
        $hasActiveFamily = $person->families()
            ->whereNull('family_members.left_at')
            ->exists();

        if (! $hasActiveFamily) {
            return [
                'resolved' => false,
                'message' => 'Esta pessoa ainda não está vinculada a uma família.',
            ];
        }

        return [
            'resolved' => true,
            'message' => 'Pessoa vinculada a uma família.',
        ];
    }

    /**
     * Verifica se menor tem responsável ativo
     */
    protected function verifyMinorWithoutGuardianResolved(SystemAlert $alert): array
    {
        if (! $alert->relatedPerson) {
            return [
                'resolved' => false,
                'message' => 'Pessoa relacionada não encontrada.',
            ];
        }

        $minor = $alert->relatedPerson;

        // Verificar se tem guardianship ativo
        $hasActiveGuardianship = $minor->guardianshipsAsMinor()
            ->where('status', 'active')
            ->exists();

        if (! $hasActiveGuardianship) {
            return [
                'resolved' => false,
                'message' => 'Este menor ainda não possui responsável ativo.',
            ];
        }

        return [
            'resolved' => true,
            'message' => 'Menor possui responsável ativo.',
        ];
    }

    /**
     * Verifica se responsabilidade próxima do fim foi tratada
     */
    protected function verifyGuardianshipEndingSoonResolved(SystemAlert $alert): array
    {
        if (! $alert->relatedPerson) {
            return [
                'resolved' => false,
                'message' => 'Pessoa relacionada não encontrada.',
            ];
        }

        $minor = $alert->relatedPerson;

        // Buscar guardianship ativo do menor
        $guardianship = $minor->guardianshipsAsMinor()
            ->where('status', 'active')
            ->first();

        if (! $guardianship) {
            return [
                'resolved' => true,
                'message' => 'Responsabilidade não existe mais ou foi encerrada.',
            ];
        }

        // Se ainda está ativo e ends_at está nos próximos 30 dias
        if ($guardianship->ends_at) {
            $endsAt = Carbon::parse($guardianship->ends_at);
            $now = Carbon::now();
            $in30Days = $now->copy()->addDays(30);

            if ($endsAt->between($now, $in30Days)) {
                return [
                    'resolved' => false,
                    'message' => 'A responsabilidade ainda termina nos próximos 30 dias. Prorrogue ou encerre formalmente.',
                ];
            }
        }

        return [
            'resolved' => true,
            'message' => 'Responsabilidade foi prorrogada ou encerrada.',
        ];
    }

    /**
     * Verifica se responsabilidade vencida foi tratada
     */
    protected function verifyGuardianshipExpiredResolved(SystemAlert $alert): array
    {
        if (! $alert->relatedPerson) {
            return [
                'resolved' => false,
                'message' => 'Pessoa relacionada não encontrada.',
            ];
        }

        $minor = $alert->relatedPerson;

        // Buscar guardianship ativo do menor
        $guardianship = $minor->guardianshipsAsMinor()
            ->where('status', 'active')
            ->first();

        if (! $guardianship) {
            return [
                'resolved' => true,
                'message' => 'Responsabilidade não existe mais ou foi encerrada.',
            ];
        }

        // Se ainda está ativo e ends_at está vencido
        if ($guardianship->ends_at) {
            $endsAt = Carbon::parse($guardianship->ends_at);
            $now = Carbon::now();

            if ($endsAt->lt($now)) {
                return [
                    'resolved' => false,
                    'message' => 'A responsabilidade ainda está vencida. Atualize a data ou encerre formalmente.',
                ];
            }
        }

        return [
            'resolved' => true,
            'message' => 'Responsabilidade foi atualizada ou encerrada.',
        ];
    }
}
