<?php

namespace App\Policies;

use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class PersonWalkingAchievementPolicy
{
    public function view(User $user, PersonWalkingAchievement $personWalkingAchievement): bool
    {
        $authorization = app(WalkingAuthorizationService::class);
        $achievement = $personWalkingAchievement->achievement;

        if ($achievement && in_array($achievement->type, ['financial', 'pastoral_sensitive'], true)) {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        return $authorization->userCanViewPersonJourney(
            $user,
            $personWalkingAchievement->person,
            $personWalkingAchievement->journey?->type ?? 'general'
        );
    }

    public function award(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanValidateWalkingPoints($user);
    }

    public function update(User $user, PersonWalkingAchievement $personWalkingAchievement): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $authorization->userCanValidateWalkingPoints($user)
            || $authorization->userCanManageWalkingRules($user);
    }
}
