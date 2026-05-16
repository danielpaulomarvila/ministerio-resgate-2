<?php

namespace App\Services\MinhaCaminhada;

use App\Models\Person;
use App\Models\User;
use App\Models\WalkingHighlight;

class WalkingDashboardReadService
{
    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
        private readonly WalkingProgressService $progressService,
        private readonly WalkingAchievementReadService $achievementReadService,
        private readonly WalkingMentorReadService $mentorReadService,
        private readonly WalkingLevelService $levelService,
    ) {
    }

    /**
     * Monta o dashboard da própria pessoa vinculada ao usuário autenticado.
     */
    public function getOwnDashboard(User $viewer, string $journeyType = 'general'): array
    {
        if (!$viewer->person) {
            return [
                'authorized' => false,
                'message' => 'Usuário sem pessoa vinculada.',
            ];
        }

        return $this->getPersonDashboard($viewer, $viewer->person, $journeyType);
    }

    /**
     * Monta um resumo seguro para futura tela Inertia sem expor Models crus.
     */
    public function getPersonDashboard(User $viewer, Person $person, string $journeyType = 'general'): array
    {
        if (!$this->authorizationService->userCanViewPersonJourney($viewer, $person, $journeyType)) {
            return [
                'authorized' => false,
                'message' => 'Você não tem permissão para visualizar esta caminhada.',
            ];
        }

        return [
            'authorized' => true,
            'person' => [
                'id' => $person->id,
                'name' => $person->preferred_name ?: $person->full_name,
            ],
            'journey_type' => $journeyType,
            'progress' => $this->progressService->getPersonProgress($viewer, $person, $journeyType),
            'achievements' => $this->achievementReadService->getPersonAchievements($viewer, $person, $journeyType),
            'mentor' => $this->mentorReadService->getSuggestedMessage($viewer, $person, $journeyType),
            'recent_logs' => $this->progressService->getRecentApprovedLogs($viewer, $person, $journeyType),
            'highlights' => $this->getVisibleHighlights($viewer, $person, $journeyType),
        ];
    }

    /**
     * Lê poucos destaques visíveis, sem metadata e sem visibilidade administrativa indevida.
     */
    private function getVisibleHighlights(User $viewer, Person $person, string $journeyType): array
    {
        $journey = $this->levelService->getJourneyByType($journeyType);

        if (!$journey) {
            return [];
        }

        $canViewSensitive = $this->authorizationService->userCanViewSensitiveWalkingData($viewer);
        $allowedVisibility = $canViewSensitive
            ? ['public_to_user', 'private_to_user', 'leadership_only']
            : ['public_to_user', 'private_to_user'];

        return WalkingHighlight::query()
            ->where('is_visible', true)
            ->where('walking_journey_id', $journey->id)
            ->whereIn('visibility', $allowedVisibility)
            ->where(function ($query) use ($person) {
                $query->where('person_id', $person->id)
                    ->orWhereNull('person_id');
            })
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (WalkingHighlight $highlight) => [
                'id' => $highlight->id,
                'type' => $highlight->type,
                'category' => $highlight->category,
                'title' => $highlight->title,
                'description' => $highlight->description,
                'period_type' => $highlight->period_type,
                'period_start' => $highlight->period_start?->toDateString(),
                'period_end' => $highlight->period_end?->toDateString(),
                'visibility' => $highlight->visibility,
            ])
            ->values()
            ->all();
    }
}
