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
        Schema::create('walking_mentor_response_logs', function (Blueprint $table) {
            // Histórico de exibição das respostas do mentor para evitar repetição e apoiar auditoria.
            $table->id();
            $table->foreignId('person_id')
                ->constrained('people')
                ->restrictOnDelete();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained('families')
                ->nullOnDelete();
            $table->foreignId('walking_journey_id')
                ->nullable()
                ->constrained('walking_journeys')
                ->nullOnDelete();
            $table->string('analysis_type');
            $table->foreignId('walking_mentor_response_template_id')->nullable();
            $table->string('response_variant')->nullable();
            $table->json('context_summary')->nullable();
            $table->enum('status', ['displayed', 'ignored', 'completed', 'replaced']);
            $table->timestamp('displayed_at')->nullable();
            $table->timestamps();

            // Nome curto evita limite de identificador em bancos como MySQL.
            $table->foreign('walking_mentor_response_template_id', 'wmrl_template_fk')
                ->references('id')
                ->on('walking_mentor_response_templates')
                ->nullOnDelete();

            // Índices para consultas por pessoa, família, jornada, análise e status.
            $table->index('person_id');
            $table->index('family_id');
            $table->index('walking_journey_id');
            $table->index('analysis_type');
            $table->index('status');
            $table->index('displayed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_mentor_response_logs');
    }
};
