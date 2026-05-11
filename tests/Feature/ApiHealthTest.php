<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes básicos para endpoint de health check da API
 */
class ApiHealthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se endpoint /api/health retorna status ok
     */
    public function test_api_health_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'service' => 'Ministério Resgate API',
            ])
            ->assertJsonStructure([
                'status',
                'service',
                'version',
                'timestamp',
                'environment',
            ]);
    }

    /**
     * Testa se timestamp está presente e é válido
     */
    public function test_api_health_timestamp_is_valid(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
        
        $timestamp = $response->json('timestamp');
        $this->assertNotNull($timestamp);
        
        // Verifica se é um formato ISO 8601 válido
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/',
            $timestamp
        );
    }
}
