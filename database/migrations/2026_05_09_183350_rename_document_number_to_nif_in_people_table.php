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
 * fiscal principal em Portugal, substituindo o CPF usado no Brasil.
 */
return new class extends Migration
{
    /**
     * Executa a migration renomeando document_number para nif
     */
    public function up(): void
    {
        // Renomear document_number para nif
        // Laravel Schema::renameColumn funciona em MySQL e SQLite
        Schema::table('people', function (Blueprint $table) {
            $table->renameColumn('document_number', 'nif');
        });
    }

    /**
     * Reverte a migration renomeando nif de volta para document_number
     */
    public function down(): void
    {
        // Renomear nif de volta para document_number
        Schema::table('people', function (Blueprint $table) {
            $table->renameColumn('nif', 'document_number');
        });
    }
};
