<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use App\Models\PersonDocument;
use App\Models\PersonAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller para gerenciar pessoas no sistema
 * Módulo Secretaria - Fase 2.1
 * 
 * Este controller lida com o CRUD básico de pessoas,
 * permitindo que a Secretaria cadastre e gerencie
 * pessoas (crianças, adolescentes, adultos, etc.).
 * 
 * Documentos e moradas foram separados em tabelas próprias
 * para deixar a tabela principal mais limpa e organizada.
 */
class PersonController extends Controller
{
    /**
     * Lista todas as pessoas do sistema
     * 
     * Esta página mostra uma tabela com todas as pessoas cadastradas,
     * incluindo dados principais como nome, idade calculada, status,
     * telemóvel, email, NIF e concelho/município.
     * 
     * @return Response Página Inertia com a lista de pessoas
     */
    public function index(): Response
    {
        // Busca todas as pessoas com soft deletes incluídos (para mostrar inativas também)
        // Carrega documentos e morada principal para mostrar dados adicionais
        // Ordena por nome completo para facilitar a busca
        $people = Person::withTrashed()
            ->with(['document', 'primaryAddress'])
            ->orderBy('full_name')
            ->get()
            ->map(function ($person) {
                // Adiciona idade calculada dinamicamente para cada pessoa
                $person->age = $person->getAgeAttribute();
                return $person;
            });

        return Inertia::render('People/Index', [
            'people' => $people,
        ]);
    }

    /**
     * Mostra o formulário para criar uma nova pessoa
     * 
     * Esta página permite que a Secretaria cadastre uma nova pessoa
     * no sistema. Não cria automaticamente usuário ou perfil de membro,
     * pois isso deve ser feito separadamente conforme as regras de idade
     * e batismo.
     * 
     * @return Response Página Inertia com o formulário de criação
     */
    public function create(): Response
    {
        return Inertia::render('People/Create');
    }

    /**
     * Salva uma nova pessoa no banco de dados
     * 
     * Valida os dados conforme as regras de negócio:
     * - Nome completo obrigatório
     * - Email opcional, mas válido se preenchido
     * - Data de nascimento opcional, mas válida se preenchida
     * - Data de batismo só faz sentido se is_baptized for true
     * - Documentos são salvos em tabela separada
     * - Morada é salva em tabela separada
     * 
     * Usa transação de banco para garantir integridade.
     * 
     * @param StorePersonRequest $request Request validado
     * @return RedirectResponse Redirecionamento para a página de detalhes
     */
    public function store(StorePersonRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Cria a pessoa com os dados validados
            $person = Person::create($request->validated()['person']);
            
            // Cria/atualiza documentos da pessoa
            if (!empty($request->validated()['document'])) {
                PersonDocument::create(array_merge(
                    ['person_id' => $person->id],
                    $request->validated()['document']
                ));
            }
            
            // Cria/atualiza morada principal da pessoa
            if (!empty($request->validated()['address'])) {
                PersonAddress::create(array_merge(
                    ['person_id' => $person->id, 'is_primary' => true],
                    $request->validated()['address']
                ));
            }
            
            DB::commit();
            
            return Redirect::route('people.show', $person->id)
                ->with('success', 'Pessoa cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()
                ->with('error', 'Erro ao cadastrar pessoa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostra os detalhes de uma pessoa específica
     * 
     * Esta página exibe todos os dados da pessoa, incluindo:
     * - Dados principais (nome, telemóvel, email, etc.)
     * - Idade calculada
     * - Situação de batismo
     * - Status
     * - Documentos (NIF, Cartão de Cidadão, etc.)
     * - Morada principal
     * - Observações
     * - Avisos sobre faixa etária (menor de 11, Júnior, Jovem, Adulto)
     * 
     * Também mostra avisos sobre quando a pessoa poderia futuramente
     * ter usuário ou perfil de membro, conforme as regras de idade e batismo.
     * 
     * @param Person $person Pessoa a ser visualizada
     * @return Response Página Inertia com os detalhes da pessoa
     */
    public function show(Person $person): Response
    {
        // Carrega a pessoa com relacionamentos para mostrar vínculos
        $person->load(['user', 'document', 'primaryAddress', 'memberProfile', 'families', 'departments']);

        // Calcula idade dinamicamente
        $person->age = $person->getAgeAttribute();

        // Determina faixa etária para mostrar avisos na tela
        $ageCategory = null;
        if ($person->age !== null) {
            if ($person->age < 11) {
                $ageCategory = 'menor_de_11';
            } elseif ($person->age >= 11 && $person->age < 14) {
                $ageCategory = 'junior';
            } elseif ($person->age >= 14 && $person->age < 18) {
                $ageCategory = 'jovem';
            } else {
                $ageCategory = 'adulto';
            }
        }

        return Inertia::render('People/Show', [
            'person' => $person,
            'ageCategory' => $ageCategory,
        ]);
    }

    /**
     * Mostra o formulário para editar uma pessoa existente
     * 
     * Esta página permite que a Secretaria atualize os dados
     * de uma pessoa já cadastrada. Mantém a integridade dos
     * dados e respeita as regras de validação.
     * 
     * @param Person $person Pessoa a ser editada
     * @return Response Página Inertia com o formulário de edição
     */
    public function edit(Person $person): Response
    {
        // Carrega documentos e morada principal para edição
        $person->load(['document', 'primaryAddress']);

        return Inertia::render('People/Edit', [
            'person' => $person,
        ]);
    }

    /**
     * Atualiza uma pessoa existente no banco de dados
     * 
     * Valida os dados conforme as regras de negócio, semelhante
     * à criação, mas permitindo edição de campos existentes.
     * 
     * Usa transação de banco para garantir integridade.
     * 
     * @param UpdatePersonRequest $request Request validado
     * @param Person $person Pessoa a ser atualizada
     * @return RedirectResponse Redirecionamento para a página de detalhes
     */
    public function update(UpdatePersonRequest $request, Person $person): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Atualiza a pessoa com os dados validados
            $person->update($request->validated()['person']);
            
            // Atualiza documentos da pessoa
            if (!empty($request->validated()['document'])) {
                $documentData = array_merge(['person_id' => $person->id], $request->validated()['document']);
                
                if ($person->document) {
                    $person->document->update($documentData);
                } else {
                    PersonDocument::create($documentData);
                }
            }
            
            // Atualiza morada principal da pessoa
            if (!empty($request->validated()['address'])) {
                $addressData = array_merge(['person_id' => $person->id, 'is_primary' => true], $request->validated()['address']);
                
                if ($person->primaryAddress) {
                    $person->primaryAddress->update($addressData);
                } else {
                    PersonAddress::create($addressData);
                }
            }
            
            DB::commit();
            
            return Redirect::route('people.show', $person->id)
                ->with('success', 'Pessoa atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()
                ->with('error', 'Erro ao atualizar pessoa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove uma pessoa do sistema (soft delete)
     * 
     * Usa soft delete para manter o histórico da pessoa no banco.
     * A pessoa não é excluída permanentemente, apenas marcada como
     * deletada. Isso permite recuperação futura se necessário.
     * 
     * Soft delete é importante para manter integridade de dados
     * e auditoria, pois a pessoa pode ter histórico financeiro,
     * registros de cantina, escalas, etc.
     * 
     * @param Person $person Pessoa a ser removida
     * @return RedirectResponse Redirecionamento para a lista de pessoas
     */
    public function destroy(Person $person): RedirectResponse
    {
        // Realiza soft delete da pessoa
        $person->delete();

        // Redireciona para a lista de pessoas com mensagem de sucesso
        return Redirect::route('people.index')
            ->with('success', 'Pessoa removida com sucesso!');
    }

    /**
     * Busca pessoas por nome para autocomplete
     * 
     * @param Request $request Request com parâmetro 'q' para busca
     * @return JsonResponse JSON com resultados da busca
     */
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $people = Person::query()
            ->where(function ($query) use ($term) {
                $query->where('full_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('primary_phone', 'like', "%{$term}%");
            })
            ->whereNull('deleted_at')
            ->orderBy('full_name')
            ->limit(10)
            ->get(['id', 'full_name', 'email', 'primary_phone']);
        
        return response()->json($people);
    }
}
