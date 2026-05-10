<?php

namespace App\Services\Secretaria;

use App\Models\FamilyMember;
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
        $this->generateChildTurning11Alerts();
        $this->generateMinorWithoutGuardianAlerts();
        $this->generatePersonWithoutFamilyAlerts();
        $this->generateIncompleteRegistrationAlerts();
        $this->generateGuardianshipEndingSoonAlerts();
        $this->generateGuardianshipExpiredAlerts();
    }

    /**
     * Gera alertas para crianças que completarão 11 anos nos próximos 60 dias
     * type: child_turning_11
     * severity: low (informativo)
     * status: pending
     */
    protected function generateChildTurning11Alerts(): void
    {
        // Buscar crianças menores de 11 anos que completarão 11 nos próximos 60 dias
        $childrenTurning11 = Person::whereNotNull('birth_date')
            ->whereRaw('DATE_ADD(birth_date, INTERVAL 11 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)')
            ->whereNull('deleted_at')
            ->get();

        foreach ($childrenTurning11 as $child) {
            $age = Carbon::parse($child->birth_date)->age;
            $turns11At = Carbon::parse($child->birth_date)->addYears(11)->format('d/m/Y');

            // Verificar se já existe alerta aberto para esta criança
            $existingAlert = SystemAlert::where('type', 'child_turning_11')
                ->where('related_person_id', $child->id)
                ->where('status', 'pending')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Criança completará 11 anos em breve",
                    'message' => "{$child->full_name} ({$age} anos) completará 11 anos em {$turns11At}. Revise o cadastro para possível transição para Júnior.",
                    'severity' => 'low',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'child_turning_11',
                    'title' => "Criança completará 11 anos em breve",
                    'message' => "{$child->full_name} ({$age} anos) completará 11 anos em {$turns11At}. Revise o cadastro para possível transição para Júnior.",
                    'severity' => 'low',
                    'status' => 'pending',
                    'related_person_id' => $child->id,
                ]);
            }
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
        // Buscar pessoas menores de 18 anos sem guardianship ativo
        $minorsWithoutGuardian = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')
            ->whereDoesntHave('guardianshipsAsMinor', function ($query) {
                $query->where('status', 'active');
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($minorsWithoutGuardian as $minor) {
            $age = Carbon::parse($minor->birth_date)->age;

            // Verificar se já existe alerta aberto para esta pessoa
            $existingAlert = SystemAlert::where('type', 'minor_without_guardian')
                ->where('related_person_id', $minor->id)
                ->where('status', 'pending')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Menor sem responsável ativo",
                    'message' => "{$minor->full_name} ({$age} anos) não possui responsável legal ativo. É necessário vincular um responsável.",
                    'severity' => 'critical',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'minor_without_guardian',
                    'title' => "Menor sem responsável ativo",
                    'message' => "{$minor->full_name} ({$age} anos) não possui responsável legal ativo. É necessário vincular um responsável.",
                    'severity' => 'critical',
                    'status' => 'pending',
                    'related_person_id' => $minor->id,
                ]);
            }
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
            // Verificar se já existe alerta aberto para esta pessoa
            $existingAlert = SystemAlert::where('type', 'person_without_family')
                ->where('related_person_id', $person->id)
                ->where('status', 'pending')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Pessoa sem família",
                    'message' => "{$person->full_name} não está vinculado a nenhuma família. Considere vincular a uma família existente.",
                    'severity' => 'medium',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'person_without_family',
                    'title' => "Pessoa sem família",
                    'message' => "{$person->full_name} não está vinculado a nenhuma família. Considere vincular a uma família existente.",
                    'severity' => 'medium',
                    'status' => 'pending',
                    'related_person_id' => $person->id,
                ]);
            }
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
        // Buscar pessoas sem data de nascimento
        $peopleWithoutBirthDate = Person::whereNull('birth_date')
            ->whereNull('deleted_at')
            ->get();

        foreach ($peopleWithoutBirthDate as $person) {
            // Verificar se já existe alerta aberto para esta pessoa
            $existingAlert = SystemAlert::where('type', 'incomplete_registration')
                ->where('related_person_id', $person->id)
                ->where('status', 'pending')
                ->where('message', 'like', '%sem data de nascimento%')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui data de nascimento. Este campo é importante para cálculo de idade e faixas etárias.",
                    'severity' => 'medium',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'incomplete_registration',
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui data de nascimento. Este campo é importante para cálculo de idade e faixas etárias.",
                    'severity' => 'medium',
                    'status' => 'pending',
                    'related_person_id' => $person->id,
                ]);
            }
        }

        // Buscar pessoas sem telefone principal
        $peopleWithoutPhone = Person::whereNull('primary_phone')
            ->whereNull('deleted_at')
            ->get();

        foreach ($peopleWithoutPhone as $person) {
            // Verificar se já existe alerta aberto para esta pessoa
            $existingAlert = SystemAlert::where('type', 'incomplete_registration')
                ->where('related_person_id', $person->id)
                ->where('status', 'pending')
                ->where('message', 'like', '%sem telefone%')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui telefone principal. Adicione um telefone para contato.",
                    'severity' => 'medium',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'incomplete_registration',
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui telefone principal. Adicione um telefone para contato.",
                    'severity' => 'medium',
                    'status' => 'pending',
                    'related_person_id' => $person->id,
                ]);
            }
        }

        // Buscar pessoas sem email e sem telefone
        $peopleWithoutEmailOrPhone = Person::whereNull('email')
            ->whereNull('primary_phone')
            ->whereNull('deleted_at')
            ->get();

        foreach ($peopleWithoutEmailOrPhone as $person) {
            // Verificar se já existe alerta aberto para esta pessoa
            $existingAlert = SystemAlert::where('type', 'incomplete_registration')
                ->where('related_person_id', $person->id)
                ->where('status', 'pending')
                ->where('message', 'like', '%sem email e sem telefone%')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui email nem telefone. Adicione pelo menos uma forma de contato.",
                    'severity' => 'medium',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'incomplete_registration',
                    'title' => "Cadastro incompleto",
                    'message' => "{$person->full_name} não possui email nem telefone. Adicione pelo menos uma forma de contato.",
                    'severity' => 'medium',
                    'status' => 'pending',
                    'related_person_id' => $person->id,
                ]);
            }
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
            ->whereNull('deleted_at')
            ->get();

        foreach ($guardianshipsEndingSoon as $guardianship) {
            $minor = $guardianship->minor;
            $guardian = $guardianship->guardian;
            $endsAt = Carbon::parse($guardianship->ends_at)->format('d/m/Y');

            if (!$minor || !$guardian) {
                continue;
            }

            // Verificar se já existe alerta aberto para esta responsabilidade
            $existingAlert = SystemAlert::where('type', 'guardianship_ending_soon')
                ->where('related_person_id', $minor->id)
                ->where('status', 'pending')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Responsabilidade com data de fim próxima",
                    'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} termina em {$endsAt}. Revise se deve ser estendida.",
                    'severity' => 'medium',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'guardianship_ending_soon',
                    'title' => "Responsabilidade com data de fim próxima",
                    'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} termina em {$endsAt}. Revise se deve ser estendida.",
                    'severity' => 'medium',
                    'status' => 'pending',
                    'related_person_id' => $minor->id,
                ]);
            }
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
            ->whereNull('deleted_at')
            ->get();

        foreach ($guardianshipsExpired as $guardianship) {
            $minor = $guardianship->minor;
            $guardian = $guardianship->guardian;
            $endsAt = Carbon::parse($guardianship->ends_at)->format('d/m/Y');

            if (!$minor || !$guardian) {
                continue;
            }

            // Verificar se já existe alerta aberto para esta responsabilidade
            $existingAlert = SystemAlert::where('type', 'guardianship_expired')
                ->where('related_person_id', $minor->id)
                ->where('status', 'pending')
                ->first();

            if ($existingAlert) {
                // Atualizar alerta existente
                $existingAlert->update([
                    'title' => "Responsabilidade vencida",
                    'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} venceu em {$endsAt}. Revise se deve ser estendida ou encerrada.",
                    'severity' => 'critical',
                ]);
            } else {
                // Criar novo alerta
                SystemAlert::create([
                    'type' => 'guardianship_expired',
                    'title' => "Responsabilidade vencida",
                    'message' => "A responsabilidade de {$guardian->full_name} sobre {$minor->full_name} venceu em {$endsAt}. Revise se deve ser estendida ou encerrada.",
                    'severity' => 'critical',
                    'status' => 'pending',
                    'related_person_id' => $minor->id,
                ]);
            }
        }
    }
}
