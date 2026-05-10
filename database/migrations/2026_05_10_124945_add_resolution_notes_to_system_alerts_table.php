<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona campo resolution_notes para guardar observações de resolução
     */
    public function up(): void
    {
        Schema::table('system_alerts', function (Blueprint $table) {
            $table->text('resolution_notes')->nullable()->after('resolved_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_alerts', function (Blueprint $table) {
            $table->dropColumn('resolution_notes');
        });
    }
};
