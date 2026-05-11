<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona campos necessários para o módulo de Departamentos e Ministérios (Etapa 10)
     */
    public function up(): void
    {
        Schema::table('department_people', function (Blueprint $table) {
            // Renomear role_name para role para manter consistência
            $table->renameColumn('role_name', 'role');
            
            // Flags de liderança e gestão
            $table->boolean('is_leader')->default(false)->after('role');
            $table->boolean('is_assistant')->default(false)->after('is_leader');
            $table->boolean('can_manage_department')->default(false)->after('is_assistant');
            
            // Observações e rastreabilidade
            $table->text('notes')->nullable()->after('can_manage_department');
            $table->foreignId('created_by_user_id')->nullable()->after('notes')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->onDelete('set null');
            
            // Índices
            $table->index('is_leader');
            $table->index('is_assistant');
            $table->index('can_manage_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department_people', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['updated_by_user_id']);
            
            // Drop columns
            $table->dropColumn([
                'is_leader',
                'is_assistant',
                'can_manage_department',
                'notes',
                'created_by_user_id',
                'updated_by_user_id',
            ]);
            
            // Renomear role de volta para role_name
            $table->renameColumn('role', 'role_name');
        });
    }
};
