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
        // Tabela para vínculo entre pessoas e departamentos (tabela pivot)
        // Uma pessoa pode estar em vários departamentos
        // Um departamento pode ter várias pessoas
        // No departamento Resgatados: category = junior (11-13 anos) ou jovem (14-17 anos)
        Schema::create('department_people', function (Blueprint $table) {
            $table->id();

            // Vinculações
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');

            // Função e categoria
            $table->string('role_name')->nullable(); // Função: líder, auxiliar, integrante, vocal, músico, operador, tesoureiro, etc.
            $table->string('category')->nullable(); // Categoria usada no departamento Resgatados: junior ou jovem

            // Controle de período
            $table->date('starts_at')->default(now()); // Data de início no departamento
            $table->date('ends_at')->nullable(); // Data de saída do departamento (para histórico)

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Índices
            $table->index('department_id');
            $table->index('person_id');
            $table->index('role_name');
            $table->index('category');
            $table->index('status');

            // Unique para evitar duplicidade de vínculo ativo
            $table->unique(['department_id', 'person_id', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_people');
    }
};
