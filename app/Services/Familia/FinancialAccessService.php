<?php

namespace App\Services\Familia;

use App\Models\GuardianShip;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Collection;

class FinancialAccessService
{
    public function visiblePersonIdsFor(User $user): Collection
    {
        $personId = $user->person_id;

        if (! $personId) {
            return collect();
        }

        $minorIds = GuardianShip::query()
            ->where('guardian_person_id', $personId)
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->where(function ($query) {
                $query->where('can_view_financial', true)
                    ->orWhere('is_financial_responsible', true)
                    ->orWhere('can_receive_canteen_debts', true);
            })
            ->whereHas('minor', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->pluck('minor_person_id');

        return $minorIds
            ->push($personId)
            ->unique()
            ->values();
    }

    public function canViewPersonFinancial(User $user, Person $person): bool
    {
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        if ($user->person_id === $person->id) {
            return true;
        }

        if (! $user->person_id || ! $person->id || $person->isAdult()) {
            return false;
        }

        return GuardianShip::query()
            ->where('guardian_person_id', $user->person_id)
            ->where('minor_person_id', $person->id)
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->where(function ($query) {
                $query->where('can_view_financial', true)
                    ->orWhere('is_financial_responsible', true)
                    ->orWhere('can_receive_canteen_debts', true);
            })
            ->exists();
    }
}
