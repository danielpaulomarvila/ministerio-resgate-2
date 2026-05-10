# DOCUMENTO_RESPONSAVEIS

## Módulo de Responsáveis Legais e Supervisores - Secretaria
**Etapa 3 do Projeto Ministério Resgate / Família Resgate**

Este documento descreve o módulo de responsáveis legais e supervisores para a Secretaria do Ministério Resgate.

---

## Visão Geral

O módulo de responsáveis permite que a Secretaria defina, para uma pessoa menor de idade:

- Responsável legal
- Responsável financeiro
- Supervisor
- Quem pode autorizar login
- Quem pode aprovar alterações cadastrais
- Quem pode ver dados financeiros futuros
- Quem deve receber dívidas futuras da cantina
- Período de responsabilidade
- Observações

Esta etapa é especialmente importante para:

- Crianças menores de 11 anos
- Júniores de 11 até antes de 14 anos
- Jovens menores de 18 anos quando necessário

---

## Diferença Entre Family Members e Guardianships

### family_members (Vínculo Familiar)

Representa o vínculo familiar dentro de uma família.

**Exemplos:**
- Pai
- Mãe
- Filho
- Filha
- Cônjuge
- Familiar
- Outro

**Propósito:**
- Organizar pessoas em grupos familiares
- Definir papel familiar dentro da família
- Marcar responsável familiar (is_responsible)

**Importante:**
- `is_responsible` em family_members indica responsável familiar dentro da família
- NÃO substitui guardianships (responsável legal)

### guardianships (Responsabilidade Legal e Supervisão)

Representa responsabilidade, supervisão e autorização sobre menor.

**Exemplos:**
- Responsável legal
- Responsável financeiro
- Quem autoriza login
- Quem recebe dívidas futuras
- Quem pode aprovar alterações
- Quem pode acompanhar dados do menor

**Propósito:**
- Definir autoridade legal sobre o menor
- Definir responsabilidade financeira
- Definir permissões de acesso e autorização
- Definir supervisão de menores

**Importante:**
- Uma pessoa pode ser mãe no family_members, mas só será responsável legal/financeira se isso estiver definido em guardianships
- Os dois módulos são independentes mas complementares

---

## Estrutura do Banco de Dados

### Tabela `guardianships`

Armazena as responsabilidades sobre menores de idade.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | BIGINT (PK) | Identificador único |
| minor_person_id | BIGINT (FK) | ID da pessoa menor (referencia people) |
| guardian_person_id | BIGINT (FK) | ID do responsável (referencia people) |
| relationship_type | ENUM | Tipo de relação com o menor |
| is_legal_guardian | BOOLEAN | Indica se é responsável legal |
| is_financial_responsible | BOOLEAN | Indica se é responsável financeiro |
| can_authorize_login | BOOLEAN | Pode autorizar login do menor |
| can_approve_changes | BOOLEAN | Pode aprovar alterações no cadastro |
| can_view_financial | BOOLEAN | Pode ver dados financeiros |
| can_receive_canteen_debts | BOOLEAN | Recebe dívidas futuras da cantina |
| starts_at | DATE | Data de início da responsabilidade |
| ends_at | DATE | Data de fim da responsabilidade (nullable, para histórico) |
| status | ENUM('active','inactive','ended') | Status da responsabilidade |
| notes | TEXT | Observações sobre a responsabilidade (opcional) |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data de atualização |

**Tipos de relação (relationship_type):**
- `father` - Pai
- `mother` - Mãe
- `grandfather` - Avô
- `grandmother` - Avó
- `uncle` - Tio
- `aunt` - Tia
- `brother` - Irmão
- `sister` - Irmã
- `legal_guardian` - Responsável legal
- `tutor` - Tutor
- `other` - Outro

**Status:**
- `active` - Ativo
- `inactive` - Inativo
- `ended` - Encerrado

**Regras:**
- `minor_person_id` deve ser pessoa menor de 18 anos.
- `guardian_person_id` deve ser diferente de `minor_person_id`.
- `guardian_person_id` deve ser adulto (18+), preferencialmente.
- Uma criança pode ter mais de um responsável.
- Um responsável pode cuidar de mais de um menor.
- Menor de 11 anos deve ter pelo menos um responsável legal e financeiro ativo.
- Júnior de 11 até antes de 14 deve ter supervisão.
- Jovem de 14 até antes de 18 pode ter responsável, mas não necessariamente precisa da mesma supervisão obrigatória.
- Não apagar histórico importante sem necessidade.

---

## Funcionalidades

### CRUD de Responsabilidades

1. **Criar responsabilidade**: Define menor, responsável, relação, permissões, período e observações.
2. **Editar responsabilidade**: Atualiza dados da responsabilidade.
3. **Visualizar responsabilidade**: Mostra dados do menor, responsável, permissões, status e avisos por idade.
4. **Listar responsabilidades**: Mostra todas as responsabilidades com menor, responsável, relação, permissões e status.
5. **Encerrar responsabilidade**: Marca como 'ended' com data atual (mantém histórico).

### Permissões e Autorizações

**Responsável Legal (`is_legal_guardian`):**
- Autoridade legal sobre o menor
- Pode aprovar alterações no cadastro
- Importante para documentos oficiais

**Responsável Financeiro (`is_financial_responsible`):**
- Paga pelas compras futuras da cantina
- Pode ver dados financeiros do menor
- Recebe cobranças de compras futuras

**Autoriza Login (`can_authorize_login`):**
- Autoriza criação de usuário para Júnior (11-13 anos)
- Menores de 11 anos não podem ter usuário próprio
- Jovens (14-17) podem ter usuário sem necessidade de autorização

**Aprova Alterações (`can_approve_changes`):**
- Pode aprovar mudanças no cadastro do menor
- Importante para menores que não têm usuário próprio

**Ver Financeiro (`can_view_financial`):**
- Acesso a dados financeiros futuros do menor
- Pode ver histórico de compras da cantina

**Recebe Dívidas da Cantina (`can_receive_canteen_debts`):**
- Recebe cobranças de compras futuras da cantina
- Para menores de 11 anos, compras devem ser cobradas do responsável financeiro
- Se este campo for true, `is_financial_responsible` também deve ser true

---

## Regras de Idade

### Importante: Responsabilidade Legal Não Acaba Automaticamente

**A responsabilidade legal/familiar dos pais NÃO acaba automaticamente aos 11 anos.**

O que muda aos 11 anos é a regra do sistema:
- A criança deixa a fase "menor de 11 sem usuário próprio"
- Entra na fase "Júnior com possível usuário supervisionado"

Portanto:
- **Não preencher automaticamente ends_at** como fim da responsabilidade legal aos 11 anos
- Em vez disso, o sistema mostra informações calculadas e avisos
- A Secretaria deve revisar o cadastro quando a criança completar 11 anos
- O vínculo de responsabilidade pode continuar ativo conforme necessário

### Crianças Menores de 11 Anos

- **Não têm usuário próprio**
- **Não são membros** (a menos que sejam batizadas)
- **Ficam vinculadas à família**
- **Tudo passa pelos pais ou responsáveis**
- **Devem ter pelo menos um responsável legal e financeiro ativo**
- **Compras futuras na cantina devem ser cobradas no financeiro dos pais/responsáveis**
- **Podem aparecer no cadastro familiar**
- **Não possuem login próprio**

**Aviso no sistema:**
"Esta criança não pode ter usuário próprio até completar 11 anos. Até lá, ações futuras como cantina e financeiro devem ser vinculadas ao responsável financeiro. Ao completar 11 anos, a Secretaria deve revisar o cadastro para possível transição para Júnior/Resgatados com usuário supervisionado."

### Júniores (11 até antes de 14 anos)

- **Categoria**: Júnior
- **Pode ter usuário** (futuramente, com supervisão dos responsáveis)
- **Precisa de supervisão dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**
- **Responsável pode autorizar login via `can_authorize_login`**
- **Responsabilidade legal dos pais continua conforme necessário**

**Aviso no sistema:**
"Esta pessoa está na fase Júnior. Pode ter usuário futuramente, mas deve continuar com supervisão dos responsáveis."

### Jovens (14 até antes de 18 anos)

- **Categoria**: Jovem
- **Tem usuário** (futuramente)
- **Não precisa de supervisão obrigatória dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**
- **Pode ter responsável, mas a supervisão obrigatória pode ser menor**
- **Responsabilidade legal dos pais pode continuar conforme necessário**

**Aviso no sistema:**
"Esta pessoa está na fase Jovem. Pode ter usuário, mas só será membro se for batizada."

### Adultos (18 anos ou mais)

- **Podem ter usuário**
- **São membros se forem batizados**
- **Não devem ser cadastrados como minor_person_id** em guardianships

---

## Bloco "Período e Regra da Responsabilidade"

Nas páginas de criação, edição e visualização, o bloco "Período de Responsabilidade" foi renomeado para "Período e regra da responsabilidade" e agora mostra:

**Campos exibidos:**
1. **Data de início** - Padrão: data atual
2. **Data de fim** - Opcional. Aviso: "Use apenas se esta responsabilidade tiver uma data real de encerramento."
3. **Idade atual do menor** - Somente leitura, calculada pela birth_date
4. **Fase atual do menor** - Somente leitura (Criança menor de 11 anos, Júnior, Jovem)
5. **Data em que completa 11 anos** - Somente leitura, mostrada apenas se a pessoa tiver menos de 11 anos
6. **Aviso automático por idade** - Baseado na fase atual do menor

**Avisos por idade:**

**Se menor de 11 anos:**
"Esta criança não pode ter usuário próprio até completar 11 anos. Até lá, ações futuras como cantina e financeiro devem ser vinculadas ao responsável financeiro. Ao completar 11 anos, a Secretaria deve revisar o cadastro para possível transição para Júnior/Resgatados com usuário supervisionado."

**Se Júnior:**
"Esta pessoa está na fase Júnior. Pode ter usuário futuramente, mas deve continuar com supervisão dos responsáveis."

**Se Jovem:**
"Esta pessoa está na fase Jovem. Pode ter usuário, mas só será membro se for batizada."

**Dados enviados pelo Controller:**

O GuardianshipController envia para as páginas Vue:
- `id`, `full_name`, `birth_date`, `age`, `age_group_label`
- `turns_11_at` - Data em que a pessoa completa 11 anos (calculada: birth_date + 11 anos)
- `can_have_user` - Boolean: pode ter usuário (idade >= 11)
- `can_be_member` - Boolean: pode ser membro (idade >= 11)

---

---

## Controllers

### GuardianshipController

Gerencia as operações CRUD de responsabilidades sobre menores.

**Métodos:**
- `index()` - Lista todas as responsabilidades com menor, responsável, relação, permissões e status.
- `create()` - Mostra formulário para criar nova responsabilidade.
- `store(StoreGuardianshipRequest)` - Salva nova responsabilidade. Verifica se responsável está na mesma família do menor.
- `show(Guardianship)` - Mostra detalhes da responsabilidade com avisos por idade.
- `edit(Guardianship)` - Mostra formulário para editar responsabilidade.
- `update(UpdateGuardianshipRequest, Guardianship)` - Atualiza responsabilidade.
- `destroy(Guardianship)` - Encerra responsabilidade marcando como 'ended' (mantém histórico).

**Lógica Especial:**
- Ao criar responsabilidade, verifica se o responsável está na mesma família do menor.
- Se não estiver na mesma família, avisa mas não bloqueia.
- Ao encerrar responsabilidade, usa soft delete marcando status como 'ended' com data atual.

---

## Requests de Validação

### StoreGuardianshipRequest

Validações para criação de responsabilidade:
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

**Validações Customizadas:**
- `minor_person_id` deve ser pessoa menor de 18 anos.
- `guardian_person_id` não pode ser a mesma pessoa.
- `guardian_person_id` deve ser adulto (18+), preferencialmente.
- Se `minor_person_id` for menor de 11 anos, deve haver pelo menos um responsável legal e financeiro ativo.
- Não permitir duplicidade exata ativa entre o mesmo menor, mesmo responsável e mesmo tipo de relação.
- Se `can_receive_canteen_debts` for true, `is_financial_responsible` também deve ser true.

### UpdateGuardianshipRequest

Validações para atualização de responsabilidade (mesmas regras de StoreGuardianshipRequest, mas ignorando o próprio registro ao verificar duplicidade).

---

## Páginas Vue

### Guardianships/Index.vue

Listagem de responsabilidades com:
- Nome do menor
- Idade/faixa etária
- Nome do responsável
- Relação
- Responsável legal (Sim/Não)
- Responsável financeiro (Sim/Não)
- Autoriza login (Sim/Não)
- Status
- Ações (Visualizar, Editar, Encerrar)

### Guardianships/Create.vue

Formulário para criar nova responsabilidade com:
- Seleção de pessoa menor (menos de 18 anos)
- Seleção de responsável adulto (18+)
- Tipo de relação
- Permissões/autorizações:
  - Responsável legal
  - Responsável financeiro
  - Pode autorizar login
  - Pode aprovar alterações
  - Pode ver financeiro
  - Recebe dívidas da cantina
- Período (data de início, data de fim, status)
- Observações

### Guardianships/Edit.vue

Formulário para editar responsabilidade existente (mesmos campos do Create).

### Guardianships/Show.vue

Visualização detalhada da responsabilidade com:
- Dados do menor (nome, idade, faixa etária)
- Avisos importantes por idade:
  - Menor de 11: Aviso sobre não ter usuário e financeiro vinculado ao responsável
  - Júnior: Informação sobre poder ter usuário futuramente com supervisão
  - Jovem: Informação sobre poder ter usuário futuramente
- Dados do responsável (nome, idade, relação)
- Permissões/autorizações (todas marcadas como Sim/Não)
- Período e status
- Observações

---

## Navegação

O módulo de responsáveis foi adicionado ao menu autenticado:
- **Responsáveis** - Acesso à listagem e gestão de responsabilidades

Os links de **Pessoas** e **Famílias** foram mantidos no menu.

---

## Integração Com Famílias

Nesta etapa, não é obrigatório que o responsável esteja na mesma família do menor, mas o sistema avisa se não estiver.

**Aviso exibido:**
"Este responsável não está vinculado à mesma família do menor. Verifique se o vínculo familiar também precisa ser criado."

**Comportamento:**
- Aviso informativo, não bloqueante
- Não cria vínculo familiar automaticamente
- Secretaria deve decidir se precisa criar vínculo familiar

---

## Integração Com Cantina e Financeiro Futuros

**Não criar cantina nesta etapa.**
**Não criar financeiro nesta etapa.**

**Documentação futura:**
- `can_receive_canteen_debts` indica quem receberá futuras dívidas da cantina do menor
- `can_view_financial` indica quem poderá ver dados financeiros futuros do menor
- `is_financial_responsible` indica responsável financeiro principal ou autorizado

**Para menores de 11 anos:**
- Compras futuras na cantina devem ser cobradas do responsável financeiro
- Não da criança
- O campo `can_receive_canteen_debts` define quem recebe as cobranças

---

## Integração Com Utilizadores Futuros

**Não criar utilizador nesta etapa.**

**Documentação futura:**
- `can_authorize_login` indica se o responsável pode autorizar criação de utilizador para Júnior
- Menores de 11 anos não podem ter utilizador próprio
- Júniores podem ter utilizador futuramente com supervisão
- Jovens podem ter utilizador futuramente

---

## Alerta dos 11 Anos

**Não criar alerta automático completo nesta etapa.**

**Documentação futura:**
Quando uma criança estiver próxima de completar 11 anos, a Secretaria deve ser alertada para:
- Revisar cadastro
- Confirmar responsáveis
- Verificar se há guardianship ativo
- Preparar possível criação de utilizador
- Definir permissões iniciais
- Manter vínculo familiar

---

## Campo "Quem Indicou/Convidou"

**Não implementar nesta etapa.**

**Preservado no cadastro de pessoas:**
- Campo `invited_by_person_id` em `people` tabela
- Conceito único: quem convidou/indicou/influenciou a pessoa para a igreja
- Futuramente poderá alimentar relatórios, acompanhamento de evangelismo e pontuação/reconhecimento
- Não criar campos separados como "quem convidou", "quem indicou" e "quem influenciou"
- Não criar gamificação agora
- Não criar pontuação agora
- Não criar ranking agora

---

## Não Implementar Nesta Etapa

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

---

## Status da Implementação

- ✅ Migration criada e executada com estrutura ajustada
- ✅ Models criados e ajustados (GuardianShip, Person)
- ✅ Controller criado com todos os métodos (GuardianshipController)
- ✅ Requests criados com validações (StoreGuardianshipRequest, UpdateGuardianshipRequest)
- ✅ Páginas Vue criadas (Index, Create, Edit, Show)
- ✅ Menu de navegação atualizado com link Responsáveis
- ✅ Rotas configuradas no routes/web.php
- ✅ Soft delete implementado (marcar como 'ended' em vez de apagar)
- ✅ Validações customizadas implementadas
- ✅ Avisos por idade implementados nas páginas Vue
