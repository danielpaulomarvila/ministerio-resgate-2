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
        // Tabela de famílias da igreja
        // Uma família pode ter várias pessoas vinculadas
        // Uma família deve ter um responsável principal
        Schema::create('families', function (Blueprint $table) {
            $table->id();

            // Dados da família
            $table->string('name'); // Nome da família (ex: Família Silva)
            $table->foreignId('main_responsible_person_id')->nullable()->constrained('people')->onDelete('set null'); // Responsável principal

            // Contato e endereço
            $table->string('address')->nullable(); // Endereço residencial
            $table->string('phone')->nullable(); // Telefone principal da família

            // Status e observações
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable(); // Observações sobre a família

            $table->timestamps();
            $table->softDeletes(); // Soft delete para preservar histórico

            // Índices
            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
