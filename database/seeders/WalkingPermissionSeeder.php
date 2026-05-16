<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalkingPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Ver caminhada geral', 'slug' => 'walking.view.general', 'description' => 'Permite ver a própria caminhada geral.', 'sort_order' => 1],
            ['name' => 'Ver caminhada jovem', 'slug' => 'walking.view.youth', 'description' => 'Permite ver a própria caminhada jovem quando a pessoa pertence ao contexto jovem/Resgatados.', 'sort_order' => 2],
            ['name' => 'Ver caminhada de filho menor', 'slug' => 'walking.view.family_child', 'description' => 'Permite responsável ver caminhada de filho menor vinculado.', 'sort_order' => 3],
            ['name' => 'Ver dados sensíveis da caminhada', 'slug' => 'walking.view.sensitive', 'description' => 'Permite ver informações sensíveis da caminhada somente em contexto autorizado.', 'sort_order' => 4],
            ['name' => 'Gerir regras da caminhada', 'slug' => 'walking.manage.rules', 'description' => 'Permite gerir regras e catálogo administrativo da caminhada.', 'sort_order' => 5],
            ['name' => 'Validar pontos da caminhada', 'slug' => 'walking.validate.points', 'description' => 'Permite validar pontos pendentes da caminhada.', 'sort_order' => 6],
            ['name' => 'Aprovar destaques da caminhada', 'slug' => 'walking.approve.highlights', 'description' => 'Permite aprovar destaques saudáveis da caminhada.', 'sort_order' => 7],
            ['name' => 'Gerir templates do Mentor', 'slug' => 'walking.manage.mentor_templates', 'description' => 'Permite gerir templates pré-aprovados do Mentor da caminhada.', 'sort_order' => 8],
            ['name' => 'Ver painel de liderança da caminhada', 'slug' => 'walking.view.leadership_dashboard', 'description' => 'Permite visão de liderança/administração sobre a caminhada, respeitando filtros e dados sensíveis.', 'sort_order' => 9],
            ['name' => 'Ver liderança jovem da caminhada', 'slug' => 'walking.view.youth_leadership', 'description' => 'Permite líder jovem ver dados jovens dentro do escopo dos Resgatados.', 'sort_order' => 10],
            ['name' => 'Ver dados pastorais da caminhada', 'slug' => 'walking.view.pastoral', 'description' => 'Permite liderança pastoral ver dados pastorais necessários, com cuidado.', 'sort_order' => 11],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $permission['name'],
                    'group' => 'minha_caminhada',
                    'description' => $permission['description'],
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => $permission['sort_order'],
                ]
            );
        }
    }
}
