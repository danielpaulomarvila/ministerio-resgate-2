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

class MinhaCaminhadaNivelDetalheControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_level_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/geral/niveis/1');

        $response->assertRedirect('/login');
    }

    public function test_general_level_page_returns_safe_payload(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral/niveis/1');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('journey', 'geral')
            ->where('level', 1)
            ->where('walkingLevelDetail.authorized', true)
            ->where('walkingLevelDetail.usesRealData', true)
            ->where('walkingLevelDetail.requestedJourneyType', 'general')
            ->where('walkingLevelDetail.legacyRoute', false)
            ->where('walkingLevelDetail.person.id', $person->id)
            ->where('walkingLevelDetail.progress.points', 0)
            ->where('walkingLevelDetail.progress.approvedLogsCount', 0)
            ->where('walkingLevelDetail.level.number', 1)
            ->where('walkingLevelDetail.level.status', 'current')
            ->has('walkingLevelDetail.level')
        );
    }

    public function test_level_detail_uses_approved_points_only(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $this->createPointLog($person, $user, $journey, 80, 'pending');
        $this->createPointLog($person, $user, $journey, 50, 'rejected');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral/niveis/2');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('walkingLevelDetail.progress.points', 120)
            ->where('walkingLevelDetail.progress.approvedLogsCount', 1)
            ->where('walkingLevelDetail.progress.currentLevel.number', 2)
            ->where('walkingLevelDetail.level.number', 2)
            ->where('walkingLevelDetail.level.status', 'current')
            ->has('walkingLevelDetail.progress.recentLogs', 1)
            ->where('walkingLevelDetail.progress.recentLogs.0.points', 120)
        );
    }

    public function test_youth_level_blocks_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/niveis/1');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('journey', 'jovem')
            ->where('walkingLevelDetail.authorized', false)
            ->where('walkingLevelDetail.requestedJourneyType', 'youth')
            ->where('walkingLevelDetail.reason', 'unauthorized_youth')
            ->where('walkingLevelDetail.level', null)
            ->where('walkingLevelDetail.message', 'Você não tem permissão para visualizar este nível jovem.')
        );
    }

    public function test_youth_level_allows_youth_user(): void
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

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/niveis/1');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('journey', 'jovem')
            ->where('walkingLevelDetail.authorized', true)
            ->where('walkingLevelDetail.requestedJourneyType', 'youth')
            ->where('walkingLevelDetail.canSeeYouthJourney', true)
            ->where('walkingLevelDetail.level.number', 1)
            ->has('walkingLevelDetail.level')
        );
    }

    public function test_legacy_level_route_defaults_to_general(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/niveis/1');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('journey', 'geral')
            ->where('walkingLevelDetail.requestedJourneyType', 'general')
            ->where('walkingLevelDetail.legacyRoute', true)
            ->where('walkingLevelDetail.authorized', true)
        );
    }

    public function test_missing_level_returns_safe_payload(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral/niveis/9999');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaNivel')
            ->where('walkingLevelDetail.authorized', true)
            ->where('walkingLevelDetail.levelFound', false)
            ->where('walkingLevelDetail.reason', 'missing_level')
            ->where('walkingLevelDetail.level', null)
            ->where('walkingLevelDetail.message', 'Nível não encontrado.')
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
            'full_name' => 'Pessoa Detalhe Nível',
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
            'notes' => 'Registro de teste aprovado para detalhe de nível.',
            'metadata' => ['internal' => 'not exposed'],
        ]);
    }
}
