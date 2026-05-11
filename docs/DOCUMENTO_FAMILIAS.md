# DOCUMENTO_FAMILIAS

## Módulo de Famílias - Secretaria
**Etapa 2 do Projeto Ministério Resgate / Família Resgate**

Este documento descreve o módulo de famílias para a Secretaria do Ministério Resgate.

---

## Visão Geral

O módulo de famílias permite que a Secretaria gerencie grupos familiares dentro da igreja, vinculando pessoas já cadastradas a famílias e definindo papéis familiares.

### Diferença entre Pessoa e Família

- **Pessoa**: Indivíduo cadastrado no sistema com dados pessoais, contatos, documentos e morada.
- **Família**: Grupo de pessoas vinculadas por laços familiares, com um responsável principal e papéis definidos.

### Diferença entre Vínculo Familiar e Responsável Legal

- **Vínculo Familiar (family_members)**: Papel de uma pessoa dentro da família (pai, mãe, filho, filha, cônjuge, etc.). É um vínculo social/relacional.
- **Responsável Legal (guardianships)**: Etapa futura para responsabilidade legal/supervisão de menores. Incluirá responsável legal, responsável financeiro, autorizações específicas, etc.

**Atenção**: O campo `is_responsible` em `family_members` indica que a pessoa é responsável dentro da família, mas NÃO substitui o módulo de guardianships que será implementado futuramente.

---

## Estrutura do Banco de Dados

### Tabela `families`

Armazena as famílias cadastradas no sistema.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | BIGINT (PK) | Identificador único |
| name | VARCHAR(255) | Nome da família (obrigatório) |
| description | TEXT | Descrição da família (opcional) |
| responsible_person_id | BIGINT (FK) | ID da pessoa responsável (opcional, referencia people) |
| status | ENUM('active','inactive') | Status da família (padrão: active) |
| notes | TEXT | Observações gerais (opcional) |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data de atualização |
| deleted_at | TIMESTAMP | Soft delete |

**Regras**:
- `name` é obrigatório.
- `responsible_person_id` é nullable e referencia `people`.
- Uma família pode existir sem responsável principal inicialmente.
- Não guardar morada diretamente na família nesta etapa. Morada pertence à pessoa em `person_addresses`.

### Tabela `family_members`

Armazena os vínculos entre pessoas e famílias.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | BIGINT (PK) | Identificador único |
| family_id | BIGINT (FK) | ID da família (referencia families) |
| person_id | BIGINT (FK) | ID da pessoa (referencia people) |
| role | ENUM | Papel familiar (obrigatório) |
| is_responsible | BOOLEAN | Indica se é responsável familiar (padrão: false) |
| joined_at | DATE | Data de entrada na família (padrão: data atual) |
| left_at | DATE | Data de saída da família (para histórico, opcional) |
| notes | TEXT | Observações sobre o vínculo (opcional) |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data de atualização |

**Papéis familiares (role)**:
- `father` - Pai
- `mother` - Mãe
- `son` - Filho
- `daughter` - Filha
- `spouse` - Cônjuge
- `guardian` - Responsável
- `relative` - Familiar
- `other` - Outro

**Regras**:
- Uma pessoa pode estar vinculada a uma família.
- Uma família pode ter várias pessoas.
- Evitar duplicar a mesma pessoa na mesma família ativa.
- `is_responsible` indica se a pessoa é responsável dentro da família (NÃO substitui guardianships).
- `joined_at` é preenchido automaticamente com a data atual se não informado.
- `left_at` permite histórico futuro.
- Não apagar histórico importante sem necessidade.

---

## Funcionalidades

### CRUD de Famílias

1. **Criar família**: Define nome, descrição, responsável principal, status e observações.
2. **Editar família**: Atualiza dados da família.
3. **Visualizar família**: Mostra dados da família, responsável principal e membros.
4. **Listar famílias**: Mostra todas as famílias com nome, responsável, quantidade de membros e status.
5. **Remover família**: Soft delete para manter histórico.

### Gestão de Membros da Família

1. **Vincular pessoa à família**: Seleciona pessoa já cadastrada, define papel familiar, marca como responsável familiar (opcional), define data de entrada (opcional) e observações.
2. **Atualizar vínculo**: Altera papel familiar, status de responsável ou observações.
3. **Desvincular pessoa da família**: Soft detach com `left_at` para manter histórico.

### Regras de Idade na Família

Ao visualizar membros da família, o sistema calcula e mostra a faixa etária da pessoa:

- **Criança menor de 11 anos**: Não deve ter usuário próprio. Deve estar vinculado a responsáveis.
- **Júnior (11 até antes de 14)**: Futuramente poderá ter usuário com supervisão.
- **Jovem (14 até antes de 18)**: Futuramente poderá ter usuário.
- **Adulto (18 ou mais)**: Pode ter usuário, mas só é membro se for batizado.

**Importante**:
- Não criar usuário automaticamente.
- Não criar membro automaticamente.
- Não criar vínculo com Resgatados automaticamente.

---

## Controllers

### FamilyController

Gerencia as operações CRUD de famílias e gestão de membros.

**Métodos**:
- `index()` - Lista todas as famílias com responsável e contagem de membros.
- `create()` - Mostra formulário para criar nova família.
- `store(StoreFamilyRequest)` - Salva nova família. Se informou responsável, vincula automaticamente como membro.
- `show(Family)` - Mostra detalhes da família, responsável, membros com faixa etária calculada.
- `edit(Family)` - Mostra formulário para editar família.
- `update(UpdateFamilyRequest, Family)` - Atualiza família. Se responsável mudou e não está na família, vincula automaticamente.
- `destroy(Family)` - Remove família com soft delete.
- `attachPerson(StoreFamilyMemberRequest, Family)` - Vincula pessoa à família. Verifica duplicidade.
- `updateMember(Request, Family, FamilyMember)` - Atualiza vínculo de membro.
- `detachPerson(Family, FamilyMember)` - Desvincula pessoa da família (soft detach).

---

## Requests de Validação

### StoreFamilyRequest

Validações para criação de família:
- `name` - required, string, max:255
- `description` - nullable, string
- `responsible_person_id` - nullable, exists:people,id
- `status` - required, in:active,inactive
- `notes` - nullable, string

### UpdateFamilyRequest

Validações para atualização de família (mesmas regras de StoreFamilyRequest).

### StoreFamilyMemberRequest

Validações para vincular pessoa à família:
- `person_id` - required, exists:people,id
- `role` - required, in:father,mother,son,daughter,spouse,guardian,relative,other
- `is_responsible` - boolean
- `joined_at` - nullable, date
- `left_at` - nullable, date, after_or_equal:joined_at
- `notes` - nullable, string

**Regras adicionais**:
- Não permitir a mesma pessoa duplicada na mesma família ativa (verificado no controller).
- Se a pessoa marcada como `responsible_person_id` não estiver na família, o controller adiciona automaticamente como membro responsável (opção mais segura).

---

## Páginas Vue

### Families/Index.vue

Listagem de famílias com:
- Nome
- Responsável principal
- Quantidade de pessoas
- Status
- Ações (Visualizar, Editar, Remover)

### Families/Create.vue

Formulário para criar nova família com:
- Nome da família (obrigatório)
- Descrição
- Responsável principal (seleção de pessoa já cadastrada)
- Status (Ativa/Inativa)
- Observações

### Families/Edit.vue

Formulário para editar família existente (mesmos campos do Create).

### Families/Show.vue

Visualização detalhada da família com:
- Dados da família (nome, descrição, responsável, status, observações)
- Formulário para adicionar pessoa à família (pessoa, papel familiar, responsável familiar, data de entrada, observações)
- Tabela de membros da família com:
  - Nome
  - Papel familiar
  - Idade
  - Faixa etária calculada
  - Responsável familiar (Sim/Não)
  - Ações (Remover)

---

## Navegação

O módulo de famílias foi adicionado ao menu autenticado:
- **Famílias** - Acesso à listagem e gestão de famílias

O link de **Pessoas** foi mantido no menu.

---

## Diferenças Importantes

### Family Members vs Guardianships

**family_members (Vínculo Familiar):**
- Representa o vínculo familiar dentro de uma família
- Papéis: pai, mãe, filho, filha, cônjuge, familiar, outro
- `is_responsible` indica responsável familiar dentro da família
- NÃO substitui guardianships (responsável legal)

**guardianships (Responsabilidade Legal e Supervisão):**
- Representa responsabilidade, supervisão e autorização sobre menor
- Responsável legal, financeiro, supervisor
- Permissões: autoriza login, aprova alterações, ver financeiro, recebe dívidas
- Implementado na Etapa 3

**Importante:**
- Uma pessoa pode ser mãe no family_members, mas só será responsável legal se definido em guardianships
- Os dois módulos são independentes mas complementares
- Para mais detalhes, ver DOCUMENTO_RESPONSAVEIS.md

---

## Observações Importantes

### Não Implementar Nesta Etapa

- **Guardianships completo**: Responsável legal, responsável financeiro, autorização para login de menores, autorização para ver financeiro, autorização para compras da cantina, supervisão de Júniores, períodos de responsabilidade.
- **Cadastro online familiar**: Etapa futura.
- **Criação automática de usuário**: Não criar usuário automaticamente.
- **Criação automática de membro**: Não criar membro automaticamente.

### Futuro

- Etapa futura de guardianships cuidará de responsabilidade legal/supervisão de menores.
- Cônjuge e filhos são vínculos familiares gerenciados nesta etapa.
- Responsável principal da família é diferente de responsável familiar dentro da família.
- Morada da família pode ser decidida futuramente (atualmente, morada pertence à pessoa).

---

## Status da Implementação

- ✅ Migrations criadas e executadas com estrutura ajustada
- ✅ Models criados e ajustados (Family, FamilyMember)
- ✅ Controller criado com todos os métodos (FamilyController)
- ✅ Requests criados com validações (StoreFamilyRequest, UpdateFamilyRequest, StoreFamilyMemberRequest)
- ✅ Páginas Vue criadas (Index, Create, Edit, Show)
- ✅ Menu de navegação atualizado com link Famílias
- ✅ Rotas configuradas no routes/web.php
- ✅ Soft delete implementado para families
- ✅ Soft detach implementado para family_members (left_at)
