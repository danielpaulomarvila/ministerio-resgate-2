<?php

namespace App\Services\MinhaCaminhada;

use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingAchievement;
use App\Models\WalkingJourney;

class WalkingAchievementReadService
{
    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
    ) {
    }

    /**
     * Lê o catálogo visível de conquistas para a jornada informada.
     */
    public function getVisibleCatalog(User $viewer, string $journeyType = 'general'): array
    {
        if (!$this->canViewOwnJourney($viewer, $journeyType)) {
            return [];
        }

        $canViewSensitive = $this->authorizationService->userCanViewSensitiveWalkingData($viewer);

        return WalkingAchievement::query()
            ->where('type', $journeyType)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->filter(fn (WalkingAchievement $achievement) => $this->achievementIsVisible($achievement, $canViewSensitive))
            ->map(fn (WalkingAchievement $achievement) => $this->formatCatalogAchievement($achievement))
            ->values()
            ->all();
    }

    /**
     * Lê conquistas de uma pessoa, respeitando autorização e visibilidade sensível.
     */
    public function getPersonAchievements(User $viewer, Person $person, string $journeyType = 'general'): array
    {
        if (!$this->authorizationService->userCanViewPersonJourney($viewer, $person, $journeyType)) {
            return [];
        }

        $journey = $this->getJourneyByType($journeyType);

        if (!$journey) {
            return [];
        }

        $canViewSensitive = $this->authorizationService->userCanViewSensitiveWalkingData($viewer);

        return PersonWalkingAchievement::query()
            ->with('achievement')
            ->where('person_id', $person->id)
            ->where('walking_journey_id', $journey->id)
            ->latest()
            ->get()
            ->filter(function (PersonWalkingAchievement $personAchievement) use ($canViewSensitive) {
                return $personAchievement->achievement
                    && $this->achievementIsVisible($personAchievement->achievement, $canViewSensitive);
            })
            ->map(fn (PersonWalkingAchievement $personAchievement) => [
                'id' => $personAchievement->id,
                'status' => $personAchievement->status,
                'progress_current' => $personAchievement->progress_current,
                'progress_target' => $personAchievement->progress_target,
                'awarded_at' => $personAchievement->awarded_at?->toISOString(),
                'achievement' => $this->formatCatalogAchievement($personAchievement->achievement),
            ])
            ->values()
            ->all();
    }

    /**
     * Lê conquistas da própria pessoa vinculada ao usuário.
     */
    public function getOwnAchievements(User $viewer, string $journeyType = 'general'): array
    {
        if (!$viewer->person) {
            return [];
        }

        return $this->getPersonAchievements($viewer, $viewer->person, $journeyType);
    }

    private function canViewOwnJourney(User $viewer, string $journeyType): bool
    {
        return $journeyType === 'youth'
            ? $this->authorizationService->userCanViewOwnYouthJourney($viewer)
            : $this->authorizationService->userCanViewOwnGeneralJourney($viewer);
    }

    private function getJourneyByType(string $journeyType): ?WalkingJourney
    {
        return WalkingJourney::query()
            ->where('type', $journeyType)
            ->where('is_active', true)
            ->first();
    }

    private function achievementIsVisible(WalkingAchievement $achievement, bool $canViewSensitive): bool
    {
        if (in_array($achievement->type, ['financial', 'pastoral_sensitive'], true)) {
            return $canViewSensitive;
        }

        if (in_array($achievement->visibility, ['hidden', 'leadership_only'], true)) {
            return $canViewSensitive;
        }

        return true;
    }

    private function formatCatalogAchievement(WalkingAchievement $achievement): array
    {
        return [
            'id' => $achievement->id,
            'key' => $achievement->key,
            'name' => $achievement->name,
            'description' => $achievement->description,
            'type' => $achievement->type,
            'category' => $achievement->category,
            'visibility' => $achievement->visibility,
            'icon' => $achievement->icon,
            'color' => $achievement->color,
            'requires_validation' => $achievement->requires_validation,
        ];
    }
}
