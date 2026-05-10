<?php

namespace Database\Seeders;

use App\Models\AccessProfile;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeder para criar permissões e perfis de acesso iniciais
 * 
 * Este seeder cria a base do sistema de controle de acesso:
 * - Permissões granulares (ex: people.view, accesses.suspend)
 * - Perfis de acesso (ex: Secretaria, Tesouraria, Membro)
 * - Vinculação entre perfis e permissões
 * 
 * Usa updateOrCreate para evitar duplicação ao rodar múltiplas vezes
 */
class AccessControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Criar permissões iniciais agrupadas por categoria
            $this->createPermissions();
            
            // Criar perfis de acesso iniciais
            $this->createProfiles();
            
            // Vincular permissões aos perfis
            $this->assignPermissionsToProfiles();
            
            DB::commit();
            $this->command->info('✅ Permissões e perfis de acesso criados com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erro ao criar permissões e perfis: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cria as permissões iniciais do sistema
     * Cada permissão representa uma ação específica que um usuário pode executar
     */
    protected function createPermissions(): void
    {
        $permissions = [
            // Secretaria
            ['name' => 'Visualizar Secretaria', 'slug' => 'secretaria.view', 'group' => 'secretaria', 'sort_order' => 1],
            
            // Pessoas
            ['name' => 'Visualizar Pessoas', 'slug' => 'people.view', 'group' => 'pessoas', 'sort_order' => 1],
            ['name' => 'Criar Pessoa', 'slug' => 'people.create', 'group' => 'pessoas', 'sort_order' => 2],
            ['name' => 'Editar Pessoa', 'slug' => 'people.update', 'group' => 'pessoas', 'sort_order' => 3],
            ['name' => 'Excluir Pessoa', 'slug' => 'people.delete', 'group' => 'pessoas', 'sort_order' => 4],
            
            // Famílias
            ['name' => 'Visualizar Famílias', 'slug' => 'families.view', 'group' => 'familias', 'sort_order' => 1],
            ['name' => 'Criar Família', 'slug' => 'families.create', 'group' => 'familias', 'sort_order' => 2],
            ['name' => 'Editar Família', 'slug' => 'families.update', 'group' => 'familias', 'sort_order' => 3],
            
            // Responsáveis
            ['name' => 'Visualizar Responsáveis', 'slug' => 'guardianships.view', 'group' => 'responsaveis', 'sort_order' => 1],
            ['name' => 'Gerenciar Responsáveis', 'slug' => 'guardianships.manage', 'group' => 'responsaveis', 'sort_order' => 2],
            
            // Alertas
            ['name' => 'Visualizar Alertas', 'slug' => 'alerts.view', 'group' => 'alertas', 'sort_order' => 1],
            ['name' => 'Gerenciar Alertas', 'slug' => 'alerts.manage', 'group' => 'alertas', 'sort_order' => 2],
            
            // Solicitações
            ['name' => 'Visualizar Solicitações', 'slug' => 'requests.view', 'group' => 'solicitacoes', 'sort_order' => 1],
            ['name' => 'Gerenciar Solicitações', 'slug' => 'requests.manage', 'group' => 'solicitacoes', 'sort_order' => 2],
            
            // Acessos
            ['name' => 'Visualizar Acessos', 'slug' => 'accesses.view', 'group' => 'acessos', 'sort_order' => 1],
            ['name' => 'Criar Acesso', 'slug' => 'accesses.create', 'group' => 'acessos', 'sort_order' => 2],
            ['name' => 'Editar Acesso', 'slug' => 'accesses.update', 'group' => 'acessos', 'sort_order' => 3],
            ['name' => 'Suspender Acesso', 'slug' => 'accesses.suspend', 'group' => 'acessos', 'sort_order' => 4],
            ['name' => 'Reativar Acesso', 'slug' => 'accesses.reactivate', 'group' => 'acessos', 'sort_order' => 5],
            
            // Permissões
            ['name' => 'Visualizar Permissões', 'slug' => 'permissions.view', 'group' => 'sistema', 'sort_order' => 1],
            ['name' => 'Gerenciar Permissões', 'slug' => 'permissions.manage', 'group' => 'sistema', 'sort_order' => 2],
            
            // Área de Membro
            ['name' => 'Visualizar Área de Membro', 'slug' => 'member.area.view', 'group' => 'membros', 'sort_order' => 1],
            
            // Área de Jovem
            ['name' => 'Visualizar Área de Jovem', 'slug' => 'youth.area.view', 'group' => 'jovens', 'sort_order' => 1],
            ['name' => 'Visualizar Área de Jovem (Limitado)', 'slug' => 'youth.area.view_limited', 'group' => 'jovens', 'sort_order' => 2],
            
            // Área Familiar
            ['name' => 'Visualizar Área Familiar', 'slug' => 'family.area.view', 'group' => 'familia', 'sort_order' => 1],
            ['name' => 'Visualizar Filhos', 'slug' => 'family.children.view', 'group' => 'familia', 'sort_order' => 2],
            ['name' => 'Criar Solicitação Familiar', 'slug' => 'family.requests.create', 'group' => 'familia', 'sort_order' => 3],
            
            // Departamentos (futuro)
            ['name' => 'Visualizar Departamentos', 'slug' => 'departments.view', 'group' => 'departamentos', 'sort_order' => 1],
            ['name' => 'Gerenciar Departamentos (Básico)', 'slug' => 'departments.manage_basic', 'group' => 'departamentos', 'sort_order' => 2],
            
            // Financeiro (futuro)
            ['name' => 'Visualizar Financeiro', 'slug' => 'financeiro.view', 'group' => 'financeiro', 'sort_order' => 1],
            ['name' => 'Gerenciar Financeiro', 'slug' => 'financeiro.manage', 'group' => 'financeiro', 'sort_order' => 2],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $permission['name'],
                    'group' => $permission['group'],
                    'description' => null,
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => $permission['sort_order'],
                ]
            );
        }
    }

    /**
     * Cria os perfis de acesso iniciais
     * Cada perfil agrupa um conjunto de permissões para um tipo de usuário
     */
    protected function createProfiles(): void
    {
        $profiles = [
            [
                'name' => 'Super Administrador',
                'slug' => 'super-admin',
                'description' => 'Acesso total ao sistema. Tem todas as permissões.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pastor/Administração',
                'slug' => 'pastor-administracao',
                'description' => 'Acesso administrativo quase total, exceto configurações técnicas sensíveis.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Secretaria',
                'slug' => 'secretaria',
                'description' => 'Pode gerenciar cadastros de pessoas, famílias, responsáveis, alertas, solicitações e acessos.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Tesouraria',
                'slug' => 'tesouraria',
                'description' => 'Acesso visual a cadastros e módulo financeiro (futuro).',
                'sort_order' => 4,
            ],
            [
                'name' => 'Líder de Departamento',
                'slug' => 'lider-departamento',
                'description' => 'Pode visualizar pessoas e gerenciar departamentos de forma básica.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Membro',
                'slug' => 'membro',
                'description' => 'Acesso à área de membros.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Responsável Familiar',
                'slug' => 'responsavel-familiar',
                'description' => 'Pode visualizar área familiar, filhos e criar solicitações.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Jovem',
                'slug' => 'jovem',
                'description' => 'Acesso às áreas de membros e jovens.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Júnior Supervisionado',
                'slug' => 'junior-supervisionado',
                'description' => 'Acesso limitado às áreas de membros e jovens com supervisão.',
                'sort_order' => 9,
            ],
        ];

        foreach ($profiles as $profile) {
            AccessProfile::updateOrCreate(
                ['slug' => $profile['slug']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $profile['name'],
                    'description' => $profile['description'],
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => $profile['sort_order'],
                ]
            );
        }
    }

    /**
     * Vincula permissões aos perfis de acesso
     * Define quais perfis têm acesso a quais permissões
     */
    protected function assignPermissionsToProfiles(): void
    {
        // Super-admin: todas as permissões
        $superAdmin = AccessProfile::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all()->pluck('id');
            $superAdmin->permissions()->sync($allPermissions);
        }

        // Pastor/Administração: quase todas as permissões
        $pastor = AccessProfile::where('slug', 'pastor-administracao')->first();
        if ($pastor) {
            $pastorPermissions = Permission::whereNotIn('slug', [
                'permissions.manage', // Não pode gerenciar permissões
            ])->pluck('id');
            $pastor->permissions()->sync($pastorPermissions);
        }

        // Secretaria: permissões de cadastro e gestão
        $secretaria = AccessProfile::where('slug', 'secretaria')->first();
        if ($secretaria) {
            $secretariaPermissions = Permission::whereIn('slug', [
                'secretaria.view',
                'people.view',
                'people.create',
                'people.update',
                'families.view',
                'families.create',
                'families.update',
                'guardianships.view',
                'guardianships.manage',
                'alerts.view',
                'alerts.manage',
                'requests.view',
                'requests.manage',
                'accesses.view',
                'accesses.create',
                'accesses.update',
                'accesses.suspend',
                'accesses.reactivate',
            ])->pluck('id');
            $secretaria->permissions()->sync($secretariaPermissions);
        }

        // Tesouraria: visualização + financeiro (futuro)
        $tesouraria = AccessProfile::where('slug', 'tesouraria')->first();
        if ($tesouraria) {
            $tesourariaPermissions = Permission::whereIn('slug', [
                'secretaria.view',
                'people.view',
                'families.view',
                'financeiro.view',
                'financeiro.manage',
            ])->pluck('id');
            $tesouraria->permissions()->sync($tesourariaPermissions);
        }

        // Líder de Departamento: visualização de pessoas e departamentos
        $lider = AccessProfile::where('slug', 'lider-departamento')->first();
        if ($lider) {
            $liderPermissions = Permission::whereIn('slug', [
                'people.view',
                'departments.view',
                'departments.manage_basic',
            ])->pluck('id');
            $lider->permissions()->sync($liderPermissions);
        }

        // Membro: área de membros
        $membro = AccessProfile::where('slug', 'membro')->first();
        if ($membro) {
            $membroPermissions = Permission::whereIn('slug', [
                'member.area.view',
            ])->pluck('id');
            $membro->permissions()->sync($membroPermissions);
        }

        // Responsável Familiar: área familiar
        $responsavel = AccessProfile::where('slug', 'responsavel-familiar')->first();
        if ($responsavel) {
            $responsavelPermissions = Permission::whereIn('slug', [
                'family.area.view',
                'family.children.view',
                'family.requests.create',
            ])->pluck('id');
            $responsavel->permissions()->sync($responsavelPermissions);
        }

        // Jovem: área de membros e jovens
        $jovem = AccessProfile::where('slug', 'jovem')->first();
        if ($jovem) {
            $jovemPermissions = Permission::whereIn('slug', [
                'member.area.view',
                'youth.area.view',
            ])->pluck('id');
            $jovem->permissions()->sync($jovemPermissions);
        }

        // Júnior Supervisionado: acesso limitado
        $junior = AccessProfile::where('slug', 'junior-supervisionado')->first();
        if ($junior) {
            $juniorPermissions = Permission::whereIn('slug', [
                'member.area.view',
                'youth.area.view_limited',
            ])->pluck('id');
            $junior->permissions()->sync($juniorPermissions);
        }
    }
}
