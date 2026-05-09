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
        // Tabela para membresia oficial da igreja
        // Só pode existir perfil de membro para pessoa batizada
        // Pessoa não batizada não pode ser membro
        // Adulto não batizado pode ter usuário, mas não member_profile
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();

            // Vinculação com pessoa
            $table->foreignId('person_id')->unique()->constrained('people')->onDelete('cascade');

            // Dados de membresia
            $table->string('membership_number')->unique(); // Número de carteira de membro
            $table->date('membership_date'); // Data de entrada como membro
            $table->enum('membership_status', ['active', 'inactive', 'suspended', 'transferred'])->default('active');

            // Aprovação
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuário que aprovou

            // Observações
            $table->text('notes')->nullable(); // Anotações sobre a membresia

            $table->timestamps();
            $table->softDeletes(); // Soft delete para preservar histórico

            // Índices
            $table->index('membership_number');
            $table->index('membership_status');
            $table->index('membership_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
