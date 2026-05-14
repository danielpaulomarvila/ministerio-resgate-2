<?php

namespace App\Policies;

use App\Models\FinancialCorrectionRequest;
use App\Models\User;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialCorrectionRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->person_id !== null || $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function view(User $user, FinancialCorrectionRequest $financialCorrectionRequest): bool
    {
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        if ($user->id === $financialCorrectionRequest->requested_by) {
            return true;
        }

        return app(FinancialAccessService::class)
            ->visiblePersonIdsFor($user)
            ->contains($financialCorrectionRequest->person_id);
    }
}
