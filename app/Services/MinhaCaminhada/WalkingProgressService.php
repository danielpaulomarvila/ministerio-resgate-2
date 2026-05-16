<?php

namespace App\Services\MinhaCaminhada;

use App\Models\Person;
use App\Models\User;
use App\Models\WalkingPointLog;

class WalkingProgressService
{
    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
        private readonly WalkingLevelService $levelService,
    ) {
    }

    /**
     * Lê o progresso aprovado de uma pessoa em uma jornada autorizada.
     */
    public function getPersonProgress(User $viewer, Person $person, string $journeyType = 'general'): array
    {
        if (!$this->authorizationService->userCanViewPersonJourney($viewer, $person, $journeyType)) {
            return $this->unauthorizedResponse($journeyType);
        }

        $journey = $this->levelService->getJourneyByType($journeyType);

        if (!$journey) {
            return [
                'authorized' => false,
                'journey_type' => $journeyType,
                'total_points' => 0,
                'level' => null,
                'message' => 'Jornada não encontrada ou inativa.',
            ];
        }

        $approvedLogsQuery = WalkingPointLog::query()
            ->where('person_id', $person->id)
            ->where('walking_journey_id', $journey->id)
            ->where('status', 'approved');

        $totalPoints = (int) $approvedLogsQuery->sum('points');
        $approvedLogsCount = (clone $approvedLogsQuery)->count();

        return [
            'authorized' => true,
            'person_id' => $person->id,
            'journey' => [
                'id' => $journey->id,
                'key' => $journey->key,
                'name' => $journey->name,
                'type' => $journey->type,
            ],
            'total_points' => $totalPoints,
            'approved_logs_count' => $approvedLogsCount,
            'level_progress' => $this->levelService->getLevelProgress($journey, $totalPoints),
        ];
    }

    /**
     * Lê o progresso da própria pessoa vinculada ao usuário autenticado.
     */
    public function getOwnProgress(User $viewer, string $journeyType = 'general'): array
    {
        if (!$viewer->person) {
            return $this->unauthorizedResponse($journeyType, 'Usuário sem pessoa vinculada.');
        }

        return $this->getPersonProgress($viewer, $viewer->person, $journeyType);
    }

    /**
     * Retorna logs aprovados recentes em formato simples e sem metadata sensível.
     */
    public function getRecentApprovedLogs(User $viewer, Person $person, string $journeyType = 'general', int $limit = 5): array
    {
        if (!$this->authorizationService->userCanViewPersonJourney($viewer, $person, $journeyType)) {
            return [];
        }

        $journey = $this->levelService->getJourneyByType($journeyType);

        if (!$journey) {
            return [];
        }

        $safeLimit = min(20, max(1, $limit));

        return WalkingPointLog::query()
            ->where('person_id', $person->id)
            ->where('walking_journey_id', $journey->id)
            ->where('status', 'approved')
            ->latest()
            ->limit($safeLimit)
            ->get()
            ->map(fn (WalkingPointLog $log) => [
                'id' => $log->id,
                'category' => $log->category,
                'points' => $log->points,
                'notes' => $log->notes,
                'created_at' => $log->created_at?->toISOString(),
                'source_type' => $log->source_type,
            ])
            ->values()
            ->all();
    }

    private function unauthorizedResponse(string $journeyType, string $message = 'Você não tem permissão para visualizar esta caminhada.'): array
    {
        return [
            'authorized' => false,
            'journey_type' => $journeyType,
            'total_points' => 0,
            'level' => null,
            'message' => $message,
        ];
    }
}
