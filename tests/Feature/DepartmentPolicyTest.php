<?php

namespace Tests\Feature;

use App\Policies\DepartmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes básicos para DepartmentPolicy
 * Verifica se a policy existe e tem os métodos corretos
 * 
 * Nota: Testes de autorização completa requerem dados no banco e
 * configuração de perfis, o que está fora do escopo deste teste básico.
 */
class DepartmentPolicyTest extends TestCase
{
    /**
     * Testa se DepartmentPolicy existe
     */
    public function test_department_policy_exists(): void
    {
        $policy = new DepartmentPolicy();
        $this->assertInstanceOf(DepartmentPolicy::class, $policy);
    }

    /**
     * Testa se DepartmentPolicy tem os métodos de autorização
     */
    public function test_department_policy_has_authorization_methods(): void
    {
        $policy = new DepartmentPolicy();
        
        $methods = ['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete', 'managePeople', 'assignLeader'];
        
        foreach ($methods as $method) {
            $this->assertTrue(method_exists($policy, $method), "DepartmentPolicy deve ter método {$method}");
        }
    }
}
