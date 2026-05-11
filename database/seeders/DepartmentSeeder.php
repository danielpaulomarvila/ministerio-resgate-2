<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder para criar departamentos iniciais do sistema (Etapa 10)
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 * - Liderar departamento não cria permissão automaticamente sem controle
 * 
 * Usa updateOrCreate para evitar duplicidade ao rodar múltiplas vezes
 */
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de departamentos raiz/oficiais da igreja (Etapa 10)
        // Todos os departamentos raiz são marcados como is_system = true
        // Departamentos raiz não podem ser excluídos
        $departments = [
            [
                'name' => 'Pastoral',
                'slug' => 'pastoral',
                'description' => 'Departamento responsável pelo pastoreio e cuidado espiritual',
                'department_type' => 'ministry',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Resgatados',
                'slug' => 'resgatados',
                'description' => 'Departamento para pessoas resgatadas e em processo de restauração',
                'department_type' => 'ministry',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Louvor',
                'slug' => 'louvor',
                'description' => 'Departamento responsável pelo louvor e adoração',
                'department_type' => 'worship',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Mídia',
                'slug' => 'midia',
                'description' => 'Departamento responsável pela produção de mídia e transmissões',
                'department_type' => 'support',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Recepção',
                'slug' => 'recepcao',
                'description' => 'Departamento responsável pelo atendimento e recepção de visitantes',
                'department_type' => 'support',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Obreiros',
                'slug' => 'obreiros',
                'description' => 'Departamento para obreiros e líderes da igreja',
                'department_type' => 'ministry',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Jovens / Resgatados',
                'slug' => 'jovens-resgatados',
                'description' => 'Departamento para Júniores (11-13 anos) e Jovens (14-17 anos)',
                'department_type' => 'youth',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Infantil',
                'slug' => 'infantil',
                'description' => 'Departamento para crianças menores de 11 anos',
                'department_type' => 'children',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Tesouraria',
                'slug' => 'tesouraria',
                'description' => 'Departamento responsável pela gestão financeira, dízimos, ofertas e contas',
                'department_type' => 'financial',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Secretaria',
                'slug' => 'secretaria',
                'description' => 'Departamento responsável pela gestão administrativa, cadastros e aprovações',
                'department_type' => 'administrative',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Cantina',
                'slug' => 'cantina',
                'description' => 'Departamento responsável pela operação da cantina e vendas',
                'department_type' => 'support',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Evangelismo',
                'slug' => 'evangelismo',
                'description' => 'Departamento responsável pelo ministério de evangelismo e missões',
                'department_type' => 'evangelism',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Intercessão',
                'slug' => 'intercessao',
                'description' => 'Departamento responsável pelo ministério de oração e intercessão',
                'department_type' => 'ministry',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'Ensino / Discipulado',
                'slug' => 'ensino-discipulado',
                'description' => 'Departamento responsável pelo ensino e discipulado',
                'department_type' => 'ministry',
                'status' => 'active',
                'is_system' => true,
                'sort_order' => 14,
            ],
        ];

        // Cria ou atualiza os departamentos no banco de dados
        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['slug' => $department['slug']],
                $department
            );
        }

        $this->command->info('✅ Departamentos criados/atualizados com sucesso!');
    }
}
