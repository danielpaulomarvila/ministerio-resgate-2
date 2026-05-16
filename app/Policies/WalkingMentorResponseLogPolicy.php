<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingMentorResponseLog;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingMentorResponseLogPolicy
{
    public function view(User $user, WalkingMentorResponseLog $log): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        if ($authorization->userCanViewSensitiveWalkingData($user)) {
            return true;
        }

        return $authorization->userCanViewPersonJourney(
            $user,
            $log->person,
            $log->journey?->type ?? 'general'
        );
    }
}
