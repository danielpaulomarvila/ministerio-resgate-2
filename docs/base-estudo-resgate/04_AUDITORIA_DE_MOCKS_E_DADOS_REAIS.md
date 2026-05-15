# Auditoria de Mocks e Preparação para Dados Reais

## 1. Objetivo

Antes de entregar qualquer página para membros usarem, remover dependência de mocks, dados fake ou localStorage usado como banco.

## 2. Regra oficial

Tela aprovada visualmente pode usar mock temporário.

Tela entregue para uso real não pode depender de mock.

## 3. Módulos que precisam passar por auditoria

- Central da Família;
- Meu Perfil;
- Meu Financeiro;
- Minha Caminhada;
- Conquistas;
- Histórico;
- Mentor;
- Regras;
- Destaques;
- Intercessão;
- Secretaria;
- Centro Financeiro;
- Centro Pastoral;
- Cantina;
- Administração Geral.

## 4. O que mapear em cada mock

Para cada mock, registrar:

- arquivo;
- componente;
- nome da variável;
- dados fake atuais;
- tela onde aparece;
- dado real esperado;
- tabela/model/service esperado;
- prop Inertia esperada;
- fallback seguro;
- quem pode ver;
- quem pode editar/validar;
- risco se continuar fake;
- prioridade.

## 5. Exemplos

### Meu Perfil

Mock/fake:

- nome;
- dados pessoais;
- família;
- ministério;
- credenciais;
- pessoa que convidou;
- foto;
- pedidos.

Origem real esperada:

- `people`;
- `users`;
- `member_profiles`;
- `families`;
- `family_members`;
- `guardianships`;
- `departments`;
- `department_people`;
- `profile_change_requests`.

### Meu Financeiro

Mock/fake:

- créditos;
- dívidas;
- saldo;
- cantina;
- pagamentos;
- recibos;
- filhos.

Origem real esperada:

- lançamentos financeiros;
- cantina;
- pagamentos;
- recibos;
- inscrições;
- responsáveis/família;
- regras de menor de idade.

### Minha Caminhada

Mock/fake:

- pontos;
- níveis;
- badges;
- histórico;
- mentor;
- destaques;
- rankings;
- mapas;
- progresso.

Origem real esperada:

- regras de pontuação;
- logs de pontuação;
- conquistas;
- níveis;
- serviço de cálculo;
- mentor por regras;
- histórico de respostas;
- permissões por perfil.

### Intercessão

Mock/fake:

- pedidos;
- intercessores;
- atendimentos;
- avaliações.

Origem real esperada:

- prayer_requests;
- prayer_followups;
- intercession_assignments;
- intercession_feedbacks;
- point_logs;
- privacy/policies.

## 6. Fallbacks seguros

Quando não houver dado real:

- mostrar “Sem registros ainda”;
- mostrar “Ainda não há histórico”;
- mostrar “Aguardando validação”;
- não inventar pontos;
- não inventar financeiro;
- não inventar família;
- não inventar intercessão;
- não inventar posição/ranking.

## 7. Checklist antes de liberar para membros

- A tela usa props reais?
- Existe fallback seguro?
- Existe policy?
- Existe validação?
- Existe auditoria/log?
- Os dados sensíveis estão protegidos?
- Responsáveis veem só o que podem?
- Jovens têm regra correta?
- Mocks removidos?
- `localStorage` não é banco?
- Build passou?
- Testes passaram?
