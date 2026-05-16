<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingHistoryEvent;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingHistoryEventPolicy
{
    public function view(User $user, WalkingHistoryEvent $event): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        if (in_array($event->visibility, ['leadership_only', 'hidden'], true)) {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        return $authorization->userCanViewPersonJourney(
            $user,
            $event->person,
            $event->journey?->type ?? 'general'
        );
    }

    public function create(User $user): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $authorization->userCanValidateWalkingPoints($user)
            || $authorization->userCanManageWalkingRules($user);
    }

    public function manage(User $user): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $authorization->userCanValidateWalkingPoints($user)
            || $authorization->userCanManageWalkingRules($user);
    }
}
