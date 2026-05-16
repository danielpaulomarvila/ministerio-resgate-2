<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalkingHighlight;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;

class WalkingHighlightPolicy
{
    public function viewAny(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanViewOwnGeneralJourney($user);
    }

    public function view(User $user, WalkingHighlight $walkingHighlight): bool
    {
        $authorization = app(WalkingAuthorizationService::class);

        if ($walkingHighlight->type === 'intercession') {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        if (in_array($walkingHighlight->visibility, ['leadership_only', 'hidden'], true)) {
            return $authorization->userCanViewSensitiveWalkingData($user);
        }

        if (!$walkingHighlight->is_visible && !$authorization->userCanApproveWalkingHighlights($user)) {
            return false;
        }

        if ($walkingHighlight->person) {
            return $authorization->userCanViewPersonJourney(
                $user,
                $walkingHighlight->person,
                $walkingHighlight->journey?->type ?? 'general'
            );
        }

        return $authorization->userCanViewOwnGeneralJourney($user);
    }

    public function approve(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanApproveWalkingHighlights($user);
    }

    public function manage(User $user): bool
    {
        return app(WalkingAuthorizationService::class)->userCanApproveWalkingHighlights($user);
    }
}
