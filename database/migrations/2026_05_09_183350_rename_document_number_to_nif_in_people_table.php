<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para renomear document_number para nif
 * Contexto: Sistema para uso em Portugal
 * 
 * Esta migration renomeia o campo document_number para nif,
 * pois o NIF (Número de Identificação Fiscal) é o documento
 * fiscal principal em Portugal.
 */
return new class extends Migration
{
    /**
     * Executa a migration renomeando document_number para nif
     */
    public function up(): void
    {
        // Verificar se a coluna document_number existe antes de renomear
        // Isso evita erro se a migration for executada em um banco onde já foi renomeado
        if (Schema::hasColumn('people', 'document_number') && !Schema::hasColumn('people', 'nif')) {
            Schema::table('people', function (Blueprint $table) {
                $table->renameColumn('document_number', 'nif');
            });
        }
    }

    /**
     * Reverte a migration renomeando nif de volta para document_number
     */
    public function down(): void
    {
        // Verificar se a coluna nif existe antes de reverter
        // Isso evita erro se a migration for revertida em um banco onde já foi revertido
        if (Schema::hasColumn('people', 'nif') && !Schema::hasColumn('people', 'document_number')) {
            Schema::table('people', function (Blueprint $table) {
                $table->renameColumn('nif', 'document_number');
            });
        }
    }
};
