<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajusta estrutura das tabelas families e family_members para atender especificação da Etapa 2
     */
    public function up(): void
    {
        // Ajustes na tabela families
        Schema::table('families', function (Blueprint $table) {
            // Renomear main_responsible_person_id para responsible_person_id
            $table->renameColumn('main_responsible_person_id', 'responsible_person_id');

            // Adicionar description
            $table->text('description')->nullable()->after('name');

            // Remover campos address e phone (não usar morada na família nesta etapa)
            $table->dropColumn(['address', 'phone']);
        });

        // Ajustes na tabela family_members
        Schema::table('family_members', function (Blueprint $table) {
            // Renomear relationship_type para role
            $table->renameColumn('relationship_type', 'role');

            // Renomear is_main_responsible para is_responsible
            $table->renameColumn('is_main_responsible', 'is_responsible');

            // Renomear starts_at para joined_at
            $table->renameColumn('starts_at', 'joined_at');

            // Renomear ends_at para left_at
            $table->renameColumn('ends_at', 'left_at');

            // Adicionar notes
            $table->text('notes')->nullable()->after('left_at');
        });

        // Atualizar o enum de role para incluir 'relative'
        // Usar sintaxe compatível com MySQL e SQLite
        $dbType = DB::connection()->getDriverName();
        if ($dbType === 'mysql') {
            DB::statement("ALTER TABLE family_members MODIFY COLUMN role ENUM('father', 'mother', 'son', 'daughter', 'spouse', 'guardian', 'relative', 'other') DEFAULT 'other'");
        } elseif ($dbType === 'sqlite') {
            // SQLite não suporta ALTER COLUMN diretamente, precisa recriar a tabela
            // Para testes, assumimos que a coluna já existe com o valor correto
            // Em produção com MySQL, a sintaxe acima será usada
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter ajustes na tabela families
        Schema::table('families', function (Blueprint $table) {
            // Renomear de volta
            $table->renameColumn('responsible_person_id', 'main_responsible_person_id');

            // Remover description
            $table->dropColumn('description');

            // Re-adicionar address e phone
            $table->string('address')->nullable()->after('main_responsible_person_id');
            $table->string('phone')->nullable()->after('address');
        });

        // Reverter ajustes na tabela family_members
        Schema::table('family_members', function (Blueprint $table) {
            // Renomear de volta
            $table->renameColumn('role', 'relationship_type');
            $table->renameColumn('is_responsible', 'is_main_responsible');
            $table->renameColumn('joined_at', 'starts_at');
            $table->renameColumn('left_at', 'ends_at');

            // Remover notes
            $table->dropColumn('notes');
        });

        // Reverter o enum de role
        $dbType = DB::connection()->getDriverName();
        if ($dbType === 'mysql') {
            DB::statement("ALTER TABLE family_members MODIFY COLUMN relationship_type ENUM('father', 'mother', 'son', 'daughter', 'spouse', 'guardian', 'other') DEFAULT 'other'");
        } elseif ($dbType === 'sqlite') {
            // SQLite não suporta ALTER COLUMN diretamente
            // Para testes, assumimos que a coluna já existe com o valor correto
        }
    }
};
