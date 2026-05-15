# Análise estruturada dos repositórios antigos

## 1. Repositório `rederesgate-cancelado-pesquisa`

Este repositório é a principal referência técnica antiga. Ele já continha uma arquitetura Laravel mais avançada, com vários controllers, rotas, services e models ligados à Família Resgate, jovens, pontuação, secretaria, financeiro, mídia, intercessão, Bíblia, devocional, oportunidades e ecossistema.

### Pontos fortes encontrados

#### 1.1 Família Resgate já tinha muitos módulos previstos

O arquivo de rotas antigo tinha importações e rotas para:

- Família Resgate;
- perfil;
- vida financeira;
- Bíblia;
- devocionais;
- orações;
- grupos;
- ministérios;
- oportunidades;
- eventos;
- escalas;
- cultos;
- fotos e vídeos;
- calendário;
- notificações;
- presença;
- visitantes;
- jovens/resgatados;
- pontuação;
- ranking;
- tesouraria jovem;
- liderança jovem.

Isso mostra que o projeto antigo pensava em ecossistema, não apenas páginas isoladas.

#### 1.2 Pontuação já estava parcialmente estruturada

O projeto antigo tinha:

- `PointRule`;
- `PointLog`;
- `MemberPointEntry`;
- `PointRuleChange`;
- `FamilyPointService`;
- `PointService`;
- `AdminPointRuleController`;
- `YouthGamificationService`.

Essas peças indicam que a lógica de pontos, regras, logs, validação e gamificação já estava bastante avançada.

#### 1.3 Administração de regras já era uma ideia forte

O `AdminPointRuleController` antigo permitia administrar regras com:

- nome;
- chave;
- descrição;
- pontos gerais;
- pontos jovens;
- pontos de equipe jovem;
- tipo de validação;
- origem;
- limites diários, semanais e mensais;
- regra fixa ou editável;
- ativa/inativa;
- conta para ranking mensal;
- conta para ranking geral;
- motivo da alteração.

Essa ideia deve ser reaproveitada no Resgate 2.0, mas com visual e organização melhores.

#### 1.4 Jovens tinham gamificação própria

O `YouthGamificationService` antigo trabalhava com:

- XP;
- níveis;
- streak;
- badges;
- histórico;
- rankings;
- equipes jovens;
- relatórios;
- presença;
- leitura bíblica;
- devocional;
- oração;
- missões;
- eventos.

Isso confirma que jovens/resgatados precisam ter trilho próprio, sem misturar com membros comuns.

## 2. Repositório `sistema-igreja`

Este repositório parece uma versão mais antiga em Next.js, com várias áreas internas e muito uso de `localStorage`.

### Pontos úteis

Ele mostra ideias funcionais importantes:

- área do membro;
- dados pessoais;
- credenciais;
- financeiro pessoal;
- pedidos de alteração;
- família;
- privacidade;
- regras de acesso familiar;
- sessão por perfil;
- páginas internas de família, financeiro, intercessão, pastor, secretaria e membros.

### Problema principal

Muita coisa era baseada em:

- `localStorage`;
- mocks;
- dados locais;
- sessão simulada;
- movimentos financeiros fake.

Isso é útil para entender o conceito, mas não pode ser usado como padrão final.

## 3. Erros antigos que não devemos repetir

### 3.1 Dados fake virando “quase produção”

Erro: deixar mocks/localStorage como se fossem dados reais.

Correção no Resgate 2.0:

- todo mock deve ser temporário;
- toda tela aprovada deve passar por auditoria de mock;
- antes de uso real, tudo precisa vir de backend, props Inertia e banco.

### 3.2 Rotas bonitas, mas desconectadas

Erro: criar páginas ou rotas que só funcionam se digitar URL manualmente.

Correção:

- toda página real precisa ter entrada no fluxo;
- cards, botões e menus devem apontar para rotas reais;
- nada de rota escondida.

### 3.3 Misturar públicos diferentes

Erro: misturar adulto/membro comum, jovem/resgatado, responsável e liderança no mesmo bloco sem separação.

Correção:

- separar Caminhada Geral;
- separar Caminhada Jovem;
- separar Equipes Jovens;
- mostrar cada coisa conforme perfil e permissão.

### 3.4 Ranking espiritual

Erro: transformar pontuação em competição de santidade.

Correção:

- usar “Destaques da Caminhada”;
- reconhecer constância e serviço;
- não expor, não humilhar, não comparar fé.

### 3.5 Administração presa no código

Erro: regras fixas demais, difíceis de ajustar.

Correção:

- Administração Geral deve poder criar/editar/desativar regras;
- regras devem ter limites, validação, jornada e categoria configuráveis.

### 3.6 Sistema crescendo sem documento de conferência

Erro: continuar criando módulo sem voltar ao mapa geral.

Correção:

- cada etapa deve atualizar histórico;
- cada módulo deve passar por checklist de integração;
- manter documentos de referência vivos.
