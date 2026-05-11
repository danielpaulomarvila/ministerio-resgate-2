<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FamilyHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_rota_familia_existe(): void
    {
        $this->assertTrue(Route::has('familia.index'));
    }

    public function test_rota_familia_exige_autenticacao(): void
    {
        $response = $this->get('/familia');
        $response->assertRedirect('/login');
    }

    public function test_usuario_autenticado_pode_acessar_familia(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/familia');
        $response->assertStatus(200);
    }

    public function test_pagina_carrega_mesmo_sem_person_id(): void
    {
        $user = User::factory()->create(['person_id' => null]);
        $response = $this->actingAs($user)->get('/familia');
        $response->assertStatus(200);
    }
}
