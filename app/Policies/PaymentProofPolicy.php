<?php

namespace App\Policies;

use App\Models\PaymentProof;
use App\Models\User;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentProofPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->person_id !== null || $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function view(User $user, PaymentProof $paymentProof): bool
    {
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        if ($user->id === $paymentProof->uploaded_by) {
            return true;
        }

        return app(FinancialAccessService::class)
            ->visiblePersonIdsFor($user)
            ->contains($paymentProof->person_id);
    }
}
