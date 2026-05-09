<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar a tabela person_addresses
 * 
 * Esta tabela separa as moradas das pessoas da tabela principal people,
 * permitindo que a tabela de pessoas fique mais limpa e organizada.
 * 
 * As moradas foram separadas para:
 * - Evitar que a tabela people fique inchada com muitos campos
 * - Permitir que uma pessoa tenha múltiplas moradas futuramente
 * - Facilitar a expansão para novos campos de endereço
 * - Melhorar a organização do banco de dados
 * 
 * Contexto: Sistema para uso em Portugal
 * - Estrutura portuguesa de morada: Distrito, Concelho, Freguesia, Localidade
 * - Código postal português no formato 0000-000
 * - Futuramente poderemos normalizar com tabelas oficiais ou dados externos
 */
return new class extends Migration
{
    /**
     * Executa a migration criando a tabela person_addresses
     */
    public function up(): void
    {
        Schema::create('person_addresses', function (Blueprint $table) {
            $table->id();
            
            // Foreign key para people
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            
            // Localização geográfica (estrutura portuguesa)
            $table->string('country_name')->nullable(); // Nome do país (texto, futuramente pode ser tabela)
            $table->string('district_name')->nullable(); // Nome do distrito (texto, futuramente pode ser tabela)
            $table->string('municipality_name')->nullable(); // Nome do concelho/município (texto, futuramente pode ser tabela)
            $table->string('parish_name')->nullable(); // Nome da freguesia (texto, futuramente pode ser tabela)
            $table->string('locality_name')->nullable(); // Nome da localidade (texto, futuramente pode ser tabela)
            $table->string('locality_manual')->nullable(); // Localidade manual para casos especiais
            
            // Endereço detalhado
            $table->string('address_line')->nullable(); // Rua/Avenida/Travessa
            $table->string('door_number')->nullable(); // Número da porta
            $table->string('floor_or_unit')->nullable(); // Andar/Fração
            $table->string('address_complement')->nullable(); // Complemento do endereço
            
            // Código postal e endereço completo
            $table->string('postal_code')->nullable(); // Código postal no formato 0000-000
            $table->string('full_address')->nullable(); // Endereço completo concatenado
            
            // Indicador de morada principal
            $table->boolean('is_primary')->default(false); // Indica se é a morada principal
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('person_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverte a migration removendo a tabela person_addresses
     */
    public function down(): void
    {
        Schema::dropIfExists('person_addresses');
    }
};
