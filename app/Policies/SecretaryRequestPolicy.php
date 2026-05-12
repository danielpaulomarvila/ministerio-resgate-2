<?php

namespace App\Policies;

use App\Models\SecretaryRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SecretaryRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function view(User $user, SecretaryRequest $secretaryRequest): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function update(User $user, SecretaryRequest $secretaryRequest): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    public function delete(User $user, SecretaryRequest $secretaryRequest): bool
    {
        return $user->isSuperAdmin();
    }
}
