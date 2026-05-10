# CHECKLIST_RESPONSAVEIS

## Módulo de Responsáveis Legais e Supervisores - Secretaria
**Etapa 3 do Projeto Ministério Resgate / Família Resgate**

Este checklist documenta o processo de implementação do módulo de responsáveis legais e supervisores.

---

## Migrations

### ✅ Migrations Criadas

- [x] `2026_05_09_165338_create_guardianships_table` - Tabela de responsáveis (existente)
- [x] `2026_05_10_120000_adjust_guardianships_structure` - Ajuste de estrutura para Etapa 3

### Ajustes Realizados na Migration de Ajuste

**Tabela `guardianships`:**
- Adicionado campo `can_authorize_login`
- Adicionado campo `can_receive_canteen_debts`
- Adicionado campo `notes`
- Atualizado enum de `relationship_type` para incluir: grandfather, grandmother, uncle, aunt, brother, sister, tutor
- Atualizado enum de `status` para incluir: ended
- Compatibilidade com SQLite (testes) e MySQL (produção)

### ✅ Migrations Executadas

- [x] `php artisan migrate` - Sucesso

---

## Models

### ✅ Models Criados/Ajustados

- [x] `app/Models/GuardianShip.php` - Ajustado com novos campos e métodos
- [x] `app/Models/Person.php` - Ajustado relacionamento families()

### Ajustes Realizados no Model GuardianShip

- Fillable atualizado: minor_person_id, guardian_person_id, relationship_type, is_legal_guardian, is_financial_responsible, can_approve_changes, can_view_financial, can_authorize_login, can_receive_canteen_debts, starts_at, ends_at, status, notes
- Casts atualizado: can_authorize_login, can_receive_canteen_debts
- Métodos adicionados: canAuthorizeLogin(), canApproveChanges(), canViewFinancial(), canReceiveCanteenDebts()

### Ajustes Realizados no Model Person

- Relacionamento `families()` atualizado com `withPivot` correto (role, is_responsible, joined_at, left_at, notes)
- Relacionamentos `guardianshipsAsMinor()` e `guardianshipsAsGuardian()` já existiam

---

## Controllers

### ✅ Controllers Criados

- [x] `app/Http/Controllers/GuardianshipController.php` - Criado com todos os métodos

### Métodos Implementados

- [x] `index()` - Listar responsabilidades com menor, responsável, relação, permissões e status
- [x] `create()` - Mostrar formulário de criação
- [x] `store(StoreGuardianshipRequest)` - Salvar nova responsabilidade com verificação de vínculo familiar
- [x] `show(Guardianship)` - Mostrar detalhes com avisos por idade
- [x] `edit(Guardianship)` - Mostrar formulário de edição
- [x] `update(UpdateGuardianshipRequest, Guardianship)` - Atualizar responsabilidade
- [x] `destroy(Guardianship)` - Encerrar responsabilidade (marcar como 'ended')

### Lógica Especial

- Ao criar responsabilidade, verifica se responsável está na mesma família do menor
- Se não estiver na mesma família, avisa mas não bloqueia
- Ao encerrar responsabilidade, usa soft delete marcando status como 'ended' com data atual
- Avisos por idade implementados na página Show.vue

---

## Requests

### ✅ Requests Criados

- [x] `app/Http/Requests/StoreGuardianshipRequest.php` - Validação para criação de responsabilidade
- [x] `app/Http/Requests/UpdateGuardianshipRequest.php` - Validação para atualização de responsabilidade

### Regras de Validação

**StoreGuardianshipRequest / UpdateGuardianshipRequest:**
- `minor_person_id` - required, exists:people,id
- `guardian_person_id` - required, exists:people,id, different:minor_person_id
- `relationship_type` - required, in:father,mother,grandfather,grandmother,uncle,aunt,brother,sister,legal_guardian,tutor,other
- `is_legal_guardian` - boolean
- `is_financial_responsible` - boolean
- `can_authorize_login` - boolean
- `can_approve_changes` - boolean
- `can_view_financial` - boolean
- `can_receive_canteen_debts` - boolean
- `starts_at` - nullable, date
- `ends_at` - nullable, date, after_or_equal:starts_at
- `status` - required, in:active,inactive,ended
- `notes` - nullable, string

### Validações Customizadas

- `minor_person_id` deve ser pessoa menor de 18 anos
- `guardian_person_id` não pode ser a mesma pessoa
- `guardian_person_id` deve ser adulto (18+)
- Se menor de 11 anos, deve haver responsável legal e financeiro marcado
- Não permitir duplicidade exata ativa entre mesmo menor, mesmo responsável e mesmo tipo de relação
- Se `can_receive_canteen_debts` for true, `is_financial_responsible` também deve ser true

---

## Páginas Vue

### ✅ Páginas Criadas

- [x] `resources/js/Pages/Guardianships/Index.vue` - Listagem de responsabilidades
- [x] `resources/js/Pages/Guardianships/Create.vue` - Formulário de criação
- [x] `resources/js/Pages/Guardianships/Edit.vue` - Formulário de edição
- [x] `resources/js/Pages/Guardianships/Show.vue` - Visualização detalhada

### Funcionalidades das Páginas

**Index.vue:**
- Listagem com menor (nome, idade, faixa etária), responsável, relação
- Permissões (resp. legal, resp. financeiro, autoriza login)
- Status (Ativo, Inativo, Encerrado)
- Links para Visualizar, Editar e Encerrar

**Create.vue:**
- Formulário com seleção de menor (menos de 18 anos)
- Seleção de responsável adulto (18+)
- Tipo de relação (pai, mãe, avô, avó, tio, tia, irmão, irmã, responsável legal, tutor, outro)
- Permissões/autorizações (checkboxes com descrições)
- Período (data de início, data de fim, status)
- Observações
- Validação de campos obrigatórios

**Edit.vue:**
- Mesmos campos do Create.vue
- Carrega dados existentes da responsabilidade

**Show.vue:**
- Mostra dados do menor (nome, idade, faixa etária)
- Avisos importantes por idade:
  - Menor de 11: Aviso sobre não ter usuário e financeiro vinculado ao responsável
  - Júnior: Informação sobre poder ter usuário futuramente com supervisão
  - Jovem: Informação sobre poder ter usuário futuramente
- Mostra dados do responsável (nome, idade, relação)
- Mostra todas as permissões/autorizações (Sim/Não)
- Mostra período e status
- Mostra observações

---

## Navegação

### ✅ Menu Atualizado

- [x] Link "Responsáveis" adicionado ao menu principal (AuthenticatedLayout.vue)
- [x] Link "Responsáveis" adicionado ao menu responsivo
- [x] Links "Pessoas" e "Famílias" mantidos no menu

---

## Rotas

### ✅ Rotas Configuradas

- [x] Import de `GuardianshipController` adicionado em `routes/web.php`
- [x] Rotas para CRUD de responsabilidades:
  - GET `/guardianships` - guardianships.index
  - GET `/guardianships/create` - guardianships.create
  - POST `/guardianships` - guardianships.store
  - GET `/guardianships/{guardianship}` - guardianships.show
  - GET `/guardianships/{guardianship}/edit` - guardianships.edit
  - PUT `/guardianships/{guardianship}` - guardianships.update
  - DELETE `/guardianships/{guardianship}` - guardianships.destroy

---

## Documentação

### ✅ Documentação Criada

- [x] `DOCUMENTO_RESPONSAVEIS.md` - Documentação completa do módulo de responsáveis
- [x] `CHECKLIST_RESPONSAVEIS.md` - Checklist de implementação

### Documentação a Atualizar (Pendente)

- [ ] `DOCUMENTO_BANCO_DADOS_INICIAL.md` - Adicionar ajustes na tabela guardianships
- [ ] `DOCUMENTO_ARQUITETURA_INICIAL.md` - Adicionar módulo de responsáveis
- [ ] `CHECKLIST_INICIAL.md` - Adicionar referência à Etapa 3
- [ ] `DOCUMENTO_FAMILIAS.md` - Adicionar referência à diferença com guardianships
- [ ] `CHECKLIST_FAMILIAS.md` - Adicionar referência à diferença com guardianships

---

## Validação

### ✅ Comandos de Validação (Pendente)

- [ ] `php artisan migrate` - Verificar migrations
- [ ] `php artisan migrate:status` - Verificar status das migrations
- [ ] `php artisan route:list --path=guardianships` - Verificar rotas de responsáveis
- [ ] `php artisan route:list --path=families` - Verificar rotas de famílias
- [ ] `php artisan route:list --path=people` - Verificar rotas de pessoas
- [ ] `php artisan test` - Rodar testes
- [ ] `npm run build` - Build do frontend
- [ ] `git status` - Verificar alterações

---

## Commit

### ✅ Commit (Pendente)

- [ ] `git add .`
- [ ] `git commit -m "feat: criar módulo de responsáveis e supervisão de menores"`

---

## Observações

### Não Implementar Nesta Etapa

- ❌ Financeiro
- ❌ Cantina
- ❌ Vendas
- ❌ Dashboard completo
- ❌ Cadastro online
- ❌ Departamentos visual
- ❌ Resgatados visual
- ❌ Área do membro
- ❌ Permissões completas com Spatie
- ❌ Utilizador automaticamente
- ❌ Membro automaticamente
- ❌ Pontuação/gamificação de evangelismo
- ❌ Ranking de quem indicou pessoas

### Diferenças Importantes

- `family_members` = vínculo familiar (pai, mãe, filho, filha, cônjuge, familiar, outro)
- `guardianships` = responsabilidade legal e supervisão (responsável legal, financeiro, autoriza login, etc.)
- `is_responsible` em family_members ≠ responsável legal (guardianships)
- Uma pessoa pode ser mãe no family_members, mas só será responsável legal se definido em guardianships

### Campo "Quem Indicou/Convidou"

- Campo `invited_by_person_id` preservado em `people` tabela
- Conceito único: quem convidou/indicou/influenciou a pessoa para a igreja
- Futuramente poderá alimentar relatórios, acompanhamento de evangelismo e pontuação/reconhecimento
- Não implementado nesta etapa, mas preservado/documentado

---

## Concluído

- ✅ Migration criada e executada com estrutura ajustada
- ✅ Models criados e ajustados
- ✅ Controller criado com todos os métodos e lógica especial
- ✅ Requests criados com validações customizadas
- ✅ Páginas Vue criadas com todas as funcionalidades
- ✅ Menu de navegação atualizado
- ✅ Rotas configuradas
- ✅ Soft delete implementado (marcar como 'ended')
- ✅ Documentação criada

---

## Próximos Passos

1. Atualizar documentação existente (DOCUMENTO_BANCO_DADOS_INICIAL.md, DOCUMENTO_ARQUITETURA_INICIAL.md, CHECKLIST_INICIAL.md, DOCUMENTO_FAMILIAS.md, CHECKLIST_FAMILIAS.md)
2. Rodar comandos de validação
3. Fazer commit das alterações
