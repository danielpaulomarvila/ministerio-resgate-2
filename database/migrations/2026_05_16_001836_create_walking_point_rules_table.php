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
        Schema::create('walking_point_rules', function (Blueprint $table) {
            // Regras oficiais de pontuação, com validação e limites por período.
            $table->id();
            $table->foreignId('walking_journey_id')
                ->nullable()
                ->constrained('walking_journeys')
                ->nullOnDelete();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->integer('points')->default(0);
            $table->enum('validation_type', [
                'automatic',
                'manual',
                'leader_validation',
                'secretary_validation',
                'pastoral_validation',
            ]);
            $table->string('source_origin')->nullable();
            $table->unsignedInteger('daily_limit')->nullable();
            $table->unsignedInteger('weekly_limit')->nullable();
            $table->unsignedInteger('monthly_limit')->nullable();
            $table->boolean('counts_for_level')->default(true);
            $table->boolean('counts_for_highlight')->default(true);
            $table->boolean('is_fixed_system_rule')->default(false);
            $table->boolean('can_edit_points')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_point_rules');
    }
};
