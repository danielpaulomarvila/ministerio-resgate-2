<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingAchievement;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingAchievementPolicy
{
    public function viewAny(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanViewOwnGeneralJourney($user);
    }

    public function view(User $user, WalkingAchievement $walkingAchievement): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        if (in_array($walkingAchievement->type, ['financial', 'pastoral_sensitive'], true)) {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        if (in_array($walkingAchievement->visibility, ['leadership_only', 'hidden'], true)) {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        return $walkingAchievement->type === 'youth'
            ? $authorization->userCanViewOwnYouthJourney($user)
            : $authorization->userCanViewOwnGeneralJourney($user);
    }

    public function manage(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanManageWalkingRules($user);
    }
}
