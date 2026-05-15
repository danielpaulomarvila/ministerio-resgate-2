# Mapa funcional de módulos e lições para o Resgate 2.0

## 1. Central da Família

### Deve ter

- Visão geral familiar;
- Meu Perfil;
- Meu Financeiro;
- Minha Caminhada;
- Conquistas;
- Histórico;
- Mentor;
- Regras;
- Destaques;
- Bíblia/devocional;
- Eventos;
- Escalas;
- Oração/intercessão;
- Grupos/ministérios;
- Comunicação;
- Documentos;
- Mídias.

### Conferir sempre

- O card leva para rota real?
- A rota existe?
- A página não é placeholder?
- Os dados são mock ou reais?
- O perfil do usuário pode ver aquilo?
- Responsáveis podem ver filhos quando aplicável?
- Jovens têm área separada?

## 2. Meu Perfil

### Ideias reaproveitáveis

- Dados pessoais;
- Credenciais;
- Família;
- Pedido de alteração;
- Histórico de pedidos;
- Privacidade;
- Regras de acesso familiar.

### Correções necessárias no Resgate 2.0

- Nada de `localStorage` como base final;
- dados devem vir de `people`, `users`, `member_profiles`, `families`, `family_members`, `guardianships`, `departments`;
- membro vê, mas não edita direto;
- pedido de alteração vai para Secretaria aprovar;
- responsável vê dados dos filhos conforme regra e permissão;
- dados sigilosos pastorais não devem vazar para secretaria/família.

## 3. Meu Financeiro

### Deve incluir

- créditos;
- dívidas;
- pendências;
- dízimos;
- ofertas;
- inscrições;
- retiro/eventos;
- cantina;
- pagamentos;
- recibos;
- histórico;
- financeiro dos filhos menores vinculados.

### Correções necessárias

- nada de movimentos fake;
- tudo deve vir de lançamentos reais;
- responsáveis veem obrigações de filhos menores;
- menor de 11 anos não tem usuário nem financeiro próprio separado, dívida vai para responsável;
- jovens de 11 a 18 podem ter usuário/financeiro próprio, mas responsável acompanha;
- recibos precisam ser auditáveis.

## 4. Minha Caminhada

### Trilhos separados

1. Caminhada Geral da Igreja;
2. Caminhada Jovem dos Resgatados;
3. Equipes dos Resgatados.

### Deve incluir

- níveis;
- pontos;
- histórico;
- conquistas;
- mentor;
- regras;
- destaques;
- mapa;
- presenças;
- pontuação;
- validações.

### Correções necessárias

- não misturar jovem e adulto;
- não vender ranking espiritual;
- não depender de mock;
- backend deve calcular tudo;
- Administração deve configurar regras.

## 5. Intercessão

### Ideia nova registrada

Ser intercessor é cargo pesado e deve ter pontuação fixa mensal.

Além disso, o intercessor pode receber pontuação por atendimento/conversa/intercessão realizada.

Também pode existir avaliação da pessoa atendida, mas com muito cuidado para não virar popularidade.

### Regras sugeridas

- Pontuação fixa mensal por cargo ativo de intercessor;
- Pontos por atendimento confirmado;
- Pontos por acompanhamento continuado;
- Pontos por oração/intercessão em escala;
- Avaliação da pessoa atendida de 1 a 10 convertida em no máximo 3 pontos;
- A avaliação não deve ser pública;
- A avaliação não deve virar ranking de “intercessor preferido”;
- A liderança deve poder auditar.

## 6. Jovens / Resgatados

### Deve incluir

- caminhada jovem;
- equipes;
- desafios;
- missões;
- Bíblia na mão;
- devocional;
- presença nos encontros;
- ranking saudável por destaque;
- pontuação de equipe;
- tesouraria jovem;
- liderança jovem.

### Correções necessárias

- não misturar com adulto;
- não expor jovem de forma tóxica;
- responsáveis precisam acompanhar conforme idade;
- menores de 11 anos não entram como jovens;
- 11 a antes de 14 = Júniores com supervisão;
- 14 a antes de 18 = Jovens sem supervisão obrigatória dos pais no uso, mas responsáveis ainda acompanham financeiro.
