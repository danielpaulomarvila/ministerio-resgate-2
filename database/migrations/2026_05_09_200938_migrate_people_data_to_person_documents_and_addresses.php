<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para migrar dados de people para person_documents e person_addresses
 * 
 * Esta migration migra os dados de documentos e moradas da tabela people
 * para as novas tabelas person_documents e person_addresses, permitindo
 * que a tabela principal fique mais limpa e organizada.
 * 
 * Mapeamento de campos:
 * 
 * Documentos (people -> person_documents):
 * - people.nif -> person_documents.nif
 * - people.secondary_document -> person_documents.other_document
 * 
 * Morada (people -> person_addresses):
 * - people.address -> person_addresses.address_line
 * - people.address_number -> person_addresses.door_number
 * - people.address_complement -> person_addresses.address_complement
 * - people.neighborhood -> person_addresses.parish_name (compatibilidade antiga)
 * - people.postal_code -> person_addresses.postal_code
 * - people.city -> person_addresses.municipality_name (compatibilidade antiga)
 * - people.state -> person_addresses.district_name (compatibilidade antiga)
 * - people.country -> person_addresses.country_name
 * 
 * Contacto (people -> people):
 * - people.phone -> people.primary_phone (será renomeado em migration posterior)
 * - people.notes -> people.general_notes (será renomeado em migration posterior)
 */
return new class extends Migration
{
    /**
     * Executa a migration migrando dados de people para person_documents e person_addresses
     */
    public function up(): void
    {
        // Migrar dados de documentos para person_documents
        // Verifica se as tabelas existem antes de migrar
        if (Schema::hasTable('people') && Schema::hasTable('person_documents')) {
            // Verifica se a coluna nif existe em people (pode não existir em instalação limpa)
            if (Schema::hasColumn('people', 'nif')) {
                $now = now()->toDateTimeString();
                DB::statement("
                    INSERT INTO person_documents (person_id, nif, other_document, created_at, updated_at)
                    SELECT 
                        id as person_id,
                        nif,
                        secondary_document as other_document,
                        '{$now}' as created_at,
                        '{$now}' as updated_at
                    FROM people
                    WHERE nif IS NOT NULL OR secondary_document IS NOT NULL
                ");
            }
        }

        // Migrar dados de morada para person_addresses
        if (Schema::hasTable('people') && Schema::hasTable('person_addresses')) {
            // Verifica se as colunas de endereço existem em people (podem não existir em instalação limpa)
            if (Schema::hasColumn('people', 'address') || Schema::hasColumn('people', 'city')) {
                $now = now()->toDateTimeString();
                DB::statement("
                    INSERT INTO person_addresses (
                        person_id, 
                        country_name, 
                        district_name, 
                        municipality_name, 
                        parish_name, 
                        locality_name, 
                        address_line, 
                        door_number, 
                        address_complement, 
                        postal_code, 
                        is_primary, 
                        created_at, 
                        updated_at
                    )
                    SELECT 
                        id as person_id,
                        country as country_name,
                        state as district_name,
                        city as municipality_name,
                        neighborhood as parish_name,
                        city as locality_name,
                        address as address_line,
                        address_number as door_number,
                        address_complement,
                        postal_code,
                        1 as is_primary,
                        '{$now}' as created_at,
                        '{$now}' as updated_at
                    FROM people
                    WHERE address IS NOT NULL OR city IS NOT NULL OR postal_code IS NOT NULL
                ");
            }
        }
    }

    /**
     * Reverte a migration removendo os dados migrados
     */
    public function down(): void
    {
        // Remover dados migrados de person_documents
        if (Schema::hasTable('person_documents')) {
            DB::statement("TRUNCATE TABLE person_documents");
        }

        // Remover dados migrados de person_addresses
        if (Schema::hasTable('person_addresses')) {
            DB::statement("TRUNCATE TABLE person_addresses");
        }
    }
};
