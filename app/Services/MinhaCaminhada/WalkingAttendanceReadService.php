<?php

namespace App\Services\MinhaCaminhada;

use App\Models\User;

class WalkingAttendanceReadService
{
    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
    ) {
    }

    public function getOwnAttendances(User $viewer): array
    {
        $person = $viewer->person;
        $canSeeYouthJourney = $this->authorizationService->userCanViewOwnYouthJourney($viewer);

        return [
            'authorized' => $person !== null,
            'usesRealData' => false,
            'sourceAvailable' => false,
            'generatedAt' => now()->toISOString(),
            'person' => $person ? [
                'id' => $person->id,
                'name' => $person->preferred_name ?: $person->full_name,
            ] : null,
            'canSeeYouthJourney' => $canSeeYouthJourney,
            'general' => $this->emptyJourney('general', $person !== null),
            'youth' => $canSeeYouthJourney
                ? $this->emptyJourney('youth', true)
                : $this->emptyJourney('youth', false, 'A caminhada jovem aparece somente para jovens/resgatados autorizados.'),
            'emptyStates' => $this->emptyStates(),
            'notice' => $this->notice(),
        ];
    }

    private function emptyJourney(string $journeyType, bool $authorized, ?string $message = null): array
    {
        return [
            'authorized' => $authorized,
            'journeyType' => $journeyType,
            'sourceAvailable' => false,
            'items' => [],
            'summary' => [
                'totalAttendances' => 0,
                'confirmedAttendances' => 0,
                'lastAttendanceAt' => null,
                'attendanceRate' => null,
            ],
            'emptyState' => $authorized
                ? $this->emptyStates()['withoutOfficialSource']
                : [
                    'title' => $journeyType === 'youth' ? 'Presenças jovens protegidas.' : 'Presenças indisponíveis para este perfil.',
                    'text' => $message ?: 'Esta visualização aparece somente quando houver vínculo cadastral e autorização adequada.',
                ],
        ];
    }

    private function emptyStates(): array
    {
        return [
            'withoutPerson' => [
                'title' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                'text' => 'Quando o cadastro estiver vinculado e houver registro oficial de presenças, elas aparecerão aqui.',
            ],
            'withoutOfficialSource' => [
                'title' => 'Presenças ainda não estão conectadas a um registro oficial.',
                'text' => 'Quando a igreja ativar o registro de presenças, elas aparecerão aqui.',
            ],
            'unauthorizedYouth' => [
                'title' => 'Presenças jovens protegidas.',
                'text' => 'A caminhada jovem aparece somente para jovens/resgatados autorizados.',
            ],
        ];
    }

    private function notice(): array
    {
        return [
            'title' => 'Registro oficial ainda não conectado',
            'text' => 'Presenças ainda não estão conectadas a um registro oficial.',
            'nextStepText' => 'Quando a igreja ativar o registro de presenças, elas aparecerão aqui.',
            'privacyText' => 'A futura integração exibirá somente presenças confirmadas/autorizadas, sem dados pastorais, financeiros ou administrativos sensíveis.',
        ];
    }
}
