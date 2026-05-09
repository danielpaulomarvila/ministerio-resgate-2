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
        // Tabela de vínculo entre pessoas e famílias (tabela pivot)
        // Uma pessoa pode estar vinculada a uma família
        // Futuramente pode ser necessário histórico de vínculo familiar
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();

            // Vinculações
            $table->foreignId('family_id')->constrained('families')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');

            // Tipo de relacionamento familiar
            $table->enum('relationship_type', ['father', 'mother', 'son', 'daughter', 'spouse', 'guardian', 'other'])->default('other');
            $table->boolean('is_main_responsible')->default(false); // Indica se é o responsável principal

            // Controle de período do vínculo
            $table->date('starts_at')->default(now()); // Data de início do vínculo
            $table->date('ends_at')->nullable(); // Data de fim do vínculo (para histórico)

            $table->timestamps();

            // Índices
            $table->index('family_id');
            $table->index('person_id');
            $table->index('relationship_type');

            // Unique para evitar duplicidade de vínculo ativo
            $table->unique(['family_id', 'person_id', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
