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
        // Tabela principal de pessoas - qualquer pessoa cadastrada no sistema
        // Pode ser criança, adolescente, adulto, visitante ou congregado
        // Pessoa pode existir sem usuário e sem ser membro
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            // Dados pessoais básicos
            $table->string('full_name'); // Nome completo da pessoa
            $table->string('preferred_name')->nullable(); // Nome como prefere ser chamada
            $table->date('birth_date')->nullable(); // Data de nascimento para cálculo de idade
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Gênero
            $table->string('email')->unique()->nullable(); // E-mail único
            $table->string('phone')->nullable(); // Telefone de contato

            // Documentação
            $table->string('document_number')->unique()->nullable(); // CPF ou outro documento
            $table->string('photo_path')->nullable(); // Caminho da foto no storage

            // Dados de membresia e batismo
            $table->boolean('is_baptized')->default(false); // Indica se foi batizado
            $table->date('baptism_date')->nullable(); // Data do batismo

            // Status da pessoa no sistema
            $table->enum('person_status', ['active', 'inactive', 'visitor', 'congregated'])->default('active');

            // Observações gerais
            $table->text('notes')->nullable(); // Anotações importantes sobre a pessoa

            $table->timestamps();
            $table->softDeletes(); // Soft delete para preservar histórico

            // Índices para performance
            $table->index('full_name');
            $table->index('birth_date');
            $table->index('person_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
