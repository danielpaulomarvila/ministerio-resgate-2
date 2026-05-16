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
        Schema::create('walking_mentor_response_templates', function (Blueprint $table) {
            // Respostas pré-aprovadas do mentor, sem IA externa nesta fase.
            $table->id();
            $table->string('analysis_type');
            $table->string('key');
            $table->string('title');
            $table->text('body');
            $table->string('tone')->nullable();
            $table->enum('journey_type', ['general', 'youth']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Evita duplicidade de template dentro do mesmo tipo de análise e jornada.
            $table->unique([
                'analysis_type',
                'key',
                'journey_type',
            ], 'walking_mentor_templates_unique');
            $table->index('analysis_type');
            $table->index('journey_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_mentor_response_templates');
    }
};
