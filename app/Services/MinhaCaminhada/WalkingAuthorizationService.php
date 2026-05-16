<?php

namespace App\Services\MinhaCaminhada;

use App\Models\DepartmentPerson;
use App\Models\GuardianShip;
use App\Models\Person;
use App\Models\User;

class WalkingAuthorizationService
{
    public function userCanViewOwnGeneralJourney(User $user): bool
    {
        return $this->userPerson($user) !== null;
    }

    public function userCanViewOwnYouthJourney(User $user): bool
    {
        $person = $this->userPerson($user);

        return $person !== null && $this->isYouthParticipant($person);
    }

    public function userCanViewPersonJourney(User $user, Person $person, string $journeyType = 'general'): bool
    {
        $userPerson = $this->userPerson($user);

        if (!$userPerson) {
            return false;
        }

        if ($userPerson->id === $person->id) {
            return $journeyType === 'youth'
                ? $this->isYouthParticipant($person)
                : true;
        }

        if ($this->userCanViewFamilyChildJourney($user, $person)) {
            return $journeyType === 'youth'
                ? $this->isYouthParticipant($person)
                : true;
        }

        if ($journeyType === 'youth') {
            return $this->userCanViewYouthLeadershipData($user);
        }

        return $this->userHasPermission($user, 'walking.view.leadership_dashboard');
    }

    public function userCanViewFamilyChildJourney(User $user, Person $child): bool
    {
        $guardian = $this->userPerson($user);

        if (!$guardian || $guardian->id === $child->id) {
            return false;
        }

        $hasActiveGuardianship = GuardianShip::query()
            ->where('minor_person_id', $child->id)
            ->where('guardian_person_id', $guardian->id)
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->exists();

        if ($hasActiveGuardianship) {
            return true;
        }

        return $this->userHasPermission($user, 'walking.view.family_child')
            && $guardian->families()
                ->whereHas('members', fn ($query) => $query->where('people.id', $child->id))
                ->exists();
    }

    public function userCanViewSensitiveWalkingData(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.view.sensitive');
    }

    public function userCanManageWalkingRules(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.manage.rules');
    }

    public function userCanValidateWalkingPoints(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.validate.points');
    }

    public function userCanApproveWalkingHighlights(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.approve.highlights');
    }

    public function userCanManageMentorTemplates(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.manage.mentor_templates');
    }

    public function userCanViewYouthLeadershipData(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.view.youth_leadership')
            || ($this->userHasPermission($user, 'walking.view.leadership_dashboard') && $this->isYouthLeader($user));
    }

    public function userCanViewPastoralWalkingData(User $user): bool
    {
        return $this->userHasPermission($user, 'walking.view.pastoral');
    }

    private function userHasPermission(User $user, string $permissionKey): bool
    {
        return $user->hasPermission($permissionKey);
    }

    private function userPerson(User $user): ?Person
    {
        return $user->person;
    }

    private function isYouthParticipant(Person $person): bool
    {
        return DepartmentPerson::query()
            ->where('person_id', $person->id)
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->whereIn('category', ['junior', 'jovem'])
            ->whereHas('department', function ($query) {
                $query->whereIn('slug', ['resgatados', 'jovens-resgatados'])
                    ->where('status', 'active');
            })
            ->exists();
    }

    private function isYouthLeader(User $user): bool
    {
        $person = $this->userPerson($user);

        if (!$person) {
            return false;
        }

        return DepartmentPerson::query()
            ->where('person_id', $person->id)
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->where(function ($query) {
                $query->where('is_leader', true)
                    ->orWhere('is_assistant', true)
                    ->orWhere('can_manage_department', true);
            })
            ->whereHas('department', function ($query) {
                $query->whereIn('slug', ['resgatados', 'jovens-resgatados'])
                    ->where('status', 'active');
            })
            ->exists();
    }
}
