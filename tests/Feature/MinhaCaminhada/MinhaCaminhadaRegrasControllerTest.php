<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\User;
use App\Models\WalkingJourney;
use App\Models\WalkingPointRule;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaRegrasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_rules_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/regras');

        $response->assertRedirect('/login');
    }

    public function test_rules_page_returns_real_general_rules(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'regras')
            ->where('journey', 'all')
            ->where('walkingRules.authorized', true)
            ->where('walkingRules.usesRealData', true)
            ->where('walkingRules.variant', 'rules')
            ->where('walkingRules.general.authorized', true)
            ->where('walkingRules.general.journeyType', 'general')
            ->has('walkingRules.general.rules.0')
            ->where('walkingRules.general.rules.0.isActive', true)
        );
        $response->assertDontSee('general_intercession_validated');
    }

    public function test_rules_page_blocks_youth_rules_for_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingRules.canSeeYouthJourney', false)
            ->where('walkingRules.youth.authorized', false)
            ->where('walkingRules.youth.rules', [])
        );
        $response->assertDontSee('youth_resgatados_attendance');
    }

    public function test_rules_page_allows_youth_rules_for_youth_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $this->attachYouthDepartment($person);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingRules.canSeeYouthJourney', true)
            ->where('walkingRules.youth.authorized', true)
            ->where('walkingRules.youth.journeyType', 'youth')
            ->has('walkingRules.youth.rules.0')
            ->where('walkingRules.youth.rules.0.isActive', true)
        );
    }

    public function test_point_rules_alias_uses_same_real_payload(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras-de-pontos');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'regras')
            ->where('walkingRules.usesRealData', true)
            ->where('walkingRules.variant', 'point_rules')
            ->where('walkingRules.general.authorized', true)
            ->has('walkingRules.general.rules.0')
        );
    }

    public function test_points_alias_uses_same_real_payload(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/pontuacao');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'regras')
            ->where('walkingRules.usesRealData', true)
            ->where('walkingRules.variant', 'points')
            ->where('walkingRules.general.authorized', true)
            ->has('walkingRules.general.rules.0')
        );
    }

    public function test_inactive_rules_are_not_returned(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::where('type', 'general')->firstOrFail();
        WalkingPointRule::create([
            'walking_journey_id' => $journey->id,
            'key' => 'inactive_test_rule',
            'name' => 'Regra inativa de teste',
            'description' => 'Esta regra não deve aparecer no payload público.',
            'category' => 'test',
            'points' => 99,
            'validation_type' => 'manual',
            'source_origin' => 'test',
            'daily_limit' => null,
            'weekly_limit' => null,
            'monthly_limit' => null,
            'counts_for_level' => true,
            'counts_for_highlight' => true,
            'is_fixed_system_rule' => false,
            'can_edit_points' => true,
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras');

        $response->assertOk();
        $response->assertDontSee('inactive_test_rule');
        $response->assertDontSee('Regra inativa de teste');
    }

    public function test_sensitive_or_inactive_intercession_rule_is_not_returned_if_inactive(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $this->assertDatabaseHas('walking_point_rules', [
            'key' => 'general_intercession_validated',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/regras');

        $response->assertOk();
        $response->assertDontSee('general_intercession_validated');
        $response->assertDontSee('Intercessão validada com cuidado pastoral');
    }

    private function seedWalkingBase(): void
    {
        $this->seed(WalkingJourneySeeder::class);
        $this->seed(WalkingLevelSeeder::class);
        $this->seed(WalkingPointRuleSeeder::class);
        $this->seed(WalkingPermissionSeeder::class);
    }

    private function createPersonAndUser(): array
    {
        $person = Person::create([
            'full_name' => 'Pessoa Regras',
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
}
