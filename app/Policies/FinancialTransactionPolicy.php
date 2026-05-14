<?php

namespace App\Policies;

use App\Models\FinancialTransaction;
use App\Models\User;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->person_id !== null || $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function view(User $user, FinancialTransaction $financialTransaction): bool
    {
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        if ($user->person_id === $financialTransaction->person_id || $user->person_id === $financialTransaction->responsible_person_id) {
            return true;
        }

        return app(FinancialAccessService::class)
            ->visiblePersonIdsFor($user)
            ->contains($financialTransaction->person_id);
    }
}
