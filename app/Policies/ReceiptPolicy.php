<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->person_id !== null || $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function view(User $user, Receipt $receipt): bool
    {
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        return app(FinancialAccessService::class)
            ->visiblePersonIdsFor($user)
            ->contains($receipt->person_id);
    }
}
