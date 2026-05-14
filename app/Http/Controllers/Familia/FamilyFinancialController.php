<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Services\Familia\FinancialAccessService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FamilyFinancialController extends Controller
{
    public function __construct(private readonly FinancialAccessService $financialAccessService)
    {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('FamiliaResgate/MeuFinanceiro', [
            'financialAccess' => $this->financialAccessPayload($request),
        ]);
    }

    public function area(Request $request, string $area): Response
    {
        return Inertia::render('FamiliaResgate/MeuFinanceiroArea', [
            'area' => $area,
            'financialAccess' => $this->financialAccessPayload($request),
        ]);
    }

    public function childDetail(Request $request, string $person): Response
    {
        return Inertia::render('FamiliaResgate/MeuFinanceiroArea', [
            'area' => 'filho-detalhe',
            'person' => $person,
            'financialAccess' => $this->financialAccessPayload($request),
        ]);
    }

    public function downloadReceipt(Request $request, string $receipt): Response
    {
        return Inertia::render('FamiliaResgate/MeuFinanceiroArea', [
            'area' => 'download-recibo',
            'receipt' => $receipt,
            'financialAccess' => $this->financialAccessPayload($request),
        ]);
    }

    private function financialAccessPayload(Request $request): array
    {
        $user = $request->user();
        $visiblePersonIds = $user ? $this->financialAccessService->visiblePersonIdsFor($user) : collect();

        return [
            'usesRealBase' => true,
            'mockFallback' => true,
            'personId' => $user?->person_id,
            'visiblePersonIds' => $visiblePersonIds->values(),
            'guardianshipsRequired' => true,
        ];
    }
}
