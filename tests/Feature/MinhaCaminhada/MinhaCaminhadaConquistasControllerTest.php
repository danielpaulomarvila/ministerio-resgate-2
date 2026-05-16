<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingAchievement;
use App\Models\WalkingJourney;
use Database\Seeders\WalkingAchievementSeeder;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingMentorResponseTemplateSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaConquistasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievements_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/conquistas');

        $response->assertRedirect('/login');
    }

    public function test_achievements_page_returns_safe_payload_for_authenticated_user(): void
    {
        $this->seedWalkingBase();
        $person = Person::create([
            'full_name' => 'Pessoa Conquistas',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/conquistas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaConquistas')
            ->where('walkingAchievements.authorized', true)
            ->where('walkingAchievements.usesRealData', true)
            ->where('walkingAchievements.person.id', $person->id)
            ->where('walkingAchievements.canSeeYouthJourney', false)
            ->has('walkingAchievements.general.catalog')
            ->has('walkingAchievements.general.summary')
            ->where('walkingAchievements.general.summary.received_count', 0)
            ->where('walkingAchievements.youth.authorized', false)
            ->where('walkingAchievements.youth.catalog', [])
        );
    }

    public function test_achievements_page_does_not_expose_sensitive_catalog_without_permission(): void
    {
        $this->seedWalkingBase();
        $person = Person::create([
            'full_name' => 'Pessoa Sem Sensivel',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        WalkingAchievement::create([
            'key' => 'test_financial_sensitive_visible_check',
            'name' => 'Conquista Financeira Sensível',
            'description' => 'Não deve aparecer para usuário comum.',
            'type' => 'financial',
            'category' => 'financial',
            'visibility' => 'private_to_user',
            'requires_validation' => true,
            'is_active' => true,
        ]);
        WalkingAchievement::create([
            'key' => 'test_pastoral_sensitive_visible_check',
            'name' => 'Conquista Pastoral Sensível',
            'description' => 'Não deve aparecer para usuário comum.',
            'type' => 'pastoral_sensitive',
            'category' => 'pastoral_care',
            'visibility' => 'leadership_only',
            'requires_validation' => true,
            'is_active' => true,
        ]);
        WalkingAchievement::create([
            'key' => 'test_hidden_general_visible_check',
            'name' => 'Conquista Oculta Geral',
            'description' => 'Não deve aparecer para usuário comum.',
            'type' => 'general',
            'category' => 'service',
            'visibility' => 'hidden',
            'requires_validation' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/conquistas');

        $response->assertOk();
        $response->assertDontSee('Conquista Financeira Sensível');
        $response->assertDontSee('Conquista Pastoral Sensível');
        $response->assertDontSee('Conquista Oculta Geral');
    }

    public function test_achievements_page_includes_received_person_achievement(): void
    {
        $this->seedWalkingBase();
        $person = Person::create([
            'full_name' => 'Pessoa Com Conquista',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $achievement = WalkingAchievement::query()
            ->where('type', 'general')
            ->where('visibility', 'public_to_user')
            ->firstOrFail();

        PersonWalkingAchievement::create([
            'person_id' => $person->id,
            'walking_achievement_id' => $achievement->id,
            'walking_journey_id' => $journey->id,
            'status' => 'received',
            'progress_current' => 1,
            'progress_target' => 1,
            'awarded_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/conquistas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaConquistas')
            ->where('walkingAchievements.general.summary.received_count', 1)
            ->where('walkingAchievements.general.received.0.name', $achievement->name)
            ->where('walkingAchievements.general.received.0.status', 'received')
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
