# Checklist Visual e Funcional da Minha Caminhada

## 1. Objetivo

Este checklist deve ser usado antes de aprovar qualquer nova página, ajuste visual, rota, componente ou integração real da Minha Caminhada.

A regra principal é evitar remendo, duplicidade, mock solto, permissão inconsistente e exposição indevida de dados.

## 2. Checklist visual

Antes de aprovar o visual, conferir:

- [ ] **Está premium?** A tela transmite cuidado, qualidade e coerência com a Família Resgate.
- [ ] **Está limpo?** A hierarquia visual está clara e sem excesso de elementos.
- [ ] **Segue a identidade visual?** Usa azul noturno, dourado, tons claros e estilo pastoral já adotado.
- [ ] **Tem hover suave?** Interações são discretas, sem efeito agressivo ou competitivo.
- [ ] **Não está poluído?** Cards, selos, números e listas não competem entre si.
- [ ] **Não tem scroll desnecessário?** O conteúdo é organizado para leitura confortável.
- [ ] **Botões são claros?** Cada botão informa ação real e destino correto.
- [ ] **Não parece ranking tóxico?** Destaques não usam pódio, medalha competitiva ou linguagem de superioridade espiritual.
- [ ] **Textos são pastorais?** Linguagem encoraja, orienta e cuida.
- [ ] **Estados vazios existem?** Quando não houver dados, a tela orienta sem inventar informação.

## 3. Checklist de rotas

Antes de aprovar navegação, conferir:

- [ ] **A rota existe?** Confirmar em `php artisan route:list` quando necessário.
- [ ] **O botão leva até ela?** Links apontam para rota real do módulo.
- [ ] **Não há rota escondida?** Nenhuma página relevante fica inacessível por navegação principal/secundária quando deve aparecer.
- [ ] **Não usa `#`?** Proibido `href="#"` e `to="#"`.
- [ ] **Não usa `javascript:void(0)`?** Proibido link falso.
- [ ] **Não há botão sem função?** Se for futuro, deve ser explicado como fluxo futuro ou não existir.
- [ ] **Breadcrumb está correto?** Deve refletir Central da Família > Minha Caminhada > área atual.
- [ ] **Voltar funciona?** Botões de retorno apontam para páginas reais.
- [ ] **Rotas antigas não confundem trilhos?** Compatibilidade não pode misturar geral e jovem.

## 4. Checklist de permissões

Antes de aprovar qualquer página, testar mentalmente e depois tecnicamente:

- [ ] **Membro comum vê só o que deve?** Caminhada Geral, regras gerais, histórico próprio, conquistas gerais, mentor geral e destaques gerais.
- [ ] **Membro comum não vê jovem/equipe?** Conteúdo dos Resgatados, equipes jovens e dados jovens não aparecem nem no payload.
- [ ] **Jovem vê trilho jovem?** Jovem/resgatado vê Caminhada Geral + Caminhada Jovem quando autorizado.
- [ ] **Equipe jovem fica no módulo jovem?** Pontos coletivos não aparecem como pontos individuais nem como geral.
- [ ] **Responsável vê só filhos vinculados?** Pai/mãe/responsável não vê todos os jovens.
- [ ] **Líder jovem vê escopo autorizado?** Líder não acessa dados fora do seu escopo.
- [ ] **Admin/liderança vê por policy?** Acesso amplo deve ser explícito, auditável e necessário.
- [ ] **Dados sensíveis têm proteção extra?** Pastoral, financeiro, intercessão e dados de menores exigem cuidado.
- [ ] **Frontend não é tratado como segurança final?** Policies/backend devem filtrar antes de enviar dados.

## 5. Checklist de dados

Antes de trocar mock por dado real, conferir:

- [ ] **É mock?** Identificar claramente o que ainda é estático.
- [ ] **Tem comentário de mock temporário?** Quando necessário, registrar intenção e origem futura.
- [ ] **Existe plano de dados reais?** Definir tabela, model, service, policy e props Inertia.
- [ ] **Fallback seguro existe?** Sem dados não pode gerar informação falsa.
- [ ] **Não inventa dados reais?** Não criar nomes, pontos, níveis ou conquistas como se fossem registros oficiais.
- [ ] **Props são filtradas por permissão?** Dados não autorizados não devem chegar ao frontend.
- [ ] **Estados vazios são pastorais?** Sem exposição, culpa ou comparação.
- [ ] **Logs/auditoria foram considerados?** Pontos, validações e conquistas precisam rastreabilidade.
- [ ] **Dados de terceiros são protegidos?** Histórico e conquistas devem respeitar vínculo e autorização.

## 6. Checklist de pontuação

Antes de aprovar qualquer regra ou tela com pontos:

- [ ] **Pontos gerais separados?** Caminhada Geral tem trilho próprio.
- [ ] **Pontos jovens separados?** Caminhada Jovem não soma automaticamente no geral.
- [ ] **Equipe jovem separada?** Pontos coletivos não viram pontos individuais sem regra explícita.
- [ ] **Intercessão com cuidado?** Sem exposição de atendimentos, pedidos, crises ou pessoas atendidas.
- [ ] **Não mede espiritualidade?** A tela comunica constância e cuidado, nunca valor espiritual.
- [ ] **Não compara pessoas de forma tóxica?** Evitar pódio, superioridade e competição religiosa.
- [ ] **Critérios são auditáveis?** Pontos devem vir de regra oficial, validação e limite.
- [ ] **Administração consegue gerir regras?** Futuramente regras devem ser administráveis.
- [ ] **Destaques são saudáveis?** Reconhecem sem expor e encorajam sem competir.

## 7. Checklist de limpeza

Antes de encerrar uma etapa, conferir:

- [ ] **Sem variável morta.** Dados, computeds e imports não usados devem ser removidos.
- [ ] **Sem CSS órfão evidente.** Estilos sem uso ou duplicados devem ser limpos quando não forem preparação clara.
- [ ] **Sem componente duplicado.** Não criar segunda versão de página/componente já existente.
- [ ] **Sem arquivo novo desnecessário.** Criar arquivo só quando fizer parte da estrutura oficial.
- [ ] **Sem versão `Final`, `V2`, `Novo`, `Corrigido` ou `Backup`.** Nome deve ser oficial e estável.
- [ ] **Sem texto antigo `ranking` na interface.** Para membros, usar `destaques` quando for visual/conceitual.
- [ ] **Sem link morto.** Proibido `href="#"`, `to="#"` e `javascript:void(0)`.
- [ ] **Sem mock contraditório.** Dados fake não podem contrariar regras oficiais de permissão.
- [ ] **Sem regra espalhada.** Lógica central deve migrar para service/policy quando sair do protótipo.

## 8. Checklist antes de uso real

Antes de liberar Minha Caminhada como módulo real:

- [ ] **Remover mocks ou isolá-los como fallback explícito.**
- [ ] **Ligar backend real.**
- [ ] **Aplicar policies.**
- [ ] **Testar com membro comum.**
- [ ] **Testar com jovem/resgatado.**
- [ ] **Testar com participante de equipe jovem.**
- [ ] **Testar com responsável.**
- [ ] **Testar com líder jovem.**
- [ ] **Testar com secretaria/administração.**
- [ ] **Testar com pastor/liderança pastoral.**
- [ ] **Auditar dados sensíveis.**
- [ ] **Validar payload Inertia.** Dados não autorizados não podem ser enviados.
- [ ] **Validar build.** Rodar `npm run build` quando houver alteração de frontend.
- [ ] **Validar rotas.** Rodar `php artisan route:list` quando houver alteração de rota.
- [ ] **Validar testes.** Rodar testes quando houver backend, service, policy ou regra real.
- [ ] **Validar `git diff --check`.**

## 9. Checklist por página

### Página principal

- [ ] Jornada jovem aparece apenas para autorizados.
- [ ] Pontos gerais e jovens estão separados.
- [ ] Destaques respeitam perfil.
- [ ] Próximos passos respeitam trilho visível.

### Nível/mapa

- [ ] Mapa geral e jovem não se confundem.
- [ ] Nível atual vem do trilho correto.
- [ ] Próximo nível usa rota correta.
- [ ] Rota antiga mantém compatibilidade sem quebrar separação.

### Histórico

- [ ] Histórico mostra apenas registros permitidos.
- [ ] Filtros respeitam perfil.
- [ ] Dados de filhos aparecem apenas para responsáveis autorizados.
- [ ] Dados sensíveis são omitidos ou resumidos.

### Mentor

- [ ] Membro comum vê mentor geral.
- [ ] Jovem vê mentor geral + jovem.
- [ ] Responsável vê filhos vinculados.
- [ ] Liderança vê acompanhamento sem exposição indevida.
- [ ] Textos deixam claro que não substitui cuidado pastoral humano.

### Regras

- [ ] Membro comum vê regras gerais.
- [ ] Jovem vê regras gerais + jovens.
- [ ] Equipe jovem vê regras de equipe quando autorizado.
- [ ] Admin vê regras administráveis.
- [ ] Regras visuais batem com regras reais.

### Ranking/Destaques

- [ ] Interface usa `destaques`, não linguagem competitiva de ranking.
- [ ] Membro comum vê apenas destaques gerais.
- [ ] Jovem vê destaques jovens somente quando autorizado.
- [ ] Equipes aparecem somente para autorizados.
- [ ] Intercessão não expõe dados sensíveis.

### Conquistas

- [ ] Conquistas gerais separadas das jovens.
- [ ] Conquistas administrativas aparecem somente quando visíveis/concedidas/autorizadas.
- [ ] Conquistas financeiras têm cuidado e policy.
- [ ] Conquistas sensíveis ficam ocultas ou privadas.
- [ ] Catálogo não parece amplo demais para membro comum.

## 10. Critério de aprovação

Uma página da Minha Caminhada só deve ser considerada aprovada quando:

- [ ] **Visual está coerente.**
- [ ] **Rotas são reais.**
- [ ] **Permissões estão claras.**
- [ ] **Mocks estão identificados.**
- [ ] **Plano de dados reais existe.**
- [ ] **Pontuação respeita trilhos.**
- [ ] **Não há exposição indevida.**
- [ ] **Não há lixo técnico evidente.**
- [ ] **Validações necessárias passaram.**
