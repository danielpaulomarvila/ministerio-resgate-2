# CHECKLIST_FAMILIAS

## Módulo de Famílias - Secretaria
**Etapa 2 do Projeto Ministério Resgate / Família Resgate**

Este checklist documenta o processo de implementação do módulo de famílias.

---

## Migrations

### ✅ Migrations Criadas

- [x] `2026_05_09_165337_create_families_table` - Tabela de famílias (existente)
- [x] `2026_05_09_165337_create_family_members_table` - Tabela de vínculos familiares (existente)
- [x] `2026_05_10_104608_adjust_families_and_family_members_structure` - Ajuste de estrutura para Etapa 2

### Ajustes Realizados na Migration de Ajuste

**Tabela `families`**:
- Renomeado `main_responsible_person_id` para `responsible_person_id`
- Adicionado campo `description`
- Removidos campos `address` e `phone` (não usar morada na família nesta etapa)

**Tabela `family_members`**:
- Renomeado `relationship_type` para `role`
- Renomeado `is_main_responsible` para `is_responsible`
- Renomeado `starts_at` para `joined_at`
- Renomeado `ends_at` para `left_at`
- Adicionado campo `notes`
- Atualizado enum de `role` para incluir `relative`

### ✅ Migrations Executadas

- [x] `php artisan migrate` - Sucesso

---

## Models

### ✅ Models Criados/Ajustados

- [x] `app/Models/Family.php` - Ajustado com novos campos e relacionamentos
- [x] `app/Models/FamilyMember.php` - Ajustado com novos campos e relacionamentos

### Ajustes Realizados no Model Family

- Fillable atualizado: `name`, `description`, `responsible_person_id`, `status`, `notes`
- Removidos: `main_responsible_person_id`, `address`, `phone`
- Relacionamento `responsible()` renomeado de `mainResponsible()`
- Relacionamento `members()` atualizado com `withPivot` correto

### Ajustes Realizados no Model FamilyMember

- Fillable atualizado: `family_id`, `person_id`, `role`, `is_responsible`, `joined_at`, `left_at`, `notes`
- Removidos: `relationship_type`, `is_main_responsible`, `starts_at`, `ends_at`
- Casts atualizados: `is_responsible`, `joined_at`, `left_at`

---

## Controllers

### ✅ Controllers Criados

- [x] `app/Http/Controllers/FamilyController.php` - Criado com todos os métodos

### Métodos Implementados

- [x] `index()` - Listar famílias com responsável e contagem de membros
- [x] `create()` - Mostrar formulário de criação
- [x] `store(StoreFamilyRequest)` - Salvar nova família com transação
- [x] `show(Family)` - Mostrar detalhes com membros e faixa etária calculada
- [x] `edit(Family)` - Mostrar formulário de edição
- [x] `update(UpdateFamilyRequest, Family)` - Atualizar família com transação
- [x] `destroy(Family)` - Remover família com soft delete
- [x] `attachPerson(StoreFamilyMemberRequest, Family)` - Vincular pessoa à família
- [x] `updateMember(Request, Family, FamilyMember)` - Atualizar vínculo de membro
- [x] `detachPerson(Family, FamilyMember)` - Desvincular pessoa (soft detach)

### Lógica Especial

- Se responsável principal informado em `store()` ou `update()`, vincula automaticamente como membro se não estiver na família.
- `attachPerson()` verifica duplicidade de pessoa na mesma família ativa.
- `detachPerson()` usa soft detach com `left_at`.
- `show()` calcula faixa etária de cada membro.

---

## Requests

### ✅ Requests Criados

- [x] `app/Http/Requests/StoreFamilyRequest.php` - Validação para criação de família
- [x] `app/Http/Requests/UpdateFamilyRequest.php` - Validação para atualização de família
- [x] `app/Http/Requests/StoreFamilyMemberRequest.php` - Validação para vincular pessoa à família

### Regras de Validação

**StoreFamilyRequest / UpdateFamilyRequest**:
- `name` - required, string, max:255
- `description` - nullable, string
- `responsible_person_id` - nullable, exists:people,id
- `status` - required, in:active,inactive
- `notes` - nullable, string

**StoreFamilyMemberRequest**:
- `person_id` - required, exists:people,id
- `role` - required, in:father,mother,son,daughter,spouse,guardian,relative,other
- `is_responsible` - boolean
- `joined_at` - nullable, date
- `left_at` - nullable, date, after_or_equal:joined_at
- `notes` - nullable, string

---

## Páginas Vue

### ✅ Páginas Criadas

- [x] `resources/js/Pages/Families/Index.vue` - Listagem de famílias
- [x] `resources/js/Pages/Families/Create.vue` - Formulário de criação
- [x] `resources/js/Pages/Families/Edit.vue` - Formulário de edição
- [x] `resources/js/Pages/Families/Show.vue` - Visualização detalhada

### Funcionalidades das Páginas

**Index.vue**:
- Listagem com nome, responsável, quantidade de membros, status
- Links para Visualizar, Editar e Remover
- Formatação de status em português

**Create.vue**:
- Formulário com nome, descrição, responsável principal, status, observações
- Seleção de responsável entre pessoas cadastradas
- Validação de campos obrigatórios

**Edit.vue**:
- Mesmos campos do Create.vue
- Carrega dados existentes da família

**Show.vue**:
- Mostra dados da família
- Formulário para adicionar pessoa à família
- Tabela de membros com nome, papel, idade, faixa etária, responsável familiar
- Ações para remover membro
- Formatação de papel familiar em português
- Cálculo de faixa etária

---

## Navegação

### ✅ Menu Atualizado

- [x] Link "Famílias" adicionado ao menu principal (AuthenticatedLayout.vue)
- [x] Link "Famílias" adicionado ao menu responsivo
- [x] Link "Pessoas" mantido no menu

---

## Rotas

### ✅ Rotas Configuradas

- [x] Import de `FamilyController` adicionado em `routes/web.php`
- [x] Rotas para CRUD de famílias:
  - GET `/families` - families.index
  - GET `/families/create` - families.create
  - POST `/families` - families.store
  - GET `/families/{family}` - families.show
  - GET `/families/{family}/edit` - families.edit
  - PUT `/families/{family}` - families.update
  - DELETE `/families/{family}` - families.destroy
- [x] Rotas para gestão de membros:
  - POST `/families/{family}/attach` - families.attachPerson
  - PUT `/families/{family}/members/{member}` - families.updateMember
  - DELETE `/families/{family}/members/{member}` - families.detachPerson

---

## Documentação

### ✅ Documentação Criada

- [x] `DOCUMENTO_FAMILIAS.md` - Documentação completa do módulo de famílias
- [x] `CHECKLIST_FAMILIAS.md` - Checklist de implementação
- [x] `DOCUMENTO_BANCO_DADOS_INICIAL.md` - Adicionar tabelas families e family_members
- [x] `CHECKLIST_INICIAL.md` - Adicionar referência à Etapa 2

### ✅ Diferença Com Guardianships

- [x] Documentação atualizada explicando diferença entre family_members e guardianships
- [x] Referência adicionada ao `DOCUMENTO_RESPONSAVEIS.md` para detalhes
- [x] `is_responsible` em family_members ≠ responsável legal (guardianships)
- [x] Uma pessoa pode ser mãe no family_members, mas só será responsável legal se definido em guardianships

---

## Observações

### Não Implementar Nesta Etapa

- ❌ Guardianships completo (responsável legal, financeiro, autorizações, etc.)
- ❌ Cadastro online familiar
- ❌ Criação automática de usuário
- ❌ Criação automática de membro
- ❌ Morada na família (morada pertence à pessoa)

### Diferenças Importantes

- `is_responsible` em `family_members` ≠ responsável legal (guardianships)
- Vínculo familiar ≠ responsabilidade legal
- Responsável principal da família ≠ responsável familiar dentro da família

---

## Validação

### ✅ Comandos de Validação (Pendente)

- [ ] `php artisan migrate` - Verificar migrations
- [ ] `php artisan migrate:status` - Verificar status das migrations
- [ ] `php artisan route:list --path=families` - Verificar rotas de famílias
- [ ] `php artisan route:list --path=people` - Verificar rotas de pessoas
- [ ] `php artisan test` - Rodar testes
- [ ] `npm run build` - Build do frontend

---

## Commit

### ✅ Commit (Pendente)

- [ ] `git add .`
- [ ] `git commit -m "feat: criar módulo de famílias e vínculos familiares"`

---

## Observações

### Não Implementar Nesta Etapa

- ❌ Guardianships completo (responsável legal, financeiro, autorizações, etc.)
- ❌ Cadastro online familiar
- ❌ Criação automática de usuário
- ❌ Criação automática de membro
- ❌ Morada na família (morada pertence à pessoa)

### Diferenças Importantes

- `is_responsible` em `family_members` ≠ responsável legal (guardianships)
- Vínculo familiar ≠ responsabilidade legal
- Responsável principal da família ≠ responsável familiar dentro da família

---

## Concluído

- ✅ Migrations criadas e executadas com estrutura ajustada
- ✅ Models criados e ajustados
- ✅ Controller criado com todos os métodos e lógica especial
- ✅ Requests criados com validações
- ✅ Páginas Vue criadas com todas as funcionalidades
- ✅ Menu de navegação atualizado
- ✅ Rotas configuradas
- ✅ Soft delete implementado
- ✅ Soft detach implementado
- ✅ Documentação criada

---

## Próximos Passos

1. Atualizar documentação existente (DOCUMENTO_BANCO_DADOS_INICIAL.md, DOCUMENTO_ARQUITETURA_INICIAL.md, CHECKLIST_INICIAL.md)
2. Rodar comandos de validação
3. Fazer commit das alterações
