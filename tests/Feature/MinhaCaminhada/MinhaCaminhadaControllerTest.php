<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Person;
use App\Models\User;
use Database\Seeders\WalkingAchievementSeeder;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingMentorResponseTemplateSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_minha_caminhada_route_returns_real_safe_dashboard_props(): void
    {
        $this->seedWalkingBase();
        $person = Person::create([
            'full_name' => 'Pessoa Caminhada',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhada')
            ->where('walkingDashboard.usesRealData', true)
            ->where('walkingDashboard.viewerContext.canSeeGeneralJourney', true)
            ->where('walkingDashboard.viewerContext.canSeeYouthJourney', false)
            ->where('walkingDashboard.general.authorized', true)
            ->where('walkingDashboard.general.progress.total_points', 0)
            ->where('walkingDashboard.general.recentLogs', [])
            ->where('walkingDashboard.general.achievements', [])
            ->where('walkingDashboard.general.highlights', [])
            ->has('walkingDashboard.general.mentor')
            ->missing('walkingDashboard.general.progress.level_progress.current_level.metadata')
        );
    }

    public function test_minha_caminhada_route_handles_user_without_person_safely(): void
    {
        $this->seedWalkingBase();
        $user = User::factory()->create(['person_id' => null]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhada')
            ->where('walkingDashboard.usesRealData', true)
            ->where('walkingDashboard.viewerContext.canSeeGeneralJourney', false)
            ->where('walkingDashboard.viewerContext.canSeeYouthJourney', false)
            ->where('walkingDashboard.general.authorized', false)
            ->where('walkingDashboard.general.recentLogs', [])
            ->where('walkingDashboard.general.achievements', [])
            ->where('walkingDashboard.general.highlights', [])
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
}
