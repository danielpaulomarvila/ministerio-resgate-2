<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela para auditoria e histórico de ações
        // Usada para rastrear alterações importantes futuramente
        // Essencial para segurança e compliance
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Usuário que realizou a ação
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Detalhes da ação
            $table->string('action'); // Ação realizada: create, update, delete, login, etc.
            $table->string('module')->nullable(); // Módulo afetado: people, families, members, etc.
            $table->text('description')->nullable(); // Descrição detalhada da ação

            // Valores antes e depois (para auditoria)
            $table->json('old_values')->nullable(); // Valores antes da alteração
            $table->json('new_values')->nullable(); // Valores depois da alteração

            // Informações de contexto
            $table->string('ip_address')->nullable(); // Endereço IP do usuário
            $table->string('user_agent')->nullable(); // User agent (navegador/dispositivo)

            $table->timestamps();

            // Índices
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
