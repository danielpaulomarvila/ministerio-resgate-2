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
        Schema::create('person_walking_achievements', function (Blueprint $table) {
            // Estado de cada conquista para uma pessoa em uma jornada.
            $table->id();
            $table->foreignId('person_id')
                ->constrained('people')
                ->restrictOnDelete();
            $table->foreignId('walking_achievement_id')
                ->constrained('walking_achievements')
                ->restrictOnDelete();
            $table->foreignId('walking_journey_id')
                ->nullable()
                ->constrained('walking_journeys')
                ->restrictOnDelete();
            // Coluna técnica para manter unicidade mesmo quando walking_journey_id for nulo no MySQL.
            $table->unsignedBigInteger('walking_journey_unique_id')
                ->storedAs('COALESCE(walking_journey_id, 0)');
            $table->enum('status', [
                'received',
                'in_progress',
                'locked',
                'hidden',
                'pending_validation',
            ]);
            $table->unsignedInteger('progress_current')->nullable();
            $table->unsignedInteger('progress_target')->nullable();
            $table->foreignId('awarded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('awarded_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Evita duplicar a mesma conquista para a mesma pessoa e jornada.
            $table->unique([
                'person_id',
                'walking_achievement_id',
                'walking_journey_unique_id',
            ], 'person_walking_achievements_unique');
            $table->index('person_id');
            $table->index('status');
            $table->index('walking_journey_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_walking_achievements');
    }
};
