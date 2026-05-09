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
        // Adiciona person_id à tabela users para vincular usuário à pessoa
        // Um usuário pertence a uma pessoa, mas nem toda pessoa tem usuário
        // Menores de 11 anos não podem ter usuário
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('id')->constrained('people')->onDelete('set null');
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at'); // Último login do usuário
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('password'); // Status do usuário

            // Índices
            $table->index('person_id');
            $table->index('status');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove person_id e campos adicionais da tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn(['person_id', 'last_login_at', 'status']);
        });
    }
};
