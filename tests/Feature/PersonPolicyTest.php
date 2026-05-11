<?php

namespace Tests\Feature;

use App\Policies\PersonPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes básicos para PersonPolicy
 * Verifica se a policy existe e tem os métodos corretos
 * 
 * Nota: Testes de autorização completa requerem dados no banco e
 * configuração de perfis, o que está fora do escopo deste teste básico.
 */
class PersonPolicyTest extends TestCase
{
    /**
     * Testa se PersonPolicy existe
     */
    public function test_person_policy_exists(): void
    {
        $policy = new PersonPolicy();
        $this->assertInstanceOf(PersonPolicy::class, $policy);
    }

    /**
     * Testa se PersonPolicy tem os métodos de autorização
     */
    public function test_person_policy_has_authorization_methods(): void
    {
        $policy = new PersonPolicy();
        
        $methods = ['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'];
        
        foreach ($methods as $method) {
            $this->assertTrue(method_exists($policy, $method), "PersonPolicy deve ter método {$method}");
        }
    }
}
