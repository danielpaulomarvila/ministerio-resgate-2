<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration para reestruturar a tabela people para Portugal
 * 
 * Esta migration reestrutura a tabela people para:
 * - Adicionar campos novos para dados pessoais e contactos
 * - Renomear campos para melhor clareza (phone -> primary_phone, notes -> general_notes)
 * - Remover campos que foram migrados para person_documents e person_addresses
 * 
 * Campos adicionados:
 * - uuid: Identificador único universal
 * - last_name: Apelido/Sobrenome separado
 * - nationality: Nacionalidade
 * - birthplace: Naturalidade
 * - profession: Profissão
 * - occupation: Ocupação
 * - whatsapp: WhatsApp
 * - contact_notes: Notas de contacto
 * - general_notes: Notas gerais (renomeado de notes)
 * - created_by_user_id: Usuário que criou
 * - updated_by_user_id: Usuário que atualizou
 * - deleted_by_user_id: Usuário que deletou
 * 
 * Campos renomeados:
 * - phone -> primary_phone
 * - notes -> general_notes
 * 
 * Campos removidos (migrados para outras tabelas):
 * - nif -> person_documents.nif
 * - secondary_document -> person_documents.other_document
 * - address -> person_addresses.address_line
 * - address_number -> person_addresses.door_number
 * - address_complement -> person_addresses.address_complement
 * - neighborhood -> person_addresses.parish_name
 * - postal_code -> person_addresses.postal_code
 * - city -> person_addresses.municipality_name
 * - state -> person_addresses.district_name
 * - country -> person_addresses.country_name
 */
return new class extends Migration
{
    /**
     * Executa a migration reestruturando a tabela people
     */
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Adicionar novos campos
            $table->uuid('uuid')->nullable()->after('id')->unique();
            $table->string('last_name')->nullable()->after('preferred_name');
            $table->string('nationality')->nullable()->after('education_level');
            $table->string('birthplace')->nullable()->after('nationality');
            $table->string('profession')->nullable()->after('birthplace');
            $table->string('occupation')->nullable()->after('profession');
            $table->string('whatsapp')->nullable()->after('secondary_phone');
            $table->text('contact_notes')->nullable()->after('photo_path');
            $table->foreignId('created_by_user_id')->nullable()->after('person_status')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_user_id')->nullable()->after('updated_by_user_id')->constrained('users')->nullOnDelete();
            
            // Renomear campos
            if (Schema::hasColumn('people', 'phone')) {
                $table->renameColumn('phone', 'primary_phone');
            }
            if (Schema::hasColumn('people', 'notes')) {
                $table->renameColumn('notes', 'general_notes');
            }
            
            // Índices para performance
            $table->index('uuid');
            $table->index('created_by_user_id');
            $table->index('updated_by_user_id');
        });

        // Remover campos que foram migrados para outras tabelas
        // Primeiro remover índices que referenciam colunas que serão removidas
        // SQLite requer que índices sejam removidos antes das colunas
        try {
            DB::statement('DROP INDEX IF EXISTS people_document_number_unique');
            DB::statement('DROP INDEX IF EXISTS people_nif_index');
            DB::statement('DROP INDEX IF EXISTS people_city_index');
            DB::statement('DROP INDEX IF EXISTS people_state_index');
        } catch (\Exception $e) {
            // Ignore error se índice não existe
        }

        Schema::table('people', function (Blueprint $table) {
            // Campos de documento (migrados para person_documents)
            if (Schema::hasColumn('people', 'nif')) {
                $table->dropColumn('nif');
            }
            if (Schema::hasColumn('people', 'secondary_document')) {
                $table->dropColumn('secondary_document');
            }
            
            // Campos de morada (migrados para person_addresses)
            if (Schema::hasColumn('people', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('people', 'address_number')) {
                $table->dropColumn('address_number');
            }
            if (Schema::hasColumn('people', 'address_complement')) {
                $table->dropColumn('address_complement');
            }
            if (Schema::hasColumn('people', 'neighborhood')) {
                $table->dropColumn('neighborhood');
            }
            if (Schema::hasColumn('people', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
            if (Schema::hasColumn('people', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('people', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('people', 'country')) {
                $table->dropColumn('country');
            }
        });
    }

    /**
     * Reverte a migration restaurando a estrutura antiga da tabela people
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Reverter renomeações
            if (Schema::hasColumn('people', 'primary_phone')) {
                $table->renameColumn('primary_phone', 'phone');
            }
            if (Schema::hasColumn('people', 'general_notes')) {
                $table->renameColumn('general_notes', 'notes');
            }
            
            // Re-adicionar campos que foram migrados
            $table->string('nif')->nullable()->unique()->after('photo_path');
            $table->string('secondary_document')->nullable()->after('nif');
            $table->string('address')->nullable()->after('secondary_phone');
            $table->string('address_number')->nullable()->after('address');
            $table->string('address_complement')->nullable()->after('address_number');
            $table->string('neighborhood')->nullable()->after('address_complement');
            $table->string('postal_code')->nullable()->after('neighborhood');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->default('Portugal')->after('state');
            
            // Remover campos novos
            if (Schema::hasColumn('people', 'uuid')) {
                $table->dropIndex(['uuid']);
                $table->dropColumn('uuid');
            }
            if (Schema::hasColumn('people', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('people', 'nationality')) {
                $table->dropColumn('nationality');
            }
            if (Schema::hasColumn('people', 'birthplace')) {
                $table->dropColumn('birthplace');
            }
            if (Schema::hasColumn('people', 'profession')) {
                $table->dropColumn('profession');
            }
            if (Schema::hasColumn('people', 'occupation')) {
                $table->dropColumn('occupation');
            }
            if (Schema::hasColumn('people', 'whatsapp')) {
                $table->dropColumn('whatsapp');
            }
            if (Schema::hasColumn('people', 'contact_notes')) {
                $table->dropColumn('contact_notes');
            }
            if (Schema::hasColumn('people', 'created_by_user_id')) {
                $table->dropForeign(['created_by_user_id']);
                $table->dropIndex(['created_by_user_id']);
                $table->dropColumn('created_by_user_id');
            }
            if (Schema::hasColumn('people', 'updated_by_user_id')) {
                $table->dropForeign(['updated_by_user_id']);
                $table->dropIndex(['updated_by_user_id']);
                $table->dropColumn('updated_by_user_id');
            }
            if (Schema::hasColumn('people', 'deleted_by_user_id')) {
                $table->dropForeign(['deleted_by_user_id']);
                $table->dropColumn('deleted_by_user_id');
            }
        });
    }
};
