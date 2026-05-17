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

class MinhaCaminhadaMapaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/mapa');

        $response->assertRedirect('/login');
    }

    public function test_general_map_page_returns_safe_payload_for_authenticated_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral/mapa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'mapa')
            ->where('journey', 'geral')
            ->where('walkingMap.authorized', true)
            ->where('walkingMap.usesRealData', true)
            ->where('walkingMap.person.id', $person->id)
            ->where('walkingMap.requestedJourneyType', 'general')
            ->where('walkingMap.canSeeYouthJourney', false)
            ->where('walkingMap.general.authorized', true)
            ->where('walkingMap.general.points', 0)
            ->where('walkingMap.general.summary.has_points', false)
            ->has('walkingMap.general.levels')
            ->where('walkingMap.youth.authorized', false)
        );
    }

    public function test_map_uses_approved_points_only(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $this->createPointLog($person, $user, $journey, 80, 'pending');
        $this->createPointLog($person, $user, $journey, 50, 'rejected');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mapa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingMap.general.points', 120)
            ->where('walkingMap.general.approvedLogsCount', 1)
            ->where('walkingMap.general.currentLevel.number', 2)
            ->where('walkingMap.general.summary.has_points', true)
            ->has('walkingMap.general.recentLogs', 1)
            ->where('walkingMap.general.recentLogs.0.points', 120)
        );
    }

    public function test_youth_map_blocks_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/mapa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'mapa')
            ->where('journey', 'jovem')
            ->where('walkingMap.requestedJourneyType', 'youth')
            ->where('walkingMap.canSeeYouthJourney', false)
            ->where('walkingMap.youth.authorized', false)
            ->where('walkingMap.youth.levels', [])
        );
    }

    public function test_youth_map_allows_youth_user(): void
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

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/mapa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingMap.canSeeYouthJourney', true)
            ->where('walkingMap.youth.authorized', true)
            ->where('walkingMap.youth.points', 90)
            ->where('walkingMap.youth.approvedLogsCount', 1)
            ->has('walkingMap.youth.levels')
        );
    }

    public function test_legacy_map_defaults_to_general(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mapa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'mapa')
            ->where('journey', 'geral')
            ->where('walkingMap.requestedJourneyType', 'general')
            ->where('walkingMap.defaultJourneyType', 'general')
            ->where('walkingMap.general.authorized', true)
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
            'full_name' => 'Pessoa Mapa',
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
            'notes' => 'Registro de teste aprovado para cálculo do mapa.',
            'metadata' => ['internal' => 'not exposed'],
        ]);
    }
}
