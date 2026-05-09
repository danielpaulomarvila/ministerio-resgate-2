<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para adicionar campos ao cadastro de pessoas
 * Módulo Secretaria - Fase 2.1 (Melhoria Controlada)
 * 
 * Esta migration adiciona campos para melhorar o cadastro de pessoas,
 * preparando o sistema para integração futura com famílias, responsáveis,
 * membros e usuários.
 * 
 * Campos adicionados:
 * - Estado civil e escolaridade
 * - Documento secundário e telefone secundário
 * - Endereço completo estruturado
 * - Data de conversão
 * - Quem convidou/influenciou/indicou a pessoa
 * - Ajuste no person_status para incluir novos valores
 */
return new class extends Migration
{
    /**
     * Executa a migration adicionando os novos campos
     */
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Estado civil (single, married, divorced, widowed, separated)
            $table->string('marital_status')->nullable()->after('gender');
            
            // Nível de escolaridade (elementary, high_school, college, postgraduate, other)
            $table->string('education_level')->nullable()->after('marital_status');
            
            // Documento secundário (RG, CNH, etc.)
            $table->string('secondary_document')->nullable()->after('document_number');
            
            // Telefone secundário
            $table->string('secondary_phone')->nullable()->after('phone');
            
            // Endereço completo estruturado
            $table->string('address')->nullable()->after('secondary_phone');
            $table->string('address_number')->nullable()->after('address');
            $table->string('address_complement')->nullable()->after('address_number');
            $table->string('neighborhood')->nullable()->after('address_complement');
            $table->string('postal_code')->nullable()->after('neighborhood');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->default('Brasil')->after('state');
            
            // Data de conversão
            $table->date('conversion_date')->nullable()->after('baptism_date');
            
            // Quem convidou/influenciou/indicou a pessoa (único campo)
            // Foreign key nullable para people, permitindo seleção de pessoa existente
            $table->foreignId('invited_by_person_id')->nullable()->after('conversion_date')->constrained('people')->nullOnDelete();
            
            // Índices para performance
            $table->index('city');
            $table->index('state');
            $table->index('invited_by_person_id');
        });
        
        // Atualizar person_status para incluir novos valores
        // Antes: active, inactive, visitor, congregated
        // Depois: active, inactive, visitor, congregant, discipling, new_convert, regularization
        // Nota: SQLite não suporta MODIFY COLUMN nativamente, então só executamos em MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE people MODIFY COLUMN person_status ENUM('active', 'inactive', 'visitor', 'congregant', 'discipling', 'new_convert', 'regularization')");
        }
    }

    /**
     * Reverte a migration removendo os campos adicionados
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Remover campos na ordem inversa
            $table->dropForeign(['invited_by_person_id']);
            $table->dropIndex(['invited_by_person_id']);
            $table->dropColumn('invited_by_person_id');
            
            $table->dropColumn('conversion_date');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropIndex(['state']);
            $table->dropColumn('city');
            $table->dropIndex(['city']);
            $table->dropColumn('postal_code');
            $table->dropColumn('neighborhood');
            $table->dropColumn('address_complement');
            $table->dropColumn('address_number');
            $table->dropColumn('address');
            $table->dropColumn('secondary_phone');
            $table->dropColumn('secondary_document');
            $table->dropColumn('education_level');
            $table->dropColumn('marital_status');
        });
        
        // Reverter person_status para valores originais
        // Nota: SQLite não suporta MODIFY COLUMN nativamente, então só executamos em MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE people MODIFY COLUMN person_status ENUM('active', 'inactive', 'visitor', 'congregated')");
        }
    }
};
