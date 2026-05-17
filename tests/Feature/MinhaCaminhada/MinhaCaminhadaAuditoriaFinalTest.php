<?php

namespace Tests\Feature\MinhaCaminhada;

use App\Models\Person;
use App\Models\PersonWalkingAchievement;
use App\Models\User;
use App\Models\WalkingHighlight;
use App\Models\WalkingHistoryEvent;
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

class MinhaCaminhadaAuditoriaFinalTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_main_walking_routes_require_authentication(): void
    {
        foreach ($this->mainRoutes() as $route => $expectedComponent) {
            $this->get($route)->assertRedirect('/login');
        }
    }

    public function test_all_main_walking_routes_render_expected_components_for_common_user(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        foreach ($this->mainRoutes() as $route => $expectedComponent) {
            $response = $this->actingAs($user)->get($route);

            $response->assertOk();
            $response->assertInertia(fn (Assert $page) => $page->component($expectedComponent));
        }
    }

    public function test_common_user_youth_routes_are_safe_and_do_not_expose_youth_payloads(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FamiliaResgate/MinhaCaminhadaArea')
                ->where('walkingJourneyDetail.authorized', false)
                ->where('walkingJourneyDetail.reason', 'unauthorized_youth')
            );

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/mapa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FamiliaResgate/MinhaCaminhadaArea')
                ->where('walkingMap.canSeeYouthJourney', false)
                ->where('walkingMap.youth.authorized', false)
                ->where('walkingMap.youth.levels', [])
            );

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/jovem/niveis/1')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FamiliaResgate/MinhaCaminhadaNivel')
                ->where('walkingLevelDetail.authorized', false)
                ->where('walkingLevelDetail.reason', 'unauthorized_youth')
                ->where('walkingLevelDetail.level', null)
            );
    }

    public function test_prepared_attendance_route_keeps_honest_empty_state(): void
    {
        [, $user] = $this->createPersonAndUser();

        $this->actingAs($user)->get('/familia-resgate/minha-caminhada/presencas')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FamiliaResgate/MinhaCaminhadaArea')
                ->where('area', 'presencas')
                ->where('walkingAttendances.usesRealData', false)
                ->where('walkingAttendances.sourceAvailable', false)
                ->where('walkingAttendances.general.items', [])
                ->where('walkingAttendances.youth.items', [])
                ->where('walkingAttendances.notice.text', 'Presenças ainda não estão conectadas a um registro oficial.')
            );
    }

    public function test_walking_page_reads_do_not_create_operational_records(): void
    {
        $this->seedWalkingBase();
        [, $user] = $this->createPersonAndUser();

        foreach ($this->mainRoutes() as $route => $expectedComponent) {
            $this->actingAs($user)->get($route)->assertOk();
        }

        $this->assertSame(0, WalkingPointLog::count());
        $this->assertSame(0, WalkingHighlight::count());
        $this->assertSame(0, PersonWalkingAchievement::count());
        $this->assertSame(0, WalkingMentorResponseLog::count());
        $this->assertSame(0, WalkingHistoryEvent::count());
    }

    private function mainRoutes(): array
    {
        return [
            '/familia-resgate/minha-caminhada' => 'FamiliaResgate/MinhaCaminhada',
            '/familia-resgate/minha-caminhada/conquistas' => 'FamiliaResgate/MinhaCaminhadaConquistas',
            '/familia-resgate/minha-caminhada/nivel' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/geral' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/jovem' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/historico' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/mentor' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/regras' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/regras-de-pontos' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/pontuacao' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/ranking' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/destaques/mensal' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/presencas' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/mapa' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/geral/mapa' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/jovem/mapa' => 'FamiliaResgate/MinhaCaminhadaArea',
            '/familia-resgate/minha-caminhada/geral/niveis/1' => 'FamiliaResgate/MinhaCaminhadaNivel',
            '/familia-resgate/minha-caminhada/jovem/niveis/1' => 'FamiliaResgate/MinhaCaminhadaNivel',
            '/familia-resgate/minha-caminhada/niveis/1' => 'FamiliaResgate/MinhaCaminhadaNivel',
        ];
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
            'full_name' => 'Pessoa Auditoria Final',
            'preferred_name' => 'Pessoa',
            'person_status' => 'active',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        return [$person, $user];
    }
}
