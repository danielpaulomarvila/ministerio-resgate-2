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
        Schema::create('walking_achievements', function (Blueprint $table) {
            // Catálogo oficial de conquistas, incluindo tipos sensíveis protegidos por policy.
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', [
                'general',
                'youth',
                'administrative',
                'financial',
                'pastoral_sensitive',
            ]);
            $table->string('category');
            $table->enum('visibility', [
                'public_to_user',
                'private_to_user',
                'leadership_only',
                'hidden',
            ]);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->json('criteria')->nullable();
            $table->boolean('requires_validation')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_achievements');
    }
};
