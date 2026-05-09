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
        // Adaptada para Portugal com estrutura limpa desde o início
        // Pode ser criança, adolescente, adulto, visitante ou congregado
        // Pessoa pode existir sem usuário e sem ser membro
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique()->after('id');

            // Dados pessoais básicos
            $table->string('full_name'); // Nome completo da pessoa
            $table->string('preferred_name')->nullable(); // Nome como prefere ser chamada
            $table->string('last_name')->nullable(); // Apelido/Sobrenome
            $table->date('birth_date')->nullable(); // Data de nascimento para cálculo de idade
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Gênero
            $table->string('marital_status')->nullable(); // Estado civil (single, married, divorced, widowed, separated)
            $table->string('education_level')->nullable(); // Nível de escolaridade
            $table->string('nationality')->nullable(); // Nacionalidade (ex: Portuguesa)
            $table->string('birthplace')->nullable(); // Naturalidade (ex: Lisboa)
            $table->string('profession')->nullable(); // Profissão
            $table->string('occupation')->nullable(); // Ocupação

            // Dados de contacto
            $table->string('email')->unique()->nullable(); // E-mail único
            $table->string('primary_phone')->nullable(); // Telemóvel principal
            $table->string('secondary_phone')->nullable(); // Telemóvel secundário
            $table->string('whatsapp')->nullable(); // WhatsApp
            $table->text('contact_notes')->nullable(); // Notas de contacto

            // Foto
            $table->string('photo_path')->nullable(); // Caminho da foto no storage

            // Dados de membresia e batismo
            $table->boolean('is_baptized')->default(false); // Indica se foi batizado
            $table->date('baptism_date')->nullable(); // Data do batismo
            $table->date('conversion_date')->nullable(); // Data de conversão
            $table->foreignId('invited_by_person_id')->nullable()->constrained('people')->nullOnDelete(); // Quem convidou/influenciou/indicou

            // Status da pessoa no sistema
            $table->enum('person_status', ['active', 'inactive', 'visitor', 'congregant', 'discipling', 'new_convert', 'regularization'])->default('active');

            // Observações gerais
            $table->text('general_notes')->nullable(); // Anotações importantes sobre a pessoa

            // Campos de auditoria
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes(); // Soft delete para preservar histórico

            // Índices para performance
            $table->index('full_name');
            $table->index('birth_date');
            $table->index('person_status');
            $table->index('uuid');
            $table->index('created_by_user_id');
            $table->index('updated_by_user_id');
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
