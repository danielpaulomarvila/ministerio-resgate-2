<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingJourney;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingJourneyPolicy
{
    public function viewAny(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanViewOwnGeneralJourney($user);
    }

    public function view(User $user, WalkingJourney $walkingJourney): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $walkingJourney->type === 'youth'
            ? $authorization->userCanViewOwnYouthJourney($user)
            : $authorization->userCanViewOwnGeneralJourney($user);
    }

    public function manage(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanManageWalkingRules($user);
    }
}
