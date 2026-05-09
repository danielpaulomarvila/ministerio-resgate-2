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
        // Tabela para alertas internos do sistema
        // Tipos futuros: child_turning_11, missing_guardian, pending_member_update, incomplete_registration, financial_warning
        // Alertas ajudam a Secretaria a agir proativamente
        Schema::create('system_alerts', function (Blueprint $table) {
            $table->id();

            // Tipo e descrição do alerta
            $table->string('type'); // Tipo do alerta: child_turning_11, missing_guardian, etc.
            $table->string('title'); // Título do alerta
            $table->text('message'); // Mensagem detalhada

            // Vinculações opcionais
            $table->foreignId('related_person_id')->nullable()->constrained('people')->onDelete('set null'); // Pessoa relacionada
            $table->foreignId('related_family_id')->nullable()->constrained('families')->onDelete('set null'); // Família relacionada

            // Prioridade e status
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium'); // Severidade do alerta
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'dismissed'])->default('pending'); // Status do alerta
            $table->date('due_date')->nullable(); // Data limite para resolução

            // Resolução
            $table->timestamp('resolved_at')->nullable(); // Data de resolução
            $table->foreignId('resolved_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuário que resolveu

            $table->timestamps();

            // Índices
            $table->index('type');
            $table->index('status');
            $table->index('severity');
            $table->index('due_date');
            $table->index('related_person_id');
            $table->index('related_family_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_alerts');
    }
};
