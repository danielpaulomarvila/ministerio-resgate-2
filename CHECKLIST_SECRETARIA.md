# CHECKLIST_SECRETARIA

## Painel Inicial da Secretaria
**Etapa 4 do Projeto Ministério Resgate / Família Resgate**

Este checklist acompanha a implementação do painel inicial da Secretaria.

---

## Controller

### ✅ SecretaryDashboardController Criado

- [x] Arquivo criado: `app/Http/Controllers/SecretaryDashboardController.php`
- [x] Método `index()` implementado
- [x] Comentários didáticos nos blocos principais
- [x] Busca dados reais do banco usando models existentes:
  - [x] Person
  - [x] Family
  - [x] GuardianShip
  - [x] FamilyMember
- [x] Não usa dados fake
- [x] Não usa seeder
- [x] Não inventa números

### Indicadores Calculados

- [x] Total de pessoas (count de people não deletadas)
- [x] Total de famílias (count de families não deletadas)
- [x] Total de responsáveis ativos (count de guardianships com status active)
- [x] Crianças menores de 11 anos (pessoas com birth_date e idade < 11)
- [x] Júniores (pessoas com idade >= 11 e < 14)
- [x] Jovens (pessoas com idade >= 14 e < 18)
- [x] Adultos (pessoas com idade >= 18)
- [x] Pessoas sem família (pessoas que não possuem vínculo em family_members ativo)
- [x] Menores sem responsável ativo (pessoas menores de 18 anos sem guardianship ativo)
- [x] Crianças próximas dos 11 anos (menores de 11 anos que completarão 11 anos nos próximos 60 dias)
  - [x] Mostra nome, idade atual e data em que completa 11
- [x] Pessoas sem data de nascimento (sem birth_date)
- [x] Pessoas sem telefone principal (sem primary_phone)
- [x] Pessoas sem email e sem telefone
- [x] Pessoas recentemente cadastradas (últimas 5)
- [x] Famílias recentemente criadas (últimas 5)
- [x] Responsabilidades recentes (últimas 5)

### Validação de Dados

- [x] Não quebra se não houver pessoas
- [x] Não quebra se não houver famílias
- [x] Não quebra se não houver responsáveis
- [x] Trata pessoa sem birth_date
- [x] Trata pessoa sem família
- [x] Trata responsible_person_id null
- [x] Trata guardian/minor null
- [x] Trata invited_by_person_id null
- [x] Acesso seguro no PHP (nullsafe operators, verificações)
- [x] Valores padrão e mensagens amigáveis

---

## Rotas

### ✅ Rota Secretaria Criada

- [x] Rota criada: `GET /secretaria`
- [x] Nome da rota: `secretaria.dashboard`
- [x] Controller: `SecretaryDashboardController@index`
- [x] Middleware: `auth` (autenticação obrigatória)
- [x] Arquivo: `routes/web.php`
- [x] Import do SecretaryDashboardController adicionado

---

## Página Vue

### ✅ Dashboard.vue Criado

- [x] Arquivo criado: `resources/js/Pages/Secretaria/Dashboard.vue`
- [x] Usa AuthenticatedLayout
- [x] Props definidas com valores padrão
- [x] Acesso seguro no Vue (verificações de null/undefined)
- [x] Textos em português do Brasil
- [x] Visual limpo, simples e responsivo

### Estrutura da Página

- [x] Cabeçalho: "Painel da Secretaria"
- [x] Subtítulo: "Visão inicial para acompanhar cadastros, famílias e responsáveis"
- [x] Cards principais:
  - [x] Pessoas (com link)
  - [x] Famílias (com link)
  - [x] Responsáveis Ativos (com link)
  - [x] Menores sem Responsável (indicador vermelho se > 0)
- [x] Cards por faixa etária:
  - [x] Crianças menores de 11 (azul)
  - [x] Júniores (verde)
  - [x] Jovens (roxo)
  - [x] Adultos (cinza)
- [x] Atenções da Secretaria:
  - [x] Pessoas sem Família (âmbar)
  - [x] Sem Data de Nascimento (âmbar)
  - [x] Sem Telefone (âmbar)
- [x] Crianças Próximas dos 11 Anos:
  - [x] Lista com nome, idade e data de aniversário
  - [x] Aviso informativo em azul
  - [x] Recomendação para revisão
- [x] Listas rápidas:
  - [x] Pessoas Recentes (últimas 5)
  - [x] Famílias Recentes (últimas 5)
  - [x] Responsabilidades Recentes (últimas 5)
- [x] Atalhos rápidos:
  - [x] Cadastrar Pessoa
  - [x] Ver Pessoas
  - [x] Criar Família
  - [x] Ver Famílias
  - [x] Criar Responsável
  - [x] Ver Responsáveis
- [x] Todos os botões apontam para rotas reais existentes

---

## Menu

### ✅ Link Secretaria Adicionado

- [x] Link adicionado ao menu autenticado (desktop)
- [x] Link adicionado ao menu responsivo (mobile)
- [x] Arquivo: `resources/js/Layouts/AuthenticatedLayout.vue`
- [x] NavLink usa `route('secretaria.dashboard')`
- [x] Active state verifica `route().current('secretaria.dashboard')`
- [x] Não removeu:
  - [x] Dashboard (Breeze)
  - [x] Pessoas
  - [x] Famílias
  - [x] Responsáveis
- [x] Não criou menu duplicado

---

## Regra dos 11 Anos

### ✅ Regra Respeitada e Documentada

- [x] Responsabilidade legal NÃO acaba automaticamente aos 11 anos documentado
- [x] O que muda é a regra do sistema documentado
- [x] Não preencher automaticamente ends_at documentado
- [x] Aviso para revisar cadastro ao completar 11 anos documentado
- [x] Crianças menores de 11 anos documentadas
- [x] Júniores documentados
- [x] Jovens documentados
- [x] Adultos documentados
- [x] Campo invited_by_person_id preservado para futuro evangelismo/pontuação documentado

---

## Campo Quem Indicou / Convidou

### ✅ Campo Preservado

- [x] Campo `invited_by_person_id` existe no cadastro de pessoas
- [x] Não criou pontuação nesta etapa
- [x] Não criou ranking nesta etapa
- [x] Não criou gamificação nesta etapa
- [x] Não criou Membro Destaque nesta etapa
- [x] Documentado como próximo passo futuro para:
  - [x] Frutos de evangelismo
  - [x] Acompanhamento de visitantes
  - [x] Reconhecimento
  - [x] Pontuação
  - [x] Membro Destaque
  - [x] Avaliação dos jovens/Resgatados

---

## Documentação

### ✅ Documentação Criada

- [x] `DOCUMENTO_SECRETARIA.md` criado
- [x] `CHECKLIST_SECRETARIA.md` criado
- [x] Objetivo do painel documentado
- [x] Indicadores documentados
- [x] Regra dos 11 anos documentada
- [x] Diferença entre pessoa, família e responsável documentada
- [x] Campo quem indicou/convidou documentado
- [x] O que não foi implementado nesta etapa documentado

### ✅ Documentação Atualizada (pendente)

- [ ] `DOCUMENTO_BANCO_DADOS_INICIAL.md`
- [ ] `DOCUMENTO_ARQUITETURA_INICIAL.md`
- [ ] `CHECKLIST_INICIAL.md`

---

## Não Implementar Nesta Etapa

### ✅ Não Criado

- [x] Sistema completo de alertas
- [x] Aprovação de cadastro
- [x] Notificações reais
- [x] Gráficos complexos
- [x] Relatórios exportáveis
- [x] Usuário automático aos 11 anos
- [x] Membro automático
- [x] Departamento Resgatados automático
- [x] Pontuação/gamificação de evangelismo
- [x] Ranking de quem indicou pessoas
- [x] Financeiro
- [x] Cantina
- [x] Louvor
- [x] Cadastro online
- [x] Departamentos visual
- [x] Resgatados visual
- [x] App mobile

---

## Validação

### ⏳ Comandos de Validação (pendente)

- [ ] `php artisan optimize:clear`
- [ ] `php artisan route:list --path=secretaria`
- [ ] `php artisan route:list --path=people`
- [ ] `php artisan route:list --path=families`
- [ ] `php artisan route:list --path=guardianships`
- [ ] `php artisan test`
- [ ] `npm run build`
- [ ] `git status`

### ⏳ Teste Manual no Navegador (pendente)

- [ ] Acessar: http://127.0.0.1:8000/secretaria
- [ ] Página abre sem tela branca
- [ ] Cards aparecem
- [ ] Números aparecem corretamente ou zero
- [ ] Atalhos funcionam
- [ ] Pessoas recentes aparecem
- [ ] Famílias recentes aparecem
- [ ] Responsáveis recentes aparecem
- [ ] Crianças próximas dos 11 anos aparecem quando houver
- [ ] Menores sem responsável aparecem quando houver
- [ ] Não há botão quebrado
- [ ] Não há erro no console do navegador

---

## Checkpoints

### ✅ Checkpoint 1 (após 4 alterações)

- [x] git status verificado
- [x] git log --oneline -5 verificado
- [x] Verificação de duplicidade realizada (nenhum arquivo Novo/Final/Corrigido/V2/Backup/Copy encontrado)
- [x] Verificação de módulos fora do escopo realizada (nenhum módulo proibido criado)

---

## Commit

### ⏳ Commit (pendente)

- [ ] `git add .`
- [ ] `git commit -m "feat: criar painel inicial da secretaria"`
