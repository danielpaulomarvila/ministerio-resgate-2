<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingMentorResponseTemplate;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingMentorResponseTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $authorization->userCanManageMentorTemplates($user)
            || $authorization->userCanViewSensitiveWalkingData($user);
    }

    public function view(User $user, WalkingMentorResponseTemplate $template): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        return $authorization->userCanManageMentorTemplates($user)
            || $authorization->userCanViewSensitiveWalkingData($user);
    }

    public function manage(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanManageMentorTemplates($user);
    }
}
