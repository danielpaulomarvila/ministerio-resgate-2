<?php

namespace Tests\Feature;

use App\Models\Credit;
use App\Models\Family;
use App\Models\FinancialCorrectionRequest;
use App\Models\FinancialTransaction;
use App\Models\GuardianShip;
use App\Models\PaymentProof;
use App\Models\Person;
use App\Models\Receipt;
use App\Models\User;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FamilyFinancialTest extends TestCase
{
    use RefreshDatabase;

    public function test_financial_routes_exist(): void
    {
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.historico'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.recibos'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.comprovantes'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.enviar_comprovante'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.solicitar_correcao'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.solicitacoes'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.cantina'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.eventos'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.filhos'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.pendencias'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.creditos'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.filhos.show'));
        $this->assertTrue(Route::has('familia-resgate.meu_financeiro.recibos.baixar'));
    }

    public function test_guest_is_redirected_from_financial_home(): void
    {
        $this->get('/familia-resgate/meu-financeiro')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_financial_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/familia-resgate/meu-financeiro')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/historico')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/recibos')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/comprovantes')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/enviar-comprovante')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/solicitar-correcao')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/solicitacoes')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/cantina')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/eventos')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/filhos')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/filhos/lucas-marvila')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/pendencias')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/creditos')->assertOk();
        $this->actingAs($user)->get('/familia-resgate/meu-financeiro/recibos/ofertas-maio-2026/baixar')->assertOk();
    }

    public function test_financial_models_can_be_created(): void
    {
        $person = Person::create(['full_name' => 'Daniel Paulo', 'person_status' => 'active']);
        $user = User::factory()->create(['person_id' => $person->id]);
        $family = Family::create(['name' => 'Família Marvila', 'status' => 'active']);

        $transaction = FinancialTransaction::create([
            'person_id' => $person->id,
            'family_id' => $family->id,
            'type' => 'offering',
            'description' => 'Oferta culto de domingo',
            'amount' => 20,
            'status' => 'paid',
        ]);

        PaymentProof::create([
            'financial_transaction_id' => $transaction->id,
            'person_id' => $person->id,
            'uploaded_by' => $user->id,
            'amount' => 20,
            'status' => 'waiting_validation',
        ]);

        Receipt::create([
            'financial_transaction_id' => $transaction->id,
            'person_id' => $person->id,
            'receipt_number' => 'REC-TEST-001',
            'type' => 'offering',
            'amount' => 20,
            'status' => 'issued',
        ]);

        FinancialCorrectionRequest::create([
            'financial_transaction_id' => $transaction->id,
            'person_id' => $person->id,
            'requested_by' => $user->id,
            'reason' => 'Valor incorreto',
            'description' => 'Pedido de revisão para teste.',
            'status' => 'open',
        ]);

        Credit::create([
            'person_id' => $person->id,
            'family_id' => $family->id,
            'origin_transaction_id' => $transaction->id,
            'amount' => 8,
            'remaining_amount' => 8,
            'status' => 'available',
        ]);

        $this->assertDatabaseCount('financial_transactions', 1);
        $this->assertDatabaseCount('payment_proofs', 1);
        $this->assertDatabaseCount('receipts', 1);
        $this->assertDatabaseCount('financial_correction_requests', 1);
        $this->assertDatabaseCount('credits', 1);
    }

    public function test_guardian_can_view_authorized_minor_financial_base(): void
    {
        $guardian = Person::create(['full_name' => 'Responsável', 'person_status' => 'active']);
        $minor = Person::create(['full_name' => 'Filho Menor', 'birth_date' => now()->subYears(10), 'person_status' => 'active']);
        $user = User::factory()->create(['person_id' => $guardian->id]);

        GuardianShip::create([
            'guardian_person_id' => $guardian->id,
            'minor_person_id' => $minor->id,
            'relationship_type' => 'father',
            'is_financial_responsible' => true,
            'can_view_financial' => true,
            'can_receive_canteen_debts' => true,
            'status' => 'active',
        ]);

        $this->assertTrue(app(FinancialAccessService::class)->canViewPersonFinancial($user, $minor));
    }
}
