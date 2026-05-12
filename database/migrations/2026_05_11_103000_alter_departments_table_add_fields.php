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
        Schema::table('departments', function (Blueprint $table) {
            // Tipo de departamento (ministry, administrative, youth, support, financial, worship, children, evangelism, other)
            $table->enum('department_type', ['ministry', 'administrative', 'youth', 'support', 'financial', 'worship', 'children', 'evangelism', 'other'])->nullable()->after('description');

            // Departamento pai (para hierarquia)
            $table->foreignId('parent_department_id')->nullable()->after('department_type')->constrained('departments')->onDelete('set null');

            // Líder e auxiliar
            $table->foreignId('leader_person_id')->nullable()->after('parent_department_id')->constrained('people')->onDelete('set null');
            $table->foreignId('assistant_leader_person_id')->nullable()->after('leader_person_id')->constrained('people')->onDelete('set null');

            // Visualização
            $table->string('color')->nullable()->after('status');
            $table->string('icon')->nullable()->after('color');

            // Reuniões
            $table->string('meeting_day')->nullable()->after('icon');
            $table->time('meeting_time')->nullable()->after('meeting_day');
            $table->string('location')->nullable()->after('meeting_time');

            // Ordenação
            $table->integer('sort_order')->default(0)->after('location');

            // Sistema
            $table->boolean('is_system')->default(false)->after('sort_order');

            // Observações e rastreabilidade
            $table->text('notes')->nullable()->after('is_system');
            $table->foreignId('created_by_user_id')->nullable()->after('notes')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->onDelete('set null');

            // Índices
            $table->index('department_type');
            $table->index('parent_department_id');
            $table->index('leader_person_id');
            $table->index('is_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['parent_department_id']);
            $table->dropForeign(['leader_person_id']);
            $table->dropForeign(['assistant_leader_person_id']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['updated_by_user_id']);

            $table->dropColumn([
                'department_type',
                'parent_department_id',
                'leader_person_id',
                'assistant_leader_person_id',
                'color',
                'icon',
                'meeting_day',
                'meeting_time',
                'location',
                'sort_order',
                'is_system',
                'notes',
                'created_by_user_id',
                'updated_by_user_id',
            ]);
        });
    }
};
