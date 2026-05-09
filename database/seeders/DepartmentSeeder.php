<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder genérico para criar departamentos iniciais do sistema
 * Não contém dados reais de pessoas, apenas a estrutura dos departamentos
 */
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de departamentos iniciais da igreja
        $departments = [
            [
                'name' => 'Secretaria',
                'slug' => 'secretaria',
                'description' => 'Departamento responsável pela gestão administrativa, cadastros e aprovações',
                'status' => 'active',
            ],
            [
                'name' => 'Tesouraria',
                'slug' => 'tesouraria',
                'description' => 'Departamento responsável pela gestão financeira, dízimos, ofertas e contas',
                'status' => 'active',
            ],
            [
                'name' => 'Cantina',
                'slug' => 'cantina',
                'description' => 'Departamento responsável pela operação da cantina e vendas',
                'status' => 'active',
            ],
            [
                'name' => 'Louvor',
                'slug' => 'louvor',
                'description' => 'Departamento responsável pelo louvor e adoração (Worship Central)',
                'status' => 'active',
            ],
            [
                'name' => 'Mídia',
                'slug' => 'midia',
                'description' => 'Departamento responsável pela produção de mídia e transmissões',
                'status' => 'active',
            ],
            [
                'name' => 'Resgatados',
                'slug' => 'resgatados',
                'description' => 'Departamento para Júniores (11-13 anos) e Jovens (14-17 anos)',
                'status' => 'active',
            ],
            [
                'name' => 'Intercessão',
                'slug' => 'intercessao',
                'description' => 'Departamento responsável pelo ministério de oração e intercessão',
                'status' => 'active',
            ],
            [
                'name' => 'Recepção',
                'slug' => 'recepcao',
                'description' => 'Departamento responsável pelo atendimento e recepção de visitantes',
                'status' => 'active',
            ],
            [
                'name' => 'Pastoral',
                'slug' => 'pastoral',
                'description' => 'Departamento responsável pelo acompanhamento pastoral e aconselhamento',
                'status' => 'active',
            ],
        ];

        // Cria os departamentos no banco de dados
        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departamentos criados com sucesso!');
    }
}
