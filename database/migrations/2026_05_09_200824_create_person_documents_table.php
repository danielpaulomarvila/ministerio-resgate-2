<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar a tabela person_documents
 * 
 * Esta tabela separa os documentos das pessoas da tabela principal people,
 * permitindo que a tabela de pessoas fique mais limpa e organizada.
 * 
 * Os documentos foram separados para:
 * - Evitar que a tabela people fique inchada com muitos campos
 * - Permitir histórico de documentos futuramente
 * - Facilitar a expansão para novos tipos de documentos
 * - Melhorar a organização do banco de dados
 * 
 * Contexto: Sistema para uso em Portugal
 * - NIF é o documento fiscal principal
 * - Cartão de Cidadão, Passaporte e Título de Residência são documentos comuns
 */
return new class extends Migration
{
    /**
     * Executa a migration criando a tabela person_documents
     */
    public function up(): void
    {
        Schema::create('person_documents', function (Blueprint $table) {
            $table->id();
            
            // Foreign key para people
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            
            // Documentos fiscais e de identificação
            $table->string('nif')->nullable()->unique(); // NIF (Número de Identificação Fiscal) - documento fiscal principal em Portugal
            $table->string('citizen_card_number')->nullable(); // Cartão de Cidadão
            $table->string('passport_number')->nullable(); // Passaporte
            $table->string('residence_permit_number')->nullable(); // Título de Residência
            $table->string('other_document')->nullable(); // Outro documento que não se encaixa nos campos principais
            
            // Observações sobre documentos
            $table->text('document_notes')->nullable(); // Anotações da Secretaria sobre documentos
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('person_id');
        });
    }

    /**
     * Reverte a migration removendo a tabela person_documents
     */
    public function down(): void
    {
        Schema::dropIfExists('person_documents');
    }
};
