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
        Schema::create('walking_point_logs', function (Blueprint $table) {
            // Registro auditável dos pontos gerados na caminhada.
            $table->id();
            $table->foreignId('person_id')
                ->constrained('people')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('walking_journey_id')
                ->constrained('walking_journeys')
                ->restrictOnDelete();
            $table->foreignId('walking_point_rule_id')
                ->nullable()
                ->constrained('walking_point_rules')
                ->nullOnDelete();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained('families')
                ->nullOnDelete();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('category');
            $table->integer('points');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled']);
            $table->string('validation_type')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Índices para consultas por pessoa, origem, status e categoria.
            $table->index(['person_id', 'walking_journey_id']);
            $table->index(['source_type', 'source_id']);
            $table->index('status');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_point_logs');
    }
};
