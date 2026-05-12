<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AccessProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para criar/atualizar usuário administrador
 * 
 * Este seeder cria ou atualiza o usuário dannpaulojr@gmail.com
 * e atribui o perfil super-admin
 */
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Buscar senha do .env ou usar senha temporária segura para ambiente local
            // IMPORTANTE: Em produção, sempre defina ADMIN_DEFAULT_PASSWORD no .env
            $defaultPassword = env('ADMIN_DEFAULT_PASSWORD', 'TempAdminPass123!ChangeMe!');
            
            // Buscar ou criar usuário
            $user = User::updateOrCreate(
                ['email' => 'dannpaulojr@gmail.com'],
                [
                    'name' => 'Daniel Paulo',
                    'password' => Hash::make($defaultPassword),
                    'status' => 'active',
                    'must_change_password' => true,
                ]
            );

            // Buscar perfil super-admin
            $superAdminProfile = AccessProfile::where('slug', 'super-admin')->first();

            if ($superAdminProfile) {
                // Sincronizar perfil super-admin ao usuário
                $user->accessProfiles()->sync([$superAdminProfile->id => [
                    'assigned_by_user_id' => $user->id,
                    'assigned_at' => now(),
                    'notes' => 'Acesso super-admin criado automaticamente',
                ]]);

                $this->command->info('✅ Usuário administrador criado/atualizado com sucesso.');
                $this->command->info('📧 Email: dannpaulojr@gmail.com');
                $this->command->info('🔑 Senha: (definida no .env via ADMIN_DEFAULT_PASSWORD)');
                $this->command->info('🛡️ Perfil: Super Administrador');
                
                if (!env('ADMIN_DEFAULT_PASSWORD')) {
                    $this->command->warn('⚠️  Usando senha temporária. Defina ADMIN_DEFAULT_PASSWORD no .env.');
                }
            } else {
                $this->command->error('❌ Perfil super-admin não encontrado. Execute AccessControlSeeder primeiro.');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erro ao criar usuário administrador: ' . $e->getMessage());
            throw $e;
        }
    }
}
