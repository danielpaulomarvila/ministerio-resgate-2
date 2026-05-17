<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\User;
use App\Models\WalkingJourney;
use App\Models\WalkingPointLog;
use Database\Seeders\WalkingAchievementSeeder;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingMentorResponseTemplateSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaHistoricoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/historico');

        $response->assertRedirect('/login');
    }

    public function test_history_page_returns_safe_empty_payload_for_authenticated_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/historico');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'historico')
            ->where('journey', 'all')
            ->where('walkingHistory.authorized', true)
            ->where('walkingHistory.usesRealData', true)
            ->where('walkingHistory.person.id', $person->id)
            ->where('walkingHistory.canSeeYouthJourney', false)
            ->where('walkingHistory.general.authorized', true)
            ->where('walkingHistory.general.events', [])
            ->where('walkingHistory.general.summary.totalEvents', 0)
            ->where('walkingHistory.general.summary.totalPoints', 0)
            ->where('walkingHistory.youth.authorized', false)
        );
    }

    public function test_history_uses_approved_points_only(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $this->createPointLog($person, $user, $journey, 80, 'pending');
        $this->createPointLog($person, $user, $journey, 50, 'rejected');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/historico');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingHistory.general.summary.totalEvents', 1)
            ->where('walkingHistory.general.summary.totalPoints', 120)
            ->where('walkingHistory.general.summary.approvedLogsCount', 1)
            ->has('walkingHistory.general.events', 1)
            ->where('walkingHistory.general.events.0.points', 120)
            ->where('walkingHistory.general.events.0.status', 'approved')
        );
    }

    public function test_history_does_not_expose_sensitive_metadata(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createPointLog($person, $user, $journey, 75, 'approved', ['internal' => 'secret']);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/historico');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->has('walkingHistory.general.events', 1)
            ->where('walkingHistory.general.events.0.points', 75)
            ->missing('walkingHistory.general.events.0.metadata')
            ->missing('walkingHistory.general.events.0.user_id')
            ->missing('walkingHistory.general.events.0.approved_by')
            ->missing('walkingHistory.general.events.0.rejected_by')
        );
    }

    public function test_youth_history_blocks_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/historico');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingHistory.canSeeYouthJourney', false)
            ->where('walkingHistory.youth.authorized', false)
            ->where('walkingHistory.youth.events', [])
        );
    }

    public function test_youth_history_allows_youth_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $department = Department::create([
            'name' => 'Resgatados',
            'slug' => 'resgatados',
            'department_type' => 'youth',
            'status' => 'active',
        ]);
        DepartmentPerson::create([
            'department_id' => $department->id,
            'person_id' => $person->id,
            'role' => 'member',
            'category' => 'jovem',
            'status' => 'active',
            'starts_at' => now()->toDateString(),
        ]);
        $journey = WalkingJourney::query()->where('type', 'youth')->firstOrFail();
        $this->createPointLog($person, $user, $journey, 90, 'approved');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/historico');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingHistory.canSeeYouthJourney', true)
            ->where('walkingHistory.youth.authorized', true)
            ->where('walkingHistory.youth.summary.totalEvents', 1)
            ->where('walkingHistory.youth.summary.totalPoints', 90)
            ->has('walkingHistory.youth.events', 1)
            ->where('walkingHistory.youth.events.0.journeyType', 'youth')
            ->where('walkingHistory.youth.events.0.points', 90)
        );
    }

    private function seedWalkingBase(): void
    {
        $this->seed(WalkingJourneySeeder::class);
        $this->seed(WalkingLevelSeeder::class);
        $this->seed(WalkingPointRuleSeeder::class);
        $this->seed(WalkingAchievementSeeder::class);
        $this->seed(WalkingMentorResponseTemplateSeeder::class);
        $this->seed(WalkingPermissionSeeder::class);
    }

    private function createPersonAndUser(): array
    {
        $person = Person::create([
            'full_name' => 'Pessoa Histórico',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        return [$person, $user];
    }

    private function createPointLog(Person $person, User $user, WalkingJourney $journey, int $points, string $status, array $metadata = []): WalkingPointLog
    {
        return WalkingPointLog::create([
            'person_id' => $person->id,
            'user_id' => $user->id,
            'walking_journey_id' => $journey->id,
            'category' => 'presence',
            'points' => $points,
            'status' => $status,
            'approved_by' => $status === 'approved' ? $user->id : null,
            'approved_at' => $status === 'approved' ? now() : null,
            'rejected_by' => $status === 'rejected' ? $user->id : null,
            'rejected_at' => $status === 'rejected' ? now() : null,
            'notes' => 'Registro de teste aprovado para histórico.',
            'metadata' => $metadata ?: ['internal' => 'not exposed'],
        ]);
    }
}
