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
        Schema::create('walking_highlights', function (Blueprint $table) {
            // Destaques saudáveis da caminhada, sem ranking espiritual.
            $table->id();
            $table->foreignId('person_id')
                ->nullable()
                ->constrained('people')
                ->nullOnDelete();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained('families')
                ->nullOnDelete();
            $table->foreignId('walking_journey_id')
                ->nullable()
                ->constrained('walking_journeys')
                ->nullOnDelete();
            $table->enum('type', ['general', 'youth', 'intercession']);
            $table->string('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('period_type', ['weekly', 'monthly', 'yearly']);
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('visibility', [
                'public_to_user',
                'private_to_user',
                'leadership_only',
                'hidden',
            ]);
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Índices para filtros de listagem, período e publicação.
            $table->index('type');
            $table->index('category');
            $table->index('period_type');
            $table->index('period_start');
            $table->index('period_end');
            $table->index('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_highlights');
    }
};
