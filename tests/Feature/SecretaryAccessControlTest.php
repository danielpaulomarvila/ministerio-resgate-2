<?php

namespace Tests\Feature;

use App\Models\AccessProfile;
use App\Models\SecretaryRequest;
use App\Models\SystemAlert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecretaryAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_secretary_areas(): void
    {
        $this->get('/secretaria/alertas')->assertRedirect('/login');
        $this->get('/secretaria/solicitacoes')->assertRedirect('/login');
        $this->get('/secretaria/acessos')->assertRedirect('/login');
    }

    public function test_authenticated_user_without_secretary_access_gets_forbidden(): void
    {
        $user = User::factory()->create();
        $alert = SystemAlert::create([
            'type' => 'missing_guardian',
            'title' => 'Alerta interno',
            'message' => 'Apenas secretaria pode ver este alerta.',
            'severity' => 'medium',
            'status' => 'pending',
        ]);

        $this->actingAs($user)->get('/secretaria')->assertForbidden();
        $this->actingAs($user)->get('/secretaria/alertas')->assertForbidden();
        $this->actingAs($user)->get('/secretaria/alertas/'.$alert->id)->assertForbidden();
        $this->actingAs($user)->get('/secretaria/solicitacoes')->assertForbidden();
        $this->actingAs($user)->get('/secretaria/acessos')->assertForbidden();
    }

    public function test_secretary_user_can_access_protected_secretary_areas(): void
    {
        $user = $this->createSecretaryUser();
        $request = SecretaryRequest::create([
            'title' => 'Revisar cadastro',
            'type' => 'manual_secretary_request',
            'priority' => 'normal',
            'status' => 'pending',
            'requester_user_id' => $user->id,
        ]);

        $this->actingAs($user)->get('/secretaria')->assertOk();
        $this->actingAs($user)->get('/secretaria/solicitacoes')->assertOk();
        $this->actingAs($user)->get('/secretaria/solicitacoes/'.$request->id)->assertOk();
        $this->actingAs($user)->get('/secretaria/acessos')->assertOk();
    }

    private function createSecretaryUser(): User
    {
        $user = User::factory()->create();
        $profile = AccessProfile::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Secretaria',
            'slug' => 'secretaria',
            'description' => 'Perfil de secretaria para testes.',
            'is_system' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $user->accessProfiles()->attach($profile->id, [
            'assigned_at' => now(),
        ]);

        return $user;
    }
}
