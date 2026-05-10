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
        Schema::table('users', function (Blueprint $table) {
            // Campo para indicar se usuário precisa trocar senha no primeiro acesso
            $table->boolean('must_change_password')->default(false)->after('status');
            
            // Timestamp de quando o acesso foi concedido
            $table->timestamp('access_granted_at')->nullable()->after('must_change_password');
            
            // Timestamp de quando o acesso foi revogado
            $table->timestamp('access_revoked_at')->nullable()->after('access_granted_at');
            
            // Motivo da revogação do acesso
            $table->string('access_revoked_reason')->nullable()->after('access_revoked_at');
            
            // Observações sobre o acesso
            $table->text('access_notes')->nullable()->after('access_revoked_reason');
            
            // Índices
            $table->index('must_change_password');
            $table->index('access_granted_at');
            $table->index('access_revoked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['must_change_password']);
            $table->dropIndex(['access_granted_at']);
            $table->dropIndex(['access_revoked_at']);
            $table->dropColumn(['must_change_password', 'access_granted_at', 'access_revoked_at', 'access_revoked_reason', 'access_notes']);
        });
    }
};
