# DOCUMENTO_SECRETARIA

## Painel Inicial da Secretaria
**Etapa 4 do Projeto Ministério Resgate / Família Resgate**

Este documento descreve o painel inicial da Secretaria para acompanhamento de cadastros, famílias e responsáveis.

---

## Visão Geral

O painel da Secretaria é uma área protegida por autenticação que mostra informações úteis e alertas básicos sobre o sistema. O objetivo é fornecer à Secretaria uma visão rápida do estado atual dos cadastros, permitindo identificar:

- Pessoas sem família vinculada
- Menores sem responsável ativo
- Crianças próximas de completar 11 anos
- Cadastros possivelmente incompletos
- Atividades recentes (pessoas, famílias, responsabilidades)

O painel não cria sistema completo de alertas, notificações reais, gráficos complexos ou relatórios exportáveis nesta etapa. É um painel simples, limpo e útil.

---

## Rota e Acesso

**Rota:** `GET /secretaria`

**Nome da rota:** `secretaria.dashboard`

**Controller:** `SecretaryDashboardController`

**Página Vue:** `resources/js/Pages/Secretaria/Dashboard.vue`

**Middleware:** `auth` (autenticação obrigatória)

**Menu:** Link "Secretaria" adicionado ao menu autenticado (AuthenticatedLayout)

---

## Indicadores do Painel

### Totais

1. **Total de Pessoas**
   - Contagem de pessoas não deletadas
   - Link para ver pessoas

2. **Total de Famílias**
   - Contagem de famílias não deletadas
   - Link para ver famílias

3. **Total de Responsáveis Ativos**
   - Contagem de guardianships com status `active`
   - Link para ver responsáveis

4. **Menores sem Responsável**
   - Contagem de pessoas menores de 18 anos sem guardianship ativo como minor_person_id
   - Indicador em vermelho se > 0, verde se = 0
   - Atenção necessária se houver menores sem responsável

### Faixa Etária

5. **Crianças Menores de 11 Anos**
   - Pessoas com birth_date e idade menor que 11
   - Indicador em azul

6. **Júniores (11-13 anos)**
   - Pessoas com idade >= 11 e < 14
   - Indicador em verde

7. **Jovens (14-17 anos)**
   - Pessoas com idade >= 14 e < 18
   - Indicador em roxo

8. **Adultos (18+ anos)**
   - Pessoas com idade >= 18
   - Indicador em cinza

### Atenções da Secretaria

9. **Pessoas sem Família**
   - Pessoas que não possuem vínculo ativo em family_members
   - Indicador em âmbar se > 0
   - Aviso para vincular às famílias

10. **Sem Data de Nascimento**
    - Pessoas sem birth_date preenchido
    - Indicador em âmbar se > 0
    - Aviso para revisar cadastros

11. **Sem Telefone**
    - Pessoas sem phone preenchido
    - Indicador em âmbar se > 0
    - Aviso para adicionar contato

### Crianças Próximas dos 11 Anos

12. **Crianças que completarão 11 anos nos próximos 60 dias**
    - Menores de 11 anos com data de aniversário nos próximos 60 dias
    - Lista com nome, idade atual e data em que completa 11 anos
    - Aviso informativo em azul
    - Recomendação: "Revise crianças que estão prestes a entrar na fase Júnior. Ao completar 11 anos, a Secretaria deve revisar o cadastro para possível transição com usuário supervisionado."

### Listas Recentes

13. **Pessoas Recentes**
    - Últimas 5 pessoas criadas
    - Mostra nome e data de criação
    - Link para visualizar cada pessoa

14. **Famílias Recentes**
    - Últimas 5 famílias criadas
    - Mostra nome e data de criação
    - Link para visualizar cada família

15. **Responsabilidades Recentes**
    - Últimas 5 guardianships criadas
    - Mostra nome do menor, nome do responsável e data de criação
    - Link para visualizar cada responsabilidade

### Atalhos Rápidos

- Cadastrar Pessoa → `people.create`
- Ver Pessoas → `people.index`
- Criar Família → `families.create`
- Ver Famílias → `families.index`
- Criar Responsável → `guardianships.create`
- Ver Responsáveis → `guardianships.index`

---

## Regra dos 11 Anos

O painel respeita e reforça a regra implementada na Etapa 3:

### Crianças Menores de 11 Anos

- **Não têm usuário próprio**
- **Não são membros** (a menos que sejam batizadas)
- **Ficam vinculadas à família**
- **Tudo passa pelos pais ou responsáveis**
- **Devem ter pelo menos um responsável legal e financeiro ativo**
- **Compras futuras na cantina devem ser cobradas no financeiro dos pais/responsáveis**

**Aviso no painel:**
"Revise crianças que estão prestes a entrar na fase Júnior. Ao completar 11 anos, a Secretaria deve revisar o cadastro para possível transição para Júnior/Resgatados com usuário supervisionado."

### Júniores (11 até antes de 14 anos)

- **Categoria**: Júnior
- **Pode ter usuário** (futuramente, com supervisão dos responsáveis)
- **Precisa de supervisão dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

### Jovens (14 até antes de 18 anos)

- **Categoria**: Jovem
- **Tem usuário** (futuramente)
- **Não precisa de supervisão obrigatória dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

### Adultos (18 anos ou mais)

- **Podem ter usuário**
- **São membros se forem batizados**
- **Não devem ser cadastrados como minor_person_id** em guardianships

**Importante:**
- **Responsabilidade legal NÃO acaba automaticamente aos 11 anos**
- O que muda é a regra do sistema (fase Júnior com possível usuário)
- O vínculo de responsabilidade pode continuar ativo conforme necessário
- Não preencher automaticamente ends_at como fim da responsabilidade legal

---

## Campo Quem Indicou / Convidou

O campo `invited_by_person_id` já existe no cadastro de pessoas e representa:
- Quem convidou
- Quem indicou
- Quem influenciou a pessoa a vir para a igreja

**Nesta etapa:**
- Não criar pontuação
- Não criar ranking
- Não criar gamificação
- Não criar Membro Destaque

**Futuramente, este campo poderá alimentar:**
- Frutos de evangelismo
- Acompanhamento de visitantes
- Reconhecimento
- Pontuação
- Membro Destaque
- Avaliação dos jovens/Resgatados

O painel não mostra este campo nesta etapa, mas ele está preservado no banco para uso futuro.

---

## Validação de Dados

O painel não deve quebrar se:

- Não houver pessoas
- Não houver famílias
- Não houver responsáveis
- Pessoa não tiver birth_date
- Pessoa não tiver família
- responsible_person_id for null
- guardian/minor for null
- invited_by_person_id for null

Todos os acessos no PHP e no Vue usam acesso seguro (nullsafe operators, valores padrão, verificações de existência). Se não houver dados, mostra zero e mensagens amigáveis.

---

## Não Implementar Nesta Etapa

- ❌ Sistema completo de alertas
- ❌ Aprovação de cadastro
- ❌ Notificações reais
- ❌ Gráficos complexos
- ❌ Relatórios exportáveis
- ❌ Usuário automático aos 11 anos
- ❌ Membro automático
- ❌ Departamento Resgatados automático
- ❌ Pontuação/gamificação de evangelismo
- ❌ Ranking de quem indicou pessoas
- ❌ Financeiro
- ❌ Cantina
- ❌ Louvor
- ❌ Cadastro online
- ❌ Departamentos visual
- ❌ Resgatados visual
- ❌ App mobile

---

## Status da Implementação

- ✅ SecretaryDashboardController criado com método index()
- ✅ Rota /secretaria criada com middleware auth
- ✅ Página Vue Dashboard.vue criada com layout responsivo
- ✅ Link Secretaria adicionado ao menu autenticado (desktop e mobile)
- ✅ Indicadores calculados usando models existentes (Person, Family, GuardianShip, FamilyMember)
- ✅ Acesso seguro no PHP e no Vue
- ✅ Valores padrão e mensagens amigáveis para dados vazios
- ✅ Regra dos 11 anos respeitada e documentada
- ✅ Campo invited_by_person_id preservado para futuro evangelismo/pontuação
- ✅ Documentação criada

---

## Próximos Passos Futuros

1. **Sistema de Alertas**
   - Criar alertas automáticos quando crianças completarem 11 anos
   - Criar alertas para menores sem responsável
   - Criar alertas para pessoas sem família
   - Criar sistema de notificações reais

2. **Gráficos e Relatórios**
   - Gráficos de evolução de cadastros
   - Relatórios exportáveis (PDF, Excel)
   - Dashboard mais visual com métricas

3. **Integração com Evangelismo**
   - Mostrar quantidade de pessoas com invited_by_person_id
   - Mostrar últimas pessoas com indicação
   - Futuramente: pontuação e ranking

4. **Integração com Financeiro/Cantina**
   - Quando implementado, mostrar indicadores financeiros
   - Mostrar dívidas da cantina por responsável
   - Mostrar pagamentos pendentes

5. **Integração com Usuários**
   - Quando implementado, mostrar usuários por faixa etária
   - Mostrar usuários ativos/inativos
   - Mostrar usuários pendentes de aprovação
