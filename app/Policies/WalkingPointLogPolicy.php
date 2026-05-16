<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingPointLog;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingPointLogPolicy
{
    public function view(User $user, WalkingPointLog $walkingPointLog): bool
    {
        return app(WalkingAuthorizationService::class)->userCanViewPersonJourney(
            $user,
            $walkingPointLog->person,
            $walkingPointLog->journey?->type ?? 'general'
        );
    }

    public function create(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanValidateWalkingPoints($user);
    }

    public function validate(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanValidateWalkingPoints($user);
    }

    public function update(User $user, WalkingPointLog $walkingPointLog): bool
    {
        return app(WalkingAuthorizationService::class)->userCanValidateWalkingPoints($user);
    }

    public function delete(User $user, WalkingPointLog $walkingPointLog): bool
    {
        return false;
    }
}
