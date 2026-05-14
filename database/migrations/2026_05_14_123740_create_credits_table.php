<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();
            $table->foreignId('origin_transaction_id')->nullable()->constrained('financial_transactions')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->decimal('remaining_amount', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->string('status')->default('available');
            $table->string('reason')->nullable();
            $table->date('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['person_id', 'status']);
            $table->index(['family_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
