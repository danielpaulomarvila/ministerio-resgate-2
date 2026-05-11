# Checklist: Centro Família Resgate

## Checklist de Validação - Etapa 11.1

### Backend

- [ ] Rota `/familia` existe e está protegida por `auth`
- [ ] Rota nomeada como `familia.index`
- [ ] Controller `FamilyHubController` foi criado
- [ ] Controller carrega usuário autenticado
- [ ] Controller carrega pessoa vinculada ao usuário
- [ ] Controller carrega aniversariantes do dia
- [ ] Controller monta atalhos conforme permissões
- [ ] Controller não cria permissões novas
- [ ] Controller usa permissões existentes do sistema
- [ ] Página funciona mesmo se usuário não tiver `person_id`

### Frontend

- [ ] Layout `FamilyHubLayout.vue` foi criado
- [ ] Layout tem tema escuro (bg-gray-900)
- [ ] Layout tem header com logo e menu
- [ ] Layout tem dropdown com "Meu perfil" e "Sair"
- [ ] Página `Familia/Index.vue` foi criada
- [ ] Página usa layout `FamilyHubLayout`
- [ ] Página tem saudação principal
- [ ] Página tem saudação personalizada
- [ ] Página tem card de aniversariantes
- [ ] Página tem card de sistemas disponíveis
- [ ] Página tem card de avisos e pendências
- [ ] Página tem card futuro de oração
- [ ] Página tem card futuro de palavra do dia
- [ ] Não há scroll longo
- [ ] Não há texto cortado
- [ ] Não há card quebrado
- [ ] Visual está escuro/premium

### Permissões

- [ ] Nenhuma permissão nova foi criada
- [ ] Atalhos usam permissões existentes
- [ ] Secretaria aparece com `secretaria.view` ou `people.view`
- [ ] Departamentos aparece com `departments.view`
- [ ] Acessos aparece com `accesses.view` ou `access_profiles.view`
- [ ] Atalhos não aparecem sem permissão
- [ ] Backend protege atalhos (não apenas frontend)

### Dados

- [ ] Usa `User` existente
- [ ] Usa `Person` existente
- [ ] Usa `Permission` existente
- [ ] Não cria migration
- [ ] Não altera banco
- [ ] Aniversariantes filtram por dia e mês
- [ ] Aniversariantes limitados a 5 registros
- [ ] Aniversariantes mostram contador se houver mais

### Visual

- [ ] Tema escuro (bg-gray-900)
- [ ] Preto/cinza como base
- [ ] Laranja-dourado (amber-500) como destaque
- [ ] Visual premium
- [ ] Visual espiritual
- [ ] Visual moderno
- [ ] Visual elegante
- [ ] Visual limpo
- [ ] Evita scroll longo
- [ ] Evita dashboard gigante
- [ ] Evita cards demais
- [ ] Evita caixas em cima de caixas
- [ ] Evita textos cortados

### Limites da Etapa

- [ ] Não criou migration
- [ ] Não alterou banco
- [ ] Não criou permissões novas
- [ ] Não criou PIN
- [ ] Não criou Central de Oração
- [ ] Não criou Centro Pastoral
- [ ] Não criou Financeiro
- [ ] Não criou Cantina
- [ ] Não criou Site Público
- [ ] Não criou Mural de Fotos
- [ ] Não criou Mural de Artes
- [ ] Não criou Caixinha de Palavras
- [ ] Não criou Membro Destaque
- [ ] Não alterou redirecionamento do login
- [ ] Não fez push

### Documentação

- [ ] DOCUMENTO_CENTRO_FAMILIA_RESGATE.md foi criado
- [ ] CHECKLIST_CENTRO_FAMILIA_RESGATE.md foi criado
- [ ] README.md foi atualizado
- [ ] Documentação registra o que foi implementado
- [ ] Documentação registra o que ficou para o futuro
- [ ] Documentação registra rotas criadas
- [ ] Documentação registra componentes criados
- [ ] Documentação registra dados usados
- [ ] Documentação registra permissões usadas
- [ ] Documentação registra limites da etapa

### Testes

- [ ] `php artisan optimize:clear` passou
- [ ] `php artisan route:list --path=familia` mostra rota
- [ ] `php artisan test` passou
- [ ] `npm run build` passou
- [ ] `git status` mostra apenas arquivos criados/alterados
- [ ] Teste manual: página abre
- [ ] Teste manual: não fica tela branca
- [ ] Teste manual: não dá erro 500
- [ ] Teste manual: não dá erro 404
- [ ] Teste manual: console não mostra erro crítico
- [ ] Teste manual: visual está escuro/premium
- [ ] Teste manual: saudação personalizada aparece
- [ ] Teste manual: aniversariantes aparecem ou mensagem de vazio aparece
- [ ] Teste manual: sistemas disponíveis aparecem conforme permissão
- [ ] Teste manual: atalhos funcionais abrem rotas existentes
- [ ] Teste manual: não aparecem atalhos quebrados
- [ ] Teste manual: botão Sair funciona

### Validações de Qualidade

- [ ] Não criou arquivos Novo, Final, Corrigido, V2, Backup ou Copy
- [ ] Não copiou código antigo
- [ ] Não copiou visual antigo
- [ ] Não criou atalhos fantasmas para sistemas que não existem
- [ ] Não criou gambiarra
- [ ] Não criou duplicidade
- [ ] Não criou sistema bagunçado

### Commit

- [ ] `git add .` foi executado
- [ ] Commit foi feito com mensagem "feat: criar base visual do centro familia resgate"
- [ ] Commit não foi feito ainda (aguardando validação)
