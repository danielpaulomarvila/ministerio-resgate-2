<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\User;
use App\Models\WalkingMentorResponseLog;
use Database\Seeders\WalkingAchievementSeeder;
use Database\Seeders\WalkingJourneySeeder;
use Database\Seeders\WalkingLevelSeeder;
use Database\Seeders\WalkingMentorResponseTemplateSeeder;
use Database\Seeders\WalkingPermissionSeeder;
use Database\Seeders\WalkingPointRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaMentorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentor_page_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertRedirect('/login');
    }

    public function test_mentor_page_returns_safe_payload_for_authenticated_user(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'mentor')
            ->where('journey', 'all')
            ->where('walkingMentor.authorized', true)
            ->where('walkingMentor.usesRealData', true)
            ->where('walkingMentor.usesExternalAi', false)
            ->where('walkingMentor.person.id', $person->id)
            ->where('walkingMentor.canSeeYouthJourney', false)
            ->where('walkingMentor.general.authorized', true)
            ->where('walkingMentor.general.message.generatedBy', 'rules')
            ->where('walkingMentor.youth.authorized', false)
        );
    }

    public function test_mentor_does_not_create_response_log_on_read(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $this->assertSame(0, WalkingMentorResponseLog::count());

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertOk();
        $this->assertSame(0, WalkingMentorResponseLog::count());
    }

    public function test_mentor_blocks_youth_for_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingMentor.canSeeYouthJourney', false)
            ->where('walkingMentor.youth.authorized', false)
            ->where('walkingMentor.youth.message', null)
        );
    }

    public function test_mentor_allows_youth_user(): void
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

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingMentor.canSeeYouthJourney', true)
            ->where('walkingMentor.youth.authorized', true)
            ->where('walkingMentor.youth.journeyType', 'youth')
            ->where('walkingMentor.youth.message.generatedBy', 'rules')
            ->where('walkingMentor.usesExternalAi', false)
        );
    }

    public function test_mentor_payload_contains_pastoral_limit(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/mentor');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingMentor.usesExternalAi', false)
            ->where('walkingMentor.pastoralDisclaimer.usesExternalAi', false)
            ->where('walkingMentor.pastoralDisclaimer.text', 'O Mentor da Caminhada é um apoio simples com mensagens pré-aprovadas. Ele não substitui pastor, liderança, discipulado, aconselhamento pastoral ou acompanhamento humano.')
            ->where('walkingMentor.general.limits.0', 'Não substitui pastor, liderança, discipulado ou aconselhamento pastoral.')
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
            'full_name' => 'Pessoa Mentor',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        return [$person, $user];
    }
}
