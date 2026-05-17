<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
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

class MinhaCaminhadaReconhecimentoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ranking_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertRedirect('/login');
    }

    public function test_monthly_highlights_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/destaques/mensal');

        $response->assertRedirect('/login');
    }

    public function test_ranking_returns_safe_real_payload(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser('Pessoa Reconhecida');
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $this->createPointLog($person, $user, $journey, 120, 'approved');
        $highlight = $this->createHighlight($person, $user, $journey, 'general', 'Presença', 'Constância em Presença');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'ranking')
            ->where('journey', 'all')
            ->where('walkingRecognition.authorized', true)
            ->where('walkingRecognition.usesRealData', true)
            ->where('walkingRecognition.variant', 'ranking')
            ->where('walkingRecognition.person.id', $person->id)
            ->where('walkingRecognition.canSeeYouthJourney', false)
            ->where('walkingRecognition.general.authorized', true)
            ->where('walkingRecognition.general.journeyType', 'general')
            ->has('walkingRecognition.general.items', 1)
            ->where('walkingRecognition.general.items.0.id', $highlight->id)
            ->where('walkingRecognition.general.items.0.displayName', 'Pessoa Reconhecida')
            ->where('walkingRecognition.general.items.0.points', 120)
            ->where('walkingRecognition.general.items.0.highlightLabel', 'Constância em Presença')
            ->where('walkingRecognition.general.items.0.position', null)
            ->missing('walkingRecognition.general.items.0.metadata')
            ->missing('walkingRecognition.general.items.0.approved_by')
            ->missing('walkingRecognition.general.items.0.generated_by')
        );
    }

    public function test_monthly_highlights_uses_same_safe_payload_with_monthly_variant(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $this->createHighlight($person, $user, $journey, 'general', 'Serviço', 'Serviço Aprovado', 'monthly');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/destaques/mensal');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('walkingRecognition.variant', 'monthly_highlights')
            ->has('walkingRecognition.general.monthlyHighlights', 1)
            ->where('walkingRecognition.general.monthlyHighlights.0.highlightLabel', 'Serviço Aprovado')
            ->missing('walkingRecognition.general.monthlyHighlights.0.metadata')
        );
    }

    public function test_common_user_does_not_see_youth_recognition(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $youthPerson = Person::create(['full_name' => 'Jovem Protegido', 'person_status' => 'active']);
        $journey = WalkingJourney::query()->where('type', 'youth')->firstOrFail();
        $this->createHighlight($youthPerson, $user, $journey, 'youth', 'Resgatados', 'Destaque Jovem');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingRecognition.canSeeYouthJourney', false)
            ->where('walkingRecognition.youth.authorized', false)
            ->where('walkingRecognition.youth.items', [])
            ->where('walkingRecognition.youth.monthlyHighlights', [])
        );
        $response->assertDontSee('Jovem Protegido');
        $response->assertDontSee('Destaque Jovem');
    }

    public function test_authorized_youth_user_can_see_youth_recognition(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser('Jovem Autorizado');
        $this->attachYouthDepartment($person);
        $journey = WalkingJourney::query()->where('type', 'youth')->firstOrFail();
        $this->createPointLog($person, $user, $journey, 90, 'approved');
        $this->createHighlight($person, $user, $journey, 'youth', 'Resgatados', 'Constância Jovem');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingRecognition.canSeeYouthJourney', true)
            ->where('walkingRecognition.youth.authorized', true)
            ->has('walkingRecognition.youth.items', 1)
            ->where('walkingRecognition.youth.items.0.displayName', 'Jovem Autorizado')
            ->where('walkingRecognition.youth.items.0.points', 90)
        );
    }

    public function test_recognition_payload_does_not_expose_sensitive_data(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();
        $this->createHighlight($person, $user, $journey, 'general', 'Presença', 'Destaque Seguro', 'monthly', ['secret' => 'hidden']);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->has('walkingRecognition.general.items', 1)
            ->where('walkingRecognition.general.items.0.highlightLabel', 'Destaque Seguro')
            ->missing('walkingRecognition.general.items.0.metadata')
            ->missing('walkingRecognition.general.items.0.family_id')
            ->missing('walkingRecognition.general.items.0.visibility')
            ->missing('walkingRecognition.general.items.0.person_id')
        );
    }

    public function test_recognition_read_does_not_create_operational_records(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking')->assertOk();

        $this->assertSame(0, WalkingPointLog::count());
        $this->assertSame(0, WalkingHighlight::count());
        $this->assertSame(0, PersonWalkingAchievement::count());
        $this->assertSame(0, WalkingMentorResponseLog::count());
        $this->assertSame(0, WalkingHistoryEvent::count());
    }

    public function test_without_approved_highlights_returns_safe_empty_state(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingRecognition.general.items', [])
            ->where('walkingRecognition.general.monthlyHighlights', [])
            ->where('walkingRecognition.general.emptyState.title', 'Nenhum destaque aprovado ainda.')
            ->where('walkingRecognition.emptyStates.withoutHighlights.text', 'Quando houver reconhecimentos validados pela liderança, eles aparecerão aqui.')
        );
    }

    public function test_inactive_unapproved_hidden_or_sensitive_highlights_are_not_returned(): void
    {
        $this->seedWalkingBase();
        [$person, $user] = $this->createPersonAndUser();
        $journey = WalkingJourney::query()->where('type', 'general')->firstOrFail();

        $this->createHighlight($person, $user, $journey, 'general', 'Presença', 'Aprovado Visível');
        $this->createHighlight($person, $user, $journey, 'general', 'Serviço', 'Sem Aprovação', 'monthly', [], false, true);
        $this->createHighlight($person, $user, $journey, 'general', 'Palavra', 'Oculto', 'monthly', [], true, false);
        $this->createHighlight($person, $user, $journey, 'general', 'intercession', 'Intercessão Sensível');
        $this->createHighlight($person, $user, $journey, 'intercession', 'Presença', 'Tipo Sensível');
        $this->createHighlight($person, $user, $journey, 'general', 'Presença', 'Privado', 'monthly', [], true, true, 'private_to_user');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/ranking');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->has('walkingRecognition.general.items', 1)
            ->where('walkingRecognition.general.items.0.highlightLabel', 'Aprovado Visível')
        );
        $response->assertDontSee('Sem Aprovação');
        $response->assertDontSee('Oculto');
        $response->assertDontSee('Intercessão Sensível');
        $response->assertDontSee('Tipo Sensível');
        $response->assertDontSee('Privado');
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

    private function createPersonAndUser(string $name = 'Pessoa Reconhecimento'): array
    {
        $person = Person::create([
            'full_name' => $name,
            'preferred_name' => $name,
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
            'notes' => 'Registro aprovado para reconhecimento.',
            'metadata' => ['internal' => 'not exposed'],
        ]);
    }

    private function createHighlight(
        Person $person,
        User $user,
        WalkingJourney $journey,
        string $type,
        string $category,
        string $title,
        string $periodType = 'monthly',
        array $metadata = [],
        bool $approved = true,
        bool $visible = true,
        string $visibility = 'public_to_user'
    ): WalkingHighlight {
        return WalkingHighlight::create([
            'person_id' => $person->id,
            'walking_journey_id' => $journey->id,
            'type' => $type,
            'category' => $category,
            'title' => $title,
            'description' => 'Reconhecimento validado pela liderança.',
            'period_type' => $periodType,
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'visibility' => $visibility,
            'generated_by' => $user->id,
            'approved_by' => $approved ? $user->id : null,
            'approved_at' => $approved ? now() : null,
            'is_visible' => $visible,
            'metadata' => $metadata ?: ['internal' => 'not exposed'],
        ]);
    }
}
