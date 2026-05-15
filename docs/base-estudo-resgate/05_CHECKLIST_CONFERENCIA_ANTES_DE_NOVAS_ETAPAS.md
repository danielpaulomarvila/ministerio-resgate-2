# Checklist de conferência antes de novas etapas

## 1. Antes de criar nova página

- Existe rota?
- Existe botão/card/menu apontando para ela?
- Ela substitui algum placeholder?
- Alguma rota antiga precisa ser mantida por compatibilidade?
- O nome visual está correto?
- A página pertence a qual módulo?
- Qual perfil pode ver?
- Tem relação com família/responsável/jovem?
- Usa mock? Se sim, comentar como temporário.
- Precisa entrar no histórico diário?

## 2. Antes de aprovar uma página visual

- Está bonita?
- Está legível?
- Está compacta?
- Tem identidade Família Resgate?
- Tem rotas reais?
- Não usa `href="#"`?
- Não usa `javascript:void(0)`?
- Não tem botão morto?
- Não mistura públicos diferentes?
- Não cria competição espiritual?
- Não expõe dados sensíveis?
- Tem fallback visual?

## 3. Antes de implementar backend

- Quais mocks serão substituídos?
- Quais tabelas existem?
- Quais tabelas faltam?
- Quais models serão usados?
- Quais services calculam?
- Quais policies protegem?
- Quem valida?
- O que precisa de auditoria/log?
- O que precisa ser configurável pela Administração?
- O que precisa ser visível para responsável?
- O que é sigiloso?

## 4. Antes de mexer em pontuação

- É pontuação geral?
- É pontuação jovem?
- É pontuação de equipe jovem?
- Pode pontuar em mais de um trilho?
- Precisa validação?
- Tem limite diário/semanal/mensal?
- Conta para destaque?
- Conta para nível?
- Gera conquista?
- Pode ser editada pela Administração?
- Deve aparecer para o membro?
- Deve aparecer para a liderança?
- Tem risco de competição tóxica?

## 5. Antes de mexer em Destaques

- O nome visual é “Destaques”, não ranking espiritual?
- Geral e jovem estão separados?
- Equipe jovem está separada?
- Não há pódio agressivo?
- Não há “melhor crente”?
- Não expõe pessoa negativamente?
- O destaque reconhece constância/serviço?
- Existe cuidado pastoral?
- O usuário consegue acessar pela tela principal?

## 6. Antes de mexer em Intercessão

- O pedido é sigiloso?
- Quem pode ver?
- Quem pode atender?
- Quem pode avaliar?
- A avaliação é privada?
- Pontuação mensal fixa do intercessor está prevista?
- Pontuação por atendimento tem limite?
- Nota da pessoa atendida converte para no máximo 3 pontos?
- Liderança pode revisar?
- Nada vira competição de oração?

## 7. Antes de commit

- `npm run build`
- `php artisan route:list --path=familia-resgate --except-vendor --no-ansi`
- `git diff --check`
- buscar:
  - `href="#"`
  - `to="#"`
  - `javascript:void(0)`
  - conflitos Git
- revisar arquivos alterados;
- atualizar histórico diário;
- confirmar que não apagou dados reais;
- confirmar que não criou arquivo duplicado.
