<?php

namespace Tests\Feature;

use App\Policies\SystemAlertPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes básicos para SystemAlertPolicy
 * Verifica se a policy existe e tem os métodos corretos
 * 
 * Nota: Testes de autorização completa requerem dados no banco e
 * configuração de perfis, o que está fora do escopo deste teste básico.
 */
class SystemAlertPolicyTest extends TestCase
{
    /**
     * Testa se SystemAlertPolicy existe
     */
    public function test_system_alert_policy_exists(): void
    {
        $policy = new SystemAlertPolicy();
        $this->assertInstanceOf(SystemAlertPolicy::class, $policy);
    }

    /**
     * Testa se SystemAlertPolicy tem os métodos de autorização
     */
    public function test_system_alert_policy_has_authorization_methods(): void
    {
        $policy = new SystemAlertPolicy();
        
        $methods = ['viewAny', 'view', 'create', 'update', 'resolve', 'delete'];
        
        foreach ($methods as $method) {
            $this->assertTrue(method_exists($policy, $method), "SystemAlertPolicy deve ter método {$method}");
        }
    }
}
