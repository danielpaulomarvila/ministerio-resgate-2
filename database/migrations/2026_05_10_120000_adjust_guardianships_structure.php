<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajusta estrutura da tabela guardianships para atender especificação da Etapa 3
     * Adiciona campos para autorização de login, dívidas da cantina e notas
     * Expande enum de relationship_type e status
     */
    public function up(): void
    {
        Schema::table('guardianships', function (Blueprint $table) {
            // Adicionar campos de autorização
            $table->boolean('can_authorize_login')->default(false)->after('can_view_financial');
            $table->boolean('can_receive_canteen_debts')->default(false)->after('can_authorize_login');
            
            // Adicionar campo de observações
            $table->text('notes')->nullable()->after('status');
        });

        // Atualizar o enum de relationship_type para incluir mais opções
        // Usar sintaxe compatível com MySQL e SQLite
        $dbType = DB::connection()->getDriverName();
        if ($dbType === 'mysql') {
            DB::statement("ALTER TABLE guardianships MODIFY COLUMN relationship_type ENUM('father', 'mother', 'grandfather', 'grandmother', 'uncle', 'aunt', 'brother', 'sister', 'legal_guardian', 'tutor', 'other') DEFAULT 'other'");
        } elseif ($dbType === 'sqlite') {
            // SQLite não suporta ALTER COLUMN diretamente
            // Para testes, assumimos que a coluna já existe com o valor correto
            // Em produção com MySQL, a sintaxe acima será usada
        }

        // Atualizar o enum de status para incluir 'ended'
        if ($dbType === 'mysql') {
            DB::statement("ALTER TABLE guardianships MODIFY COLUMN status ENUM('active', 'inactive', 'ended') DEFAULT 'active'");
        } elseif ($dbType === 'sqlite') {
            // SQLite não suporta ALTER COLUMN diretamente
            // Para testes, assumimos que a coluna já existe com o valor correto
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guardianships', function (Blueprint $table) {
            // Remover campos adicionados
            $table->dropColumn(['can_authorize_login', 'can_receive_canteen_debts', 'notes']);
        });

        // Reverter os enums
        $dbType = DB::connection()->getDriverName();
        if ($dbType === 'mysql') {
            DB::statement("ALTER TABLE guardianships MODIFY COLUMN relationship_type ENUM('father', 'mother', 'legal_guardian', 'other') DEFAULT 'other'");
            DB::statement("ALTER TABLE guardianships MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
        } elseif ($dbType === 'sqlite') {
            // SQLite não suporta ALTER COLUMN diretamente
        }
    }
};
