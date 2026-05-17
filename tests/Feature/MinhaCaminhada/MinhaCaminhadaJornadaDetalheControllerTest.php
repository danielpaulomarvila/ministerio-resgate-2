<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingAchievement;
use App\Models\WalkingHighlight;
use App\Models\WalkingHistoryEvent;
use App\Models\WalkingJourney;
use App\Models\WalkingMentorResponseLog;
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

class MinhaCaminhadaJornadaDetalheControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_journey_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/geral');

        $response->assertRedirect('/login');
    }

    public function test_youth_journey_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/jovem');

        $response->assertRedirect('/login');
    }

    public function test_general_journey_returns_safe_payload_for_user_with_person(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'geral')
            ->where('journey', 'geral')
            ->where('walkingJourneyDetail.authorized', true)
            ->where('walkingJourneyDetail.usesRealData', true)
            ->where('walkingJourneyDetail.requestedJourneyType', 'general')
            ->where('walkingJourneyDetail.person.id', $person->id)
            ->where('walkingJourneyDetail.canSeeYouthJourney', false)
            ->where('walkingJourneyDetail.journey.authorized', true)
            ->where('walkingJourneyDetail.journey.journeyType', 'general')
            ->where('walkingJourneyDetail.journey.progress.points', 0)
            ->where('walkingJourneyDetail.journey.summary.hasPoints', false)
        );
    }

    public function test_general_journey_uses_only_real_approved_data(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $achievement = WalkingAchievement::query()
            ->where('type', 'general')
            ->where('visibility', 'public_to_user')
            ->firstOrFail();

        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $this->createPointLog($person, $user, $journey, 80, 'pending');
        $this->createPointLog($person, $user, $journey, 50, 'rejected');
        $this->createPersonAchievement($person, $journey, $achievement);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingJourneyDetail.journey.progress.points', 120)
            ->where('walkingJourneyDetail.journey.progress.approvedLogsCount', 1)
            ->where('walkingJourneyDetail.journey.currentLevel.number', 2)
            ->where('walkingJourneyDetail.journey.summary.hasPoints', true)
            ->where('walkingJourneyDetail.journey.summary.achievementsCount', 1)
            ->has('walkingJourneyDetail.journey.recentLogs', 1)
            ->where('walkingJourneyDetail.journey.recentLogs.0.points', 120)
            ->has('walkingJourneyDetail.journey.achievements', 1)
            ->where('walkingJourneyDetail.journey.achievements.0.achievement.name', $achievement->name)
        );
    }

    public function test_youth_journey_blocks_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'jovem')
            ->where('journey', 'jovem')
            ->where('walkingJourneyDetail.authorized', false)
            ->where('walkingJourneyDetail.requestedJourneyType', 'youth')
            ->where('walkingJourneyDetail.canSeeYouthJourney', false)
            ->where('walkingJourneyDetail.reason', 'unauthorized_youth')
            ->where('walkingJourneyDetail.journey.authorized', false)
            ->where('walkingJourneyDetail.journey.recentLogs', [])
        );
        $response->assertDontSee('youth_resgatados_attendance');
    }

    public function test_youth_journey_allows_youth_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $this->attachYouthDepartment($person);
        $journey = WalkingJourney::query()->where('type', 'youth')->firstOrFail();
        $this->createPointLog($person, $user, $journey, 90, 'approved');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingJourneyDetail.authorized', true)
            ->where('walkingJourneyDetail.requestedJourneyType', 'youth')
            ->where('walkingJourneyDetail.canSeeYouthJourney', true)
            ->where('walkingJourneyDetail.journey.authorized', true)
            ->where('walkingJourneyDetail.journey.journeyType', 'youth')
            ->where('walkingJourneyDetail.journey.progress.points', 90)
            ->where('walkingJourneyDetail.journey.progress.approvedLogsCount', 1)
            ->has('walkingJourneyDetail.journey.recentLogs', 1)
        );
    }

    public function test_journey_payload_does_not_expose_sensitive_metadata(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $this->createPointLog($person, $user, $journey, 75, 'approved', ['secret' => 'hidden']);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->has('walkingJourneyDetail.journey.recentLogs', 1)
            ->where('walkingJourneyDetail.journey.recentLogs.0.points', 75)
            ->missing('walkingJourneyDetail.journey.recentLogs.0.metadata')
            ->missing('walkingJourneyDetail.journey.recentLogs.0.user_id')
            ->missing('walkingJourneyDetail.journey.recentLogs.0.approved_by')
            ->missing('walkingJourneyDetail.journey.recentLogs.0.rejected_by')
        );
    }

    public function test_journey_detail_read_does_not_create_operational_records(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/geral')->assertOk();

        $this->assertSame(0, WalkingPointLog::count());
        $this->assertSame(0, PersonWalkingAchievement::count());
        $this->assertSame(0, WalkingHighlight::count());
        $this->assertSame(0, WalkingMentorResponseLog::count());
        $this->assertSame(0, WalkingHistoryEvent::count());
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
            'full_name' => 'Pessoa Jornada',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        return [$person, $user];
    }

    private function attachYouthDepartment(Person $person): void
    {
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
            'notes' => 'Registro de teste aprovado para detalhe da jornada.',
            'metadata' => $metadata ?: ['internal' => 'not exposed'],
        ]);
    }

    private function createPersonAchievement(Person $person, WalkingJourney $journey, WalkingAchievement $achievement): PersonWalkingAchievement
    {
        return PersonWalkingAchievement::create([
            'person_id' => $person->id,
            'walking_achievement_id' => $achievement->id,
            'walking_journey_id' => $journey->id,
            'status' => 'received',
            'progress_current' => 1,
            'progress_target' => 1,
            'awarded_at' => now(),
        ]);
    }
}
