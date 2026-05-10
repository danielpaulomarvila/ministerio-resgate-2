<?php

namespace App\Console\Commands;

use App\Models\AccessProfile;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Comando para conceder acesso de Super Administrador a um usuário
 * 
 * Uso: php artisan access:grant-super-admin {email}
 * Localiza o usuário pelo email e vincula o perfil super-admin
 * Não duplica vínculo se o usuário já tiver o perfil
 */
#[Signature('access:grant-super-admin {email}')]
#[Description('Concede acesso de Super Administrador a um usuário pelo email')]
class GrantSuperAdminAccess extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("🔍 Buscando usuário com email: {$email}");

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuário com email '{$email}' não encontrado.");
            return 1;
        }

        $this->info("✅ Usuário encontrado: {$user->name} (ID: {$user->id})");

        $superAdminProfile = AccessProfile::where('slug', 'super-admin')->first();

        if (!$superAdminProfile) {
            $this->error("❌ Perfil 'super-admin' não encontrado. Execute o seeder AccessControlSeeder primeiro.");
            return 1;
        }

        $this->info("✅ Perfil 'super-admin' encontrado");

        // Verificar se o usuário já tem o perfil
        if ($user->hasAccessProfile('super-admin')) {
            $this->warn("⚠️  O usuário já tem o perfil 'super-admin'. Nenhuma alteração necessária.");
            return 0;
        }

        // Vincular o perfil ao usuário
        try {
            DB::beginTransaction();

            $user->accessProfiles()->attach($superAdminProfile->id, [
                'assigned_by_user_id' => null, // Atribuído pelo sistema via comando
                'assigned_at' => now(),
                'notes' => 'Perfil concedido via comando artisan',
            ]);

            DB::commit();

            $this->info("✅ Perfil 'super-admin' concedido ao usuário {$user->name} com sucesso!");
            $this->info("📧 Email: {$user->email}");
            $this->info("👤 ID: {$user->id}");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erro ao conceder perfil: " . $e->getMessage());
            return 1;
        }
    }
}
