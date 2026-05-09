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
        // Tabela de departamentos da igreja
        // Departamentos futuros: Secretaria, Tesouraria, Cantina, Louvor, Mídia, Resgatados, Intercessão, Recepção, Pastoral
        // Usar slug único para identificação
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            // Dados do departamento
            $table->string('name'); // Nome do departamento
            $table->string('slug')->unique(); // Slug único para URL e referências
            $table->text('description')->nullable(); // Descrição do departamento

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes(); // Soft delete para preservar histórico

            // Índices
            $table->index('name');
            $table->index('slug');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
