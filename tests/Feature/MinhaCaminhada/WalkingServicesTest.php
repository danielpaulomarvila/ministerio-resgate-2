<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\AccessProfile;
use App\Models\Permission;
use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingAchievement;
use App\Models\WalkingHistoryEvent;
use App\Models\WalkingHighlight;
use App\Models\WalkingJourney;
use App\Models\WalkingMentorResponseLog;
use App\Models\WalkingPointLog;
use App\Services\MinhaCaminhada\WalkingAchievementReadService;
use App\Services\MinhaCaminhada\WalkingDashboardReadService;
use App\Services\MinhaCaminhada\WalkingLevelService;
use App\Services\MinhaCaminhada\WalkingMentorReadService;
use App\Services\MinhaCaminhada\WalkingProgressService;
use Database\Seeders\WalkingAchievementSeeder;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingMentorResponseTemplateSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WalkingServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_walking_level_service_calculates_current_and_next_level(): void
    {
        $this->seedWalkingBase();
        $journey = $this->getJourney('general');
        $service = app(WalkingLevelService::class);

        $initialProgress = $service->getLevelProgress($journey, 0);

        $this->assertSame('Primeiros Passos', $initialProgress['current_level']['name']);
        $this->assertSame('Coração Desperto', $initialProgress['next_level']['name']);
        $this->assertSame(100, $initialProgress['points_to_next_level']);

        $secondLevelProgress = $service->getLevelProgress($journey, 100);

        $this->assertSame('Coração Desperto', $secondLevelProgress['current_level']['name']);

        $finalProgress = $service->getLevelProgress($journey, 999999);

        $this->assertNull($finalProgress['next_level']);
        $this->assertSame(100, $finalProgress['progress_percentage']);
        $this->assertSame(0, $finalProgress['points_to_next_level']);
    }

    public function test_progress_service_blocks_viewer_without_person(): void
    {
        $this->seedWalkingBase();
        $viewer = User::factory()->create(['person_id' => null]);

        $progress = app(WalkingProgressService::class)->getOwnProgress($viewer);

        $this->assertFalse($progress['authorized']);
        $this->assertSame(0, $progress['total_points']);
    }

    public function test_progress_service_blocks_other_person_without_permission_or_guardian_link(): void
    {
        $this->seedWalkingBase();
        [$viewerPerson, $viewer] = $this->createPersonAndUser();
        $otherPerson = Person::create(['full_name' => 'Outra Pessoa', 'person_status' => 'active']);

        $progress = app(WalkingProgressService::class)->getPersonProgress($viewer, $otherPerson);

        $this->assertFalse($progress['authorized']);
        $this->assertSame($viewerPerson->id, $viewer->person_id);
    }

    public function test_progress_service_allows_viewing_own_general_progress(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = $this->getJourney('general');
        $this->createApprovedPointLog($person, $journey, 25);

        $progress = app(WalkingProgressService::class)->getOwnProgress($user);

        $this->assertTrue($progress['authorized']);
        $this->assertSame(25, $progress['total_points']);
        $this->assertSame(1, $progress['approved_logs_count']);
    }

    public function test_recent_approved_logs_do_not_expose_metadata(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = $this->getJourney('general');
        $this->createApprovedPointLog($person, $journey, 15, 'presence', ['internal_note' => 'não deve aparecer']);

        $logs = app(WalkingProgressService::class)->getRecentApprovedLogs($user, $person);

        $this->assertCount(1, $logs);
        $this->assertSame(['id', 'category', 'points', 'notes', 'created_at', 'source_type'], array_keys($logs[0]));
        $this->assertArrayNotHasKey('metadata', $logs[0]);
    }

    public function test_achievement_catalog_hides_sensitive_items_without_sensitive_permission(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $catalog = app(WalkingAchievementReadService::class)->getVisibleCatalog($user);

        $this->assertNotEmpty($catalog);
        $this->assertNotContains('financial', array_column($catalog, 'type'));
        $this->assertNotContains('pastoral_sensitive', array_column($catalog, 'type'));
        $this->assertContains('general', array_unique(array_column($catalog, 'type')));
    }

    public function test_achievement_catalog_allows_sensitive_items_with_sensitive_permission(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();
        WalkingAchievement::create([
            'key' => 'hidden_test_sensitive',
            'name' => 'Conquista Sensível de Teste',
            'description' => 'Conquista criada apenas no banco de teste.',
            'type' => 'general',
            'category' => 'pastoral_care',
            'visibility' => 'hidden',
            'criteria' => ['test' => true],
            'requires_validation' => true,
            'is_active' => true,
        ]);

        $withoutPermission = app(WalkingAchievementReadService::class)->getVisibleCatalog($user);
        $this->assertNotContains('hidden_test_sensitive', array_column($withoutPermission, 'key'));

        $this->giveWalkingPermission($user, 'walking.view.sensitive');
        $withPermission = app(WalkingAchievementReadService::class)->getVisibleCatalog($user);

        $this->assertContains('hidden_test_sensitive', array_column($withPermission, 'key'));
    }

    public function test_person_achievements_are_filtered_by_visibility(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = $this->getJourney('general');
        $achievement = WalkingAchievement::create([
            'key' => 'hidden_person_achievement_test',
            'name' => 'Conquista Oculta de Teste',
            'description' => 'Conquista criada apenas no banco de teste.',
            'type' => 'general',
            'category' => 'pastoral_care',
            'visibility' => 'hidden',
            'criteria' => ['test' => true],
            'requires_validation' => true,
            'is_active' => true,
        ]);
        $this->createPersonAchievement($person, $journey, $achievement);

        $withoutPermission = app(WalkingAchievementReadService::class)->getOwnAchievements($user);
        $this->assertEmpty($withoutPermission);

        $this->giveWalkingPermission($user, 'walking.view.sensitive');
        $withPermission = app(WalkingAchievementReadService::class)->getOwnAchievements($user);

        $this->assertCount(1, $withPermission);
        $this->assertSame('hidden_person_achievement_test', $withPermission[0]['achievement']['key']);
    }

    public function test_mentor_returns_pre_approved_template_without_creating_log(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $message = app(WalkingMentorReadService::class)->getOwnSuggestedMessage($user);

        $this->assertTrue($message['authorized']);
        $this->assertContains($message['source'], ['pre_approved_template', 'safe_fallback']);
        $this->assertNotEmpty($message['title']);
        $this->assertNotEmpty($message['body']);
        $this->assertSame(0, WalkingMentorResponseLog::count());
    }

    public function test_mentor_blocks_unauthorized_person(): void
    {
        $this->seedWalkingBase();
        [, $viewer] = $this->createPersonAndUser();
        $otherPerson = Person::create(['full_name' => 'Outra Pessoa Mentor', 'person_status' => 'active']);

        $message = app(WalkingMentorReadService::class)->getSuggestedMessage($viewer, $otherPerson);

        $this->assertFalse($message['authorized']);
    }

    public function test_dashboard_returns_safe_structure_for_own_general_journey(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = $this->getJourney('general');
        $this->createApprovedPointLog($person, $journey, 30, 'presence', ['internal_note' => 'não deve aparecer']);

        $dashboard = app(WalkingDashboardReadService::class)->getOwnDashboard($user);

        $this->assertTrue($dashboard['authorized']);
        $this->assertArrayHasKey('person', $dashboard);
        $this->assertArrayHasKey('progress', $dashboard);
        $this->assertArrayHasKey('achievements', $dashboard);
        $this->assertArrayHasKey('mentor', $dashboard);
        $this->assertArrayHasKey('recent_logs', $dashboard);
        $this->assertArrayHasKey('highlights', $dashboard);
        $this->assertArrayNotHasKey('metadata', $dashboard['recent_logs'][0]);
    }

    public function test_dashboard_blocks_other_person_without_permission(): void
    {
        $this->seedWalkingBase();
        [, $viewer] = $this->createPersonAndUser();
        $otherPerson = Person::create(['full_name' => 'Outra Pessoa Dashboard', 'person_status' => 'active']);

        $dashboard = app(WalkingDashboardReadService::class)->getPersonDashboard($viewer, $otherPerson);

        $this->assertFalse($dashboard['authorized']);
    }

    public function test_read_services_do_not_create_operational_records(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        app(WalkingProgressService::class)->getOwnProgress($user);
        app(WalkingAchievementReadService::class)->getOwnAchievements($user);
        app(WalkingMentorReadService::class)->getOwnSuggestedMessage($user);
        app(WalkingDashboardReadService::class)->getOwnDashboard($user);

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

    private function createPersonAndUser(array $personOverrides = [], array $userOverrides = []): array
    {
        $person = Person::create(array_merge([
            'full_name' => 'Pessoa Teste ' . Str::uuid(),
            'preferred_name' => 'Pessoa Teste',
            'email' => Str::uuid() . '@example.test',
            'person_status' => 'active',
        ], $personOverrides));

        $user = User::factory()->create(array_merge([
            'person_id' => $person->id,
            'status' => 'active',
        ], $userOverrides));

        return [$person, $user];
    }

    private function giveWalkingPermission(User $user, string $permissionSlug): void
    {
        $permission = Permission::where('slug', $permissionSlug)->firstOrFail();
        $profile = AccessProfile::updateOrCreate(
            ['slug' => 'walking-test-profile'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Perfil de Teste Minha Caminhada',
                'description' => 'Perfil usado apenas em testes automatizados.',
                'is_system' => false,
                'is_active' => true,
                'sort_order' => 999,
            ]
        );

        $profile->permissions()->syncWithoutDetaching([$permission->id]);
        $user->accessProfiles()->syncWithoutDetaching([
            $profile->id => ['assigned_at' => now()],
        ]);
    }

    private function getJourney(string $type): WalkingJourney
    {
        return WalkingJourney::where('type', $type)->firstOrFail();
    }

    private function createApprovedPointLog(Person $person, WalkingJourney $journey, int $points, string $category = 'presence', array $metadata = []): WalkingPointLog
    {
        return WalkingPointLog::create([
            'person_id' => $person->id,
            'walking_journey_id' => $journey->id,
            'category' => $category,
            'points' => $points,
            'status' => 'approved',
            'approved_at' => now(),
            'notes' => 'Log aprovado criado no teste.',
            'metadata' => $metadata,
        ]);
    }

    private function createPersonAchievement(Person $person, WalkingJourney $journey, WalkingAchievement $achievement, string $status = 'received'): PersonWalkingAchievement
    {
        return PersonWalkingAchievement::create([
            'person_id' => $person->id,
            'walking_achievement_id' => $achievement->id,
            'walking_journey_id' => $journey->id,
            'status' => $status,
            'progress_current' => 1,
            'progress_target' => 1,
            'awarded_at' => now(),
        ]);
    }
}
