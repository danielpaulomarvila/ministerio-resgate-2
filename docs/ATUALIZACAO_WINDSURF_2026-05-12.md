# Atualização Completa Para Windsurf

Data: 2026-05-12
Projeto: Ministério Resgate / Família Resgate 2.0

Este documento consolida tudo o que foi definido, validado, corrigido e alinhado nesta data para continuidade segura do projeto caso a conversa atual seja interrompida.

## 1. Estado atual do projeto nesta data

Nesta sessão foram corrigidos pontos reais de segurança, bootstrap e consistência do sistema, sem reconstruir módulos inteiros e sem criar arquivos paralelos desnecessários.

Alterações feitas no código:

- Rotas sensíveis da secretaria agora exigem autenticação:
  - `secretaria/alertas`
  - `secretaria/solicitacoes`
  - `secretaria/acessos`
- Controllers da secretaria agora fazem autorização explícita:
  - `SecretaryAlertController`
  - `SecretaryRequestController`
  - `SecretaryUserAccessController`
- Foi criada a policy que faltava:
  - `app/Policies/SecretaryRequestPolicy.php`
- O dashboard da secretaria passou a:
  - validar acesso por perfil `secretaria` ou `super-admin`
  - usar cálculos portáveis de idade por data, sem SQL preso ao MySQL
- O rollback da migration de departamentos foi corrigido:
  - primeiro remove foreign keys
  - depois remove colunas
- O `DatabaseSeeder` passou a chamar os seeders reais:
  - `AccessControlSeeder`
  - `DepartmentSeeder`
  - `CreateAdminUserSeeder`
- O `README.md` principal foi atualizado
- Foi adicionada CI básica:
  - `.github/workflows/ci.yml`
- Foi adicionada cobertura de acesso real:
  - `tests/Feature/SecretaryAccessControlTest.php`

Validação executada nesta sessão:

- `composer test` passando com `44/44`
- `npm run build` passando

Arquivos alterados nesta sessão:

- `routes/web.php`
- `app/Http/Controllers/SecretaryAlertController.php`
- `app/Http/Controllers/SecretaryDashboardController.php`
- `app/Http/Controllers/SecretaryRequestController.php`
- `app/Http/Controllers/SecretaryUserAccessController.php`
- `app/Policies/SecretaryRequestPolicy.php`
- `database/seeders/DatabaseSeeder.php`
- `database/migrations/2026_05_11_103000_alter_departments_table_add_fields.php`
- `tests/Feature/SecretaryAccessControlTest.php`
- `README.md`
- `.github/workflows/ci.yml`

Observação importante:

- O `git status` não está limpo neste momento, porque essas alterações ainda estão no worktree local e não foram commitadas nesta sessão.

## 2. Regra mestra do produto

Foi reafirmado que este sistema não é apenas um CRUD nem apenas um painel administrativo.

Ele é um ecossistema eclesiástico completo para a igreja Ministério Resgate, incluindo:

- área pública
- login
- transição pós-login
- dashboard inicial
- Centro Família
- Secretaria
- Central Pastoral
- pessoas e famílias
- acessos, perfis e permissões
- alertas e solicitações
- departamentos e ministérios
- jovens / Resgatados
- financeiro / tesouraria
- cantina
- louvor / Worship Central
- calendário
- documentos
- orações / rede de fé
- galeria
- obreiros / escalas
- relatórios e auditoria
- configurações

Princípios obrigatórios:

- verdade
- segurança
- organização
- visão de futuro
- código limpo
- visual premium
- respeito à identidade da igreja
- cuidado com pessoas
- escalabilidade para web e futuro app mobile

Regras inegociáveis:

- não criar páginas bonitas sem lógica
- não criar módulos duplicados
- não criar arquivos paralelos
- não quebrar o que já funciona
- corrigir o arquivo real
- não enfraquecer segurança
- não remover auth, policies ou middleware sensíveis
- não reconstruir módulos inteiros sem autorização
- sempre rodar build e testes após alterar código

## 3. Identidade visual oficial

O sistema deve seguir uma identidade visual premium, moderna, espiritual e organizada.

Paleta principal:

- preto
- cinza escuro
- grafite
- fundos profundos
- cards escuros
- bordas discretas
- sombras suaves

Cor principal:

- laranja-dourado

Cores secundárias funcionais:

- azul para informação
- verde para sucesso / confirmado / ativo
- vermelho para erro / risco / pendência grave
- âmbar para atenção / aviso / pendência

Sensação visual desejada:

- premium
- moderno
- limpo
- espiritual discreto
- acolhedor nas áreas da família
- operacional nas áreas administrativas
- estratégico nas áreas pastorais
- sério e confiável no financeiro

Evitar:

- visual infantil
- aparência de template genérico
- páginas poluídas
- cards desalinhados
- textos escapando das caixas
- botões sem padrão
- scroll horizontal global
- excesso de efeito
- aparência amadora

## 4. Regras de desenvolvimento e segurança técnica

Obrigatório:

- não criar arquivos com nomes tipo `Novo`, `Final`, `Corrigido`, `V2`, `Ajustado`, `Old`, `Backup`
- corrigir sempre o arquivo real
- não mexer em backend quando a tarefa for apenas visual
- não apagar backups
- não apagar `.env`
- não apagar `storage`
- não apagar documentos
- não apagar dados reais
- não remover rotas
- não remover policies
- não remover middleware auth
- não fazer commit sem autorização explícita

Comandos obrigatórios após alteração:

- `npm run build`
- `php artisan test`
- `git status`

Quando houver alteração de backend, conferir também:

- `php artisan route:list`
- `php artisan migrate:status`

## 5. Segurança de GitHub, MySQL, backup e .env

Foi reafirmada e validada a política oficial:

### GitHub salva

- código-fonte
- controllers
- models
- policies
- requests
- events
- listeners
- jobs
- migrations
- seeders seguros
- rotas
- páginas Vue
- componentes
- layouts
- testes
- documentação
- workflows

### GitHub não salva

- dados reais do MySQL
- `.env` real
- senhas
- tokens
- chaves
- backups `.sql`
- backups do `.env`
- dados sensíveis da igreja
- dumps
- comprovantes
- anexos privados

### MySQL salva

- pessoas
- famílias
- usuários
- permissões
- solicitações
- alertas
- histórico
- registros reais da operação

Resumo obrigatório:

- GitHub = código
- MySQL = dados reais
- backup `.sql` = cópia dos dados
- backup `.env` = cópia das configurações sensíveis
- iCloud = segunda cópia externa dos backups

### Script de backup

O projeto possui:

- `./scripts/backup-local.sh`

O script já foi conferido nesta sessão e está alinhado com a política:

- lê `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` a partir do `.env`
- cria backup MySQL em `backups/mysql`
- cria backup do `.env` em `backups/env`
- copia ambos para o iCloud
- usa arquivo temporário de credenciais com `chmod 600`
- para se o `mysqldump` falhar
- não aceita `.sql` vazio
- mostra relatório final com nome e tamanho do arquivo

Observação técnica:

- no estado atual, o script exige `DB_PASSWORD` preenchido; se o MySQL local usar senha vazia, o script pode falhar até ser ajustado para esse cenário

### Arquivos conferidos nesta sessão

- `.gitignore` protege:
  - `.env`
  - `backups/mysql/*.sql`
  - `backups/env/*`
- `.env.example` não expõe senha real e mantém:
  - `ADMIN_DEFAULT_PASSWORD=`

### Ritual oficial de segurança

Antes de mudanças grandes:

```bash
git status
./scripts/backup-local.sh
```

Depois de alterar:

```bash
npm run build
php artisan test
git status
```

Se aprovado:

```bash
git add arquivos
git commit -m "mensagem clara"
git push
./scripts/backup-local.sh
git status
```

### Comandos proibidos sem autorização

- `php artisan migrate:fresh`
- `php artisan migrate:fresh --seed`
- `php artisan db:wipe`
- `DROP DATABASE`
- `TRUNCATE`
- `DELETE` destrutivo sem critério

## 6. Públicos e experiência por área

### Secretaria / Administração

Deve ser:

- rápida
- densa
- operacional
- objetiva
- filtrável
- com tabelas fortes
- com ações rápidas
- com status, alertas e histórico

### Pastor / Liderança / Central Pastoral

Deve ser:

- estratégica
- pastoral
- clara
- humana
- com visão de cuidado espiritual
- com indicadores e acompanhamento

### Família / Membros / Congregados

Deve ser:

- simples
- acolhedora
- bonita
- humana
- mobile friendly
- com linguagem curta e afetuosa

### Jovens / Resgatados

Deve ser:

- moderna
- motivadora
- espiritual
- organizada
- com gamificação saudável
- com equipes, desafios, identidade e crescimento

### Tesouraria / Financeiro

Deve ser:

- segura
- precisa
- auditável
- profissional
- com relatórios fortes
- com histórico e anexos

### Cantina

Deve ser:

- rápida
- prática
- simples
- com fluxo fácil de venda
- integrada ao financeiro

## 7. Conclusão sobre sistemas privados pesquisados

Foi pesquisado o padrão de mercado e produto para áreas privadas de igreja.

### Secretaria

Referências úteis:

- Planning Center People
- ChurchTrac
- Vine & Branch
- GraceBase

Conclusão:

- o núcleo deve ser `pessoas + famílias + histórico + follow-up + permissões + operação`
- perfis 360 e filtros fortes são obrigatórios

### Centro Pastoral / Care

Referências úteis:

- CareNote
- Carelog
- Prayerflow
- Church Care Hub

Conclusão:

- a lógica certa é receber, atribuir, acompanhar, fechar
- nada sensível deve depender só de memória ou mensagens dispersas

### Intercessão / Oração

Referências úteis:

- Carelog
- Prayerflow
- SWAPP
- Church Center forms

Conclusão:

- o sistema de oração não deve ser só mural público
- deve combinar acolhimento espiritual + triagem interna + acompanhamento pastoral quando necessário

### Financeiro / Tesouraria

Referências úteis:

- Grain Ledger
- FlockBase
- ChurchTrac

Conclusão:

- o padrão maduro é contabilidade por fundos, relatórios fortes, reconciliação, trilha de auditoria e prestação de contas

### Cantina

Referências úteis:

- SimplyGiv POS
- lógica geral de POS integrado ao financeiro

Conclusão:

- a cantina deve ser rápida para operar e integrada ao caixa, repasse e controle financeiro da igreja

## 8. Intercessão / pedidos de oração

Foi definida a direção conceitual do módulo.

Ele não deve nascer como apenas lista pública de pedidos.

O desenho certo é em camadas:

### Entrada do pedido

- nome opcional ou obrigatório conforme regra futura
- contato opcional
- texto do pedido
- categoria
- opção de solicitar acompanhamento
- nível de privacidade

### Triagem interna

Status recomendados:

- novo
- em oração
- em acompanhamento
- encaminhado ao pastoral
- respondido
- arquivado

### Regras de visibilidade

- público para igreja
- grupo/ministério específico
- apenas intercessão
- apenas pastoral/liderança autorizada

### Camada de acompanhamento

- responsável pelo caso
- prazo de retorno
- histórico interno
- notas privadas
- sinalização de urgência / crise

### Camada espiritual para o usuário

- enviar pedido com acolhimento
- marcar “orei”
- testemunho / resposta quando apropriado

### Regra central

Para o Resgate, o ideal é separar em:

- pedido de oração público / acolhedor
- painel interno da equipe de intercessão
- encaminhamento pastoral para casos sensíveis

## 9. Resgatados / sistema dos jovens

Foi feita verificação do que existe no código e na documentação.

### O que já existe na base

- regra de idade para `junior` e `jovem`
- vínculo com departamento `Resgatados`
- categoria `junior` e `jovem` em `department_people`
- alerta de transição aos 11 anos
- permissões jovens documentadas
- preparação de responsáveis financeiros e regras para menores
- documentação sobre pontuação futura

### O que ainda não existe como módulo real

- dashboard próprio dos Resgatados
- sistema de tesouraria dos jovens
- sistema de pontuação operacional
- sistema das equipes
- identidade visual própria das equipes

### Arquitetura recomendada do módulo Resgatados

Dividir em 3 núcleos:

#### 1. Núcleo Resgatados

- visão geral do grupo
- próximos cultos / eventos
- avisos
- pontuação pessoal
- ranking saudável
- equipe atual
- desafios
- presença
- atalhos para retiro / congresso / tesouraria jovem

#### 2. Núcleo Tesouraria Jovem

Deve existir um mini-financeiro próprio dos jovens, conectado conceitualmente ao financeiro central.

Deve controlar:

- caixa geral dos Resgatados
- entradas
- saídas
- ofertas próprias
- ações para levantar dinheiro
- congressos e retiros
- quem está pagando
- quem está inadimplente
- parcelas
- comprovantes
- histórico
- caixa por evento
- contas a receber dos jovens
- repasses para tesouraria central

Para retiro e eventos:

- valor total por participante
- valor já pago
- valor pendente
- vencimentos
- forma de pagamento
- observações
- responsável financeiro do júnior quando necessário

#### 3. Núcleo Equipes

As equipes `Boanerges` e `Club Resgatados da Fé` não devem ser apenas tags.

Cada uma deve ter:

- identidade própria
- cor principal
- logo
- banner / capa
- líderes
- integrantes
- pontuação coletiva
- mural interno
- desafios
- presença por equipe
- visitantes trazidos
- missões
- metas
- histórico

Ao entrar em uma equipe, a sensação deve ser de entrar no universo daquela equipe.

### Evolução recomendada da pontuação

Não limitar a “jovem assíduo”.

Separar a pontuação em camadas:

#### Constância

- presença em culto dos jovens
- presença em eventos
- streak saudável

#### Crescimento espiritual

- levar Bíblia
- responder estudo
- leitura
- devocional
- participação espiritual

#### Serviço

- servir equipe
- cumprir missão
- responsabilidade

#### Evangelismo e comunhão

- visitante confirmado
- integração
- ações externas

#### Honra e compromisso

- comportamento aprovado com muito critério
- responsabilidade
- fidelidade aos combinados

Saídas de reconhecimento sugeridas:

- caminhada pessoal
- jovem destaque
- equipe destaque
- evangelismo destaque

Regra obrigatória:

- sem ranking tóxico
- sem humilhação
- sem vaidade como centro

### Direção simbólica das equipes

Foi definido que as equipes devem ter alma própria.

Sugestão conceitual:

- `Boanerges`: identidade mais forte, ousada, de impacto, coragem, fogo, missão
- `Club Resgatados da Fé`: identidade mais fraterna, honra, fidelidade, unidade e construção

Isso depois deve aparecer em:

- paleta
- ícone
- logo
- banner
- linguagem
- troféus
- cards
- ambientação visual

## 10. Sistema de relatórios para Secretaria e Tesouraria

Foi definido que o módulo de relatórios precisa ser completo, institucional e exportável.

### Regra geral

Todo relatório importante deve ter:

- logo da igreja no topo
- nome da igreja
- nome do relatório
- subtítulo com período e filtros
- texto institucional adequado àquele relatório
- visualização completa em tela
- exportação para impressão / PDF
- assinatura ao final

### Assinaturas obrigatórias

No final do relatório deve haver dois espaços:

- Pastor Presidente
- Secretário ou Tesoureiro, conforme o módulo

Definição atual:

- Pastor Presidente: `Jeferson Linhares`
- Secretaria: assina o usuário logado que estiver atuando como secretário
- Tesouraria: assina o usuário logado que estiver atuando como tesoureiro

Melhoria recomendada:

- o nome do pastor não deve ficar duro no código para sempre
- deve vir de cadastro/configuração institucional, com `Jeferson Linhares` como padrão inicial

### Comportamento ideal dos botões

O módulo de relatórios deve ter três saídas:

#### Visualizar

- abre o documento completo em tela
- com cabeçalho, filtros, tabelas, texto, totais e assinaturas

#### Imprimir / PDF

- versão paginada e pronta para impressão
- exportável pelo navegador como PDF oficial

#### Exportar dados

- Excel / CSV quando fizer sentido
- separado do PDF institucional

Regra:

- PDF = documento oficial
- Excel / CSV = ferramenta operacional

### Estrutura padrão de todo relatório

- logo
- identificação da igreja
- título
- subtítulo
- texto institucional base
- texto específico do tipo do relatório
- filtros aplicados
- corpo
- resumo / totais
- observações
- assinaturas

### Texto adequado por relatório

Todo relatório institucional deve ter:

- um texto base de emissão
- um texto específico do tipo do relatório

Exemplo de base:

“Relatório emitido pelo sistema Ministério Resgate para fins de controle, conferência e registro institucional.”

Exemplos específicos:

- membros: relação atual de membros cadastrados para fins de organização e conferência
- famílias: composição familiar registrada para acompanhamento e organização interna
- financeiro mensal: movimentação financeira do período para controle, transparência e prestação de contas
- dízimos e ofertas: consolidação das contribuições registradas no período
- pendências: registros que demandam atenção administrativa, conferência ou regularização

### Tipos de relatório necessários da Secretaria

#### Pessoas

- lista geral
- por status
- por faixa etária
- por sexo
- por estado civil
- por bairro / cidade
- cadastro incompleto
- sem telefone
- sem email
- sem documento
- sem família
- com usuário
- sem usuário
- aniversariantes
- novos cadastrados

#### Membros

- membros ativos
- membros inativos
- por batismo
- por tempo de membresia
- por departamento
- por faixa etária
- sem participação recente
- dados incompletos

#### Congregados / visitantes

- congregados ativos
- visitantes recentes
- visitantes por período
- visitantes em acompanhamento
- visitantes sem retorno
- visitantes por quem convidou

#### Famílias

- lista de famílias
- famílias com responsáveis
- famílias sem responsável regular
- famílias com crianças
- famílias com júniores
- famílias com jovens
- famílias com membros
- famílias por região
- composição familiar completa

#### Responsáveis / guardianships

- menores com responsável legal
- menores sem responsável legal
- menores sem responsável financeiro
- júniores supervisionados
- responsáveis autorizados para login
- responsáveis que recebem débitos da cantina
- responsáveis com permissão de visualização financeira

#### Secretaria operacional

- solicitações por status
- solicitações por tipo
- solicitações por período
- solicitações atrasadas
- alertas por prioridade
- alertas em aberto
- alertas resolvidos
- alertas ignorados
- acessos ativos
- acessos suspensos
- usuários por perfil
- usuários por status
- departamentos e participantes
- pessoas por departamento
- líderes por departamento

#### Frequência e acompanhamento

- presença por culto / evento
- faltosos por período
- pessoas sem participação recente
- presença por faixa etária
- presença por departamento
- presença por família
- júniores e jovens com baixa frequência

#### Auditoria

- ações por usuário
- alterações cadastrais por período
- aprovações / reprovações
- criação de acessos
- mudanças de perfil / permissão
- histórico de atividade administrativa

### Tipos de relatório necessários da Tesouraria / Financeiro

#### Resumo financeiro

- diário
- semanal
- mensal
- trimestral
- anual
- comparativo entre períodos

#### Entradas

- por período
- por categoria
- por conta
- por fundo
- por departamento
- por origem
- por usuário lançador

#### Saídas

- por período
- por categoria
- por conta
- por fundo
- por departamento
- por fornecedor
- por usuário lançador

#### Caixa e contas

- saldo por conta
- extrato por conta
- fluxo de caixa
- caixa físico
- caixa para depósito
- depósitos realizados
- depósitos pendentes
- divergências de caixa

#### Dízimos e ofertas

- dízimos por período
- ofertas por período
- contribuições por pessoa
- contribuições por família
- contribuições por fundo
- contribuições por campanha
- contribuintes ativos
- não contribuintes
- comparativo de contribuição

#### Contas a pagar

- em aberto
- vencimentos por período
- vencidos
- pagos no período
- por fornecedor
- por categoria
- por centro de custo

#### Contas a receber

- em aberto
- vencimentos por período
- recebidos no período
- pendências
- por origem
- por pessoa / família
- por evento

#### Fundos, orçamento e centros de custo

- movimentação por fundo
- saldo por fundo
- orçamento previsto x realizado
- por departamento
- por evento
- por centro de custo
- por categoria

#### Eventos, cantina e repasses

- financeiro de retiro / congresso / evento
- participantes pagantes
- inadimplentes
- parcelas em aberto
- arrecadação por ação
- repasses recebidos
- repasses pendentes
- cantina por período
- caixa da cantina
- dívidas da cantina
- créditos da cantina

#### Prestação de contas

- mensal para diretoria
- por evento
- por campanha
- por departamento
- consolidado da igreja

#### Auditoria financeira

- lançamentos por usuário
- alterações em lançamentos
- exclusões / cancelamentos
- comprovantes pendentes
- inconsistências
- divergências entre caixa e sistema

## 11. Centro Família Resgate

Foi reafirmado que o Centro Família deve ser a entrada acolhedora do sistema para quem tem login comum.

Características:

- acolhedor
- humano
- moderno
- menos tabela
- mais cards
- linguagem curta
- experiência tipo app

Papel:

- casa digital da família e dos membros
- não deve substituir a Secretaria
- a Secretaria continua separada

Atalhos permitidos:

- apenas para usuários com permissão

O usuário comum não deve cair direto na Secretaria.

## 12. Regras de idade, membros e jovens

Foi reafirmado:

- membro = somente batizado
- pessoa ≠ usuário
- nem toda pessoa tem usuário
- menores de 11 anos não têm usuário próprio
- menores de 11 ficam vinculados aos pais / responsáveis
- 11 até antes de 14 = júniores
- 14 até antes de 18 = jovens
- ao se aproximar dos 11 anos, o sistema deve alertar a Secretaria para revisar a transição

## 13. Regras futuras de áreas sensíveis

Foi preservada a visão de que áreas internas sensíveis podem futuramente exigir PIN de 4 dígitos, mas isso não deve ser implementado agora.

Regras documentadas:

- PIN por usuário
- armazenamento seguro
- limite de tentativas
- log de tentativas
- uso em áreas internas sensíveis

## 14. Responsividade obrigatória

Todas as telas precisam funcionar em:

- desktop
- notebook menor
- meia tela
- tablet
- mobile quando aplicável

Regras:

- usar `w-full`
- usar `max-w` adequado
- usar `mx-auto`
- usar `px` responsivo
- grids que quebrem bem
- overflow horizontal apenas interno em tabelas
- nunca criar scroll horizontal global

## 15. Ordem de prioridade visual e funcional

Ordem sugerida reafirmada:

1. Secretaria
2. Centro Família
3. Dashboard inicial
4. Pessoas / Famílias
5. Acessos / Alertas / Solicitações
6. Central Pastoral
7. Financeiro / Tesouraria
8. Cantina
9. Jovens / Resgatados
10. Departamentos / Louvor / Calendário / Obreiros

## 16. Regra oficial para trabalho com referências visuais

Método obrigatório quando for usar imagem de referência:

1. pedir imagem
2. analisar
3. identificar arquivo e rota real
4. explicar plano
5. implementar somente a tela autorizada
6. rodar build e testes
7. entregar relatório
8. aguardar aprovação

“Idêntico à imagem” significa:

- máxima fidelidade visual possível
- mesma hierarquia
- mesma sensação
- mesma lógica
- sem copiar de forma burra se quebrar responsividade ou dados reais

## 17. Regra sobre o projeto antigo cancelado

Foi definido que o sistema antigo referenciado pelo usuário:

- não deve ser copiado
- não é a base do novo projeto
- serve como material de estudo
- só deve ser aproveitado no que for realmente bom e maduro
- as camadas antigas ruins, improvisadas ou “lixo” não devem ser trazidas para o novo sistema

Regra:

- criar algo novo
- limpo
- coerente
- sem herdar sujeira técnica

## 18. Forma correta de continuar o projeto

Ao continuar com Windsurf ou outro agente:

- primeiro confirmar o módulo ou tela autorizada
- localizar os arquivos reais
- alterar o mínimo necessário
- não criar abstrações ou camadas paralelas sem necessidade
- validar com build e testes
- entregar relatório claro do que foi feito

## 19. Recomendação prática para o próximo passo

Se o trabalho continuar em outra ferramenta, a melhor sequência é:

1. preservar as correções desta sessão
2. validar se vai commitar ou seguir localmente
3. definir o próximo módulo com prioridade real
4. se for visual, começar por:
   - Secretaria
   - Centro Família
   - Dashboard inicial
5. antes de desenhar jovens, financeiro ou intercessão visualmente, fechar a arquitetura funcional desses módulos

## 20. Resumo final

Tudo definido nesta data aponta para o seguinte:

- o Resgate 2.0 deve ser um ecossistema eclesiástico premium, limpo, seguro e escalável
- a base técnica deve continuar protegida por policies, middleware, logs e testes
- o projeto deve respeitar a política forte de backup, MySQL, GitHub e `.env`
- intercessão deve combinar acolhimento e triagem interna
- Resgatados deve ter módulo próprio, com tesouraria jovem, pontuação justa e equipes com identidade forte
- relatórios de Secretaria e Tesouraria devem ser completos, institucionais, assináveis e exportáveis em PDF
- nenhuma evolução futura deve introduzir lixo técnico, duplicação ou soluções improvisadas
