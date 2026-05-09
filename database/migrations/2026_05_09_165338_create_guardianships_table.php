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
        // Tabela para responsáveis por menores de idade
        // Usada para crianças e Júniores (11-13 anos)
        // Define responsável legal, financeiro e quem aprova alterações
        // Essencial para cantina e financeiro futuros
        Schema::create('guardianships', function (Blueprint $table) {
            $table->id();

            // Vinculações: menor e responsável
            $table->foreignId('minor_person_id')->constrained('people')->onDelete('cascade'); // Pessoa menor de idade
            $table->foreignId('guardian_person_id')->constrained('people')->onDelete('cascade'); // Responsável

            // Tipo de relacionamento
            $table->enum('relationship_type', ['father', 'mother', 'legal_guardian', 'other'])->default('other');

            // Permissões e responsabilidades
            $table->boolean('is_legal_guardian')->default(false); // Responsável legal
            $table->boolean('is_financial_responsible')->default(false); // Responsável financeiro (para cantina, etc.)
            $table->boolean('can_approve_changes')->default(false); // Pode aprovar alterações no cadastro
            $table->boolean('can_view_financial')->default(false); // Pode ver dados financeiros

            // Controle de período
            $table->date('starts_at')->default(now()); // Início da responsabilidade
            $table->date('ends_at')->nullable(); // Fim da responsabilidade (para histórico)

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Índices
            $table->index('minor_person_id');
            $table->index('guardian_person_id');
            $table->index('status');

            // Unique para evitar duplicidade de responsabilidade ativa
            $table->unique(['minor_person_id', 'guardian_person_id', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardianships');
    }
};
