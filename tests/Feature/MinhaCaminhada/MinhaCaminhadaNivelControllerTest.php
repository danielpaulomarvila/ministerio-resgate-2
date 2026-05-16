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

class MinhaCaminhadaNivelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/nivel');

        $response->assertRedirect('/login');
    }

    public function test_level_page_returns_safe_payload_for_authenticated_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/nivel');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'nivel')
            ->where('journey', 'auto')
            ->where('walkingLevel.authorized', true)
            ->where('walkingLevel.usesRealData', true)
            ->where('walkingLevel.person.id', $person->id)
            ->where('walkingLevel.canSeeYouthJourney', false)
            ->where('walkingLevel.general.authorized', true)
            ->where('walkingLevel.general.points', 0)
            ->where('walkingLevel.general.approvedLogsCount', 0)
            ->where('walkingLevel.general.summary.has_points', false)
            ->where('walkingLevel.youth.authorized', false)
        );
    }

    public function test_level_page_uses_approved_points_only(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $this->createPointLog($person, $user, $journey, 80, 'pending');
        $this->createPointLog($person, $user, $journey, 50, 'rejected');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/nivel');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingLevel.general.points', 120)
            ->where('walkingLevel.general.approvedLogsCount', 1)
            ->where('walkingLevel.general.currentLevel.number', 2)
            ->where('walkingLevel.general.summary.has_points', true)
            ->has('walkingLevel.general.recentLogs', 1)
            ->where('walkingLevel.general.recentLogs.0.points', 120)
        );
    }

    public function test_level_page_blocks_youth_journey_for_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/nivel');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingLevel.canSeeYouthJourney', false)
            ->where('walkingLevel.youth.authorized', false)
            ->where('walkingLevel.youth.points', 0)
        );
    }

    public function test_level_page_allows_youth_payload_for_youth_user(): void
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

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/nivel');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingLevel.canSeeYouthJourney', true)
            ->where('walkingLevel.youth.authorized', true)
            ->where('walkingLevel.youth.points', 90)
            ->where('walkingLevel.youth.approvedLogsCount', 1)
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
            'full_name' => 'Pessoa Nível',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        return [$person, $user];
    }

    private function createPointLog(Person $person, User $user, WalkingJourney $journey, int $points, string $status): WalkingPointLog
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
            'notes' => 'Registro de teste aprovado para cálculo de nível.',
            'metadata' => ['internal' => 'not exposed'],
        ]);
    }
}
