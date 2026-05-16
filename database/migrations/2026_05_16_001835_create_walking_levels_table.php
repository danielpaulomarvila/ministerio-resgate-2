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
        Schema::create('walking_levels', function (Blueprint $table) {
            // Cada jornada possui sua própria sequência de níveis.
            $table->id();
            $table->foreignId('walking_journey_id')
                ->constrained('walking_journeys')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('level_number');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('required_points')->default(0);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Evita duplicar o mesmo número de nível dentro da mesma jornada.
            $table->unique(['walking_journey_id', 'level_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_levels');
    }
};
