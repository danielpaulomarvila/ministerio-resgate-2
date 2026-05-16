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
        Schema::create('walking_history_events', function (Blueprint $table) {
            // Timeline visível da caminhada, separada dos logs administrativos.
            $table->id();
            $table->foreignId('person_id')
                ->constrained('people')
                ->restrictOnDelete();
            $table->foreignId('walking_journey_id')
                ->constrained('walking_journeys')
                ->restrictOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('points')->nullable();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->enum('visibility', [
                'public_to_user',
                'private_to_user',
                'leadership_only',
                'hidden',
            ]);
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            // Índices para montar a timeline filtrada por pessoa, jornada, origem e visibilidade.
            $table->index(['person_id', 'walking_journey_id']);
            $table->index('event_type');
            $table->index(['source_type', 'source_id']);
            $table->index('visibility');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_history_events');
    }
};
