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
        Schema::create('walking_journeys', function (Blueprint $table) {
            // Trilhos principais da Minha Caminhada: geral e jovem.
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->enum('type', ['general', 'youth']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_journeys');
    }
};
