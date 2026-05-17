<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingHighlight;
use App\Models\WalkingHistoryEvent;
use App\Models\WalkingMentorResponseLog;
use App\Models\WalkingPointLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MinhaCaminhadaPresencasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendances_requires_authenticated_user(): void
    {
        $response = $this->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertRedirect('/login');
    }

    public function test_attendances_returns_safe_prepared_payload_without_official_source(): void
    {
        [$person, $user] = $this->createPersonAndUser('Pessoa Presença');

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('FamiliaResgate/MinhaCaminhadaArea')
            ->where('area', 'presencas')
            ->where('journey', 'all')
            ->where('walkingAttendances.authorized', true)
            ->where('walkingAttendances.usesRealData', false)
            ->where('walkingAttendances.sourceAvailable', false)
            ->where('walkingAttendances.person.id', $person->id)
            ->where('walkingAttendances.person.name', 'Pessoa Presença')
            ->where('walkingAttendances.canSeeYouthJourney', false)
            ->where('walkingAttendances.general.authorized', true)
            ->where('walkingAttendances.general.journeyType', 'general')
            ->where('walkingAttendances.general.sourceAvailable', false)
            ->where('walkingAttendances.general.items', [])
            ->where('walkingAttendances.general.summary.totalAttendances', 0)
            ->where('walkingAttendances.general.summary.confirmedAttendances', 0)
            ->where('walkingAttendances.general.summary.lastAttendanceAt', null)
            ->where('walkingAttendances.general.summary.attendanceRate', null)
            ->where('walkingAttendances.general.emptyState.title', 'Presenças ainda não estão conectadas a um registro oficial.')
            ->where('walkingAttendances.general.emptyState.text', 'Quando a igreja ativar o registro de presenças, elas aparecerão aqui.')
            ->where('walkingAttendances.notice.text', 'Presenças ainda não estão conectadas a um registro oficial.')
            ->where('walkingAttendances.notice.nextStepText', 'Quando a igreja ativar o registro de presenças, elas aparecerão aqui.')
        );
    }

    public function test_attendances_does_not_invent_presence_records(): void
    {
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingAttendances.general.items', [])
            ->where('walkingAttendances.youth.items', [])
            ->where('walkingAttendances.general.summary.totalAttendances', 0)
            ->where('walkingAttendances.general.summary.attendanceRate', null)
        );
        $response->assertDontSee('Culto de domingo');
        $response->assertDontSee('Culto de sexta');
        $response->assertDontSee('95%');
        $response->assertDontSee('80%');
    }

    public function test_common_user_does_not_receive_authorized_youth_attendances(): void
    {
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingAttendances.canSeeYouthJourney', false)
            ->where('walkingAttendances.youth.authorized', false)
            ->where('walkingAttendances.youth.sourceAvailable', false)
            ->where('walkingAttendances.youth.items', [])
            ->where('walkingAttendances.youth.emptyState.title', 'Presenças jovens protegidas.')
        );
    }

    public function test_authorized_youth_user_receives_empty_youth_track_without_fake_data(): void
    {
        [$person, $user] = $this->createPersonAndUser('Jovem Presença');
        $this->attachYouthDepartment($person);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingAttendances.canSeeYouthJourney', true)
            ->where('walkingAttendances.youth.authorized', true)
            ->where('walkingAttendances.youth.journeyType', 'youth')
            ->where('walkingAttendances.youth.sourceAvailable', false)
            ->where('walkingAttendances.youth.items', [])
            ->where('walkingAttendances.youth.summary.totalAttendances', 0)
            ->where('walkingAttendances.youth.summary.attendanceRate', null)
        );
    }

    public function test_attendance_payload_does_not_expose_sensitive_data(): void
    {
        [, $user] = $this->createPersonAndUser();

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->missing('walkingAttendances.general.items.0.metadata')
            ->missing('walkingAttendances.general.items.0.approved_by')
            ->missing('walkingAttendances.general.items.0.family_id')
            ->missing('walkingAttendances.youth.items.0.metadata')
            ->missing('walkingAttendances.youth.items.0.minor_document')
        );
    }

    public function test_attendance_read_does_not_create_operational_records(): void
    {
        [, $user] = $this->createPersonAndUser();

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas')->assertOk();

        $this->assertSame(0, WalkingPointLog::count());
        $this->assertSame(0, WalkingHighlight::count());
        $this->assertSame(0, PersonWalkingAchievement::count());
        $this->assertSame(0, WalkingMentorResponseLog::count());
        $this->assertSame(0, WalkingHistoryEvent::count());
    }

    public function test_user_without_person_receives_safe_empty_state(): void
    {
        $user = User::factory()->create(['person_id' => null]);

        $response = $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->where('walkingAttendances.authorized', false)
            ->where('walkingAttendances.person', null)
            ->where('walkingAttendances.general.authorized', false)
            ->where('walkingAttendances.general.items', [])
            ->where('walkingAttendances.emptyStates.withoutPerson.title', 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.')
        );
    }

    private function createPersonAndUser(string $name = 'Pessoa Presencas'): array
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
}
