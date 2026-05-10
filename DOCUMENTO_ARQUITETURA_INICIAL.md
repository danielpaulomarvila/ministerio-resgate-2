# DOCUMENTO ARQUITETURA INICIAL

## Stack Escolhida

### Backend
- **Laravel 13.x**: Framework PHP robusto e maduro para desenvolvimento de aplicações web
- **MySQL 8.x**: Banco de dados relacional amplamente utilizado e confiável
- **Laravel Sanctum**: Sistema de autenticação API para futuro app mobile
- **Eloquent ORM**: Mapeamento objeto-relacional do Laravel para manipulação de dados

### Frontend
- **Vue 3**: Framework JavaScript progressivo para construção de interfaces
- **Inertia.js**: Biblioteca que permite construir SPAs sem precisar de API separada
- **Tailwind CSS**: Framework CSS utilitário para estilização rápida e consistente
- **Vite**: Build tool moderno e rápido para bundling de assets

### Ferramentas
- **Composer**: Gerenciador de dependências PHP
- **NPM**: Gerenciador de dependências JavaScript
- **Git**: Sistema de controle de versão

## Por Que Usar Laravel

### 1. Maturidade e Estabilidade
- Laravel é um framework maduro, lançado em 2011
- Grande comunidade e ecossistema robusto
- Documentação extensa e bem mantida
- Atualizações constantes e suporte de longo prazo

### 2. Recursos Nativos
- Sistema de rotas poderoso e flexível
- Eloquent ORM intuitivo e expressivo
- Sistema de migrations para controle de versão do banco
- Sistema de seeders para dados de teste
- Sistema de autenticação pronto
- Sistema de permissões (Spatie Laravel Permission)
- Sistema de filas para processamento assíncrono
- Sistema de eventos e listeners
- Sistema de notificações

### 3. Segurança
- Proteção CSRF nativa
- Hash de senhas automático
- Proteção contra SQL injection via Eloquent
- Validação de dados robusta
- Middleware para autenticação e autorização

### 4. Escalabilidade
- Suporte a cache (Redis, Memcached)
- Suporte a filas (Redis, Beanstalkd, SQS)
- Suporte a filas de trabalho para processamento pesado
- Facilidade de integração com serviços de nuvem (AWS, DigitalOcean, etc.)

### 5. Desenvolvimento Rápido
- Artisan CLI para automação de tarefas
- Blade templates para views (embora estejamos usando Inertia)
- Sistema de factories para dados de teste
- Sistema de testes integrado (PHPUnit, Pest)

## Por Que Usar MySQL

### 1. Relacionalidade
- O sistema requer relacionamentos complexos entre entidades
- Foreign keys garantem integridade referencial
- Joins eficientes para consultas complexas
- Transações ACID para consistência de dados

### 2. Performance
- Índices otimizados para busca rápida
- Suporte a full-text search
- Cache de queries nativo
- Escalabilidade horizontal (replicação, sharding)

### 3. Ecossistema
- Amplamente utilizado e testado
- Ferramentas de administração (phpMyAdmin, MySQL Workbench)
- Suporte de backup e restore
- Integração com Laravel nativa

### 4. Custo
- Open source e gratuito
- Hospedagem amplamente disponível
- Alternativas compatíveis (MariaDB, Percona)

## Por Que Usar Vue/Inertia

### 1. Experiência do Usuário
- SPA (Single Page Application) sem recarregamentos
- Transições suaves entre páginas
- Resposta rápida às interações
- Estado mantido entre navegações

### 2. Desenvolvimento
- Vue 3 com Composition API é moderno e poderoso
- Inertia elimina a necessidade de API REST separada
- Reutilização de componentes
- Tipagem com TypeScript (opcional)
- Hot Module Replacement com Vite

### 3. Manutenção
- Código organizado e modular
- Separação clara entre lógica e apresentação
- Facilidade de testes
- Comunidade ativa

### 4. Performance
- Vite é extremamente rápido no desenvolvimento
- Bundle otimizado para produção
- Code splitting automático
- Lazy loading de componentes

## Como Preparar para App Futuro

### 1. API Sanctum
- Laravel Sanctum já instalado via Breeze
- Suporte a autenticação via tokens
- Suporte a autenticação via SPA
- Suporte a autenticação via mobile apps
- Proteção de rotas por middleware

### 2. Estrutura de Controllers
- Controllers separados por módulo
- Lógica de negócio centralizada
- Facilidade de expor endpoints API
- Validação de dados via Form Requests

### 3. Responsividade
- Tailwind CSS facilita design mobile-first
- Componentes reutilizáveis
- Layouts flexíveis
- Suporte a dark mode (planejado)

### 4. PWA (Progressive Web App)
- Capacitor para empacotar como app nativo
- Service Workers para cache offline
- Notificações push (futuro)
- Acesso a recursos do dispositivo (câmera, GPS)

### 5. Separação de Camadas
- Models: Lógica de dados
- Controllers: Lógica de negócio
- Services: Lógica complexa (futuro)
- Resources: Transformação de dados para API (futuro)
- Views (Vue): Apresentação

## Ordem Recomendada dos Próximos Módulos

### Fase 2: Secretaria e Gestão de Pessoas
1. CRUD completo de People (COMPLETADO - Fase 2.1)
   - **Estrutura separada**: Documentos e moradas foram movidos para tabelas próprias (person_documents, person_addresses)
   - Campos pessoais: nome completo, nome preferido, apelido/sobrenome, data de nascimento, gênero
   - Campos civis: estado civil (single, married, divorced, widowed, separated), nível de escolaridade (elementary, high_school, college, postgraduate, other)
   - Campos adicionais: nacionalidade, naturalidade, profissão, ocupação
   - Contatos: email, telemóvel principal, telemóvel secundário, whatsapp, notas de contacto
   - **Documentos (tabela person_documents)**: NIF (Número de Identificação Fiscal), Cartão de Cidadão, Passaporte, Título de Residência, outro documento, notas sobre documentos
   - **Morada (tabela person_addresses)**: País, distrito, concelho/município, freguesia, localidade, localidade manual, rua/avenida, número da porta, andar/fração, complemento, código postal (formato 0000-000), endereço completo
   - Vida cristã: batismo (is_baptized, baptism_date), conversão (conversion_date), quem convidou (invited_by_person_id)
   - Status: active, inactive, visitor, congregant, discipling, new_convert, regularization
   - Métodos auxiliares: ageGroupLabel(), canHaveUser(), canBeMember()
   - Relacionamentos: invitedBy (BelongsTo), invitedPeople (HasMany), document (hasOne - PersonDocument), addresses (hasMany - PersonAddress), primaryAddress (hasOne - PersonAddress)
   - Validações: StorePersonRequest e UpdatePersonRequest com estrutura separada (person, document, address) e mensagens em português
   - Páginas Vue: Index, Create, Edit, Show organizadas em seções (A) Dados Pessoais, (B) Contactos, (C) Documentos, (D) Morada, (E) Vida Cristã/Igreja, (F) Observações
   - Terminologia portuguesa: telemóvel, contactos, morada, freguesia, concelho, código postal
   - Avisos de elegibilidade: usuário, membro, departamento Resgatados, alerta de 11 anos
2. CRUD de Families
3. Gestão de Guardianships
4. Sistema de aprovação de cadastros
5. Sistema de alertas automáticos
6. Dashboard básico da Secretaria

### Fase 3: Membresia e Departamentos
1. CRUD de Member Profiles
2. Gestão de Departments
3. Vinculação de pessoas a departamentos
4. Sistema de categorias (Júnior/Jovem)
5. Relatórios de membresia

### Fase 4: Financeiro Básico
1. Contas financeiras
2. Categorias de transações
3. Transações (dízimos, ofertas)
4. Relatórios financeiros básicos
5. Integração com responsáveis financeiros

### Fase 5: Cantina
1. Produtos
2. Vendas
3. Cobrança em responsáveis de menores
4. Repasse para Tesouraria
5. Relatórios de vendas

### Fase 6: Louvor/Worship Central
1. Repertório
2. Cifras
3. Tonalidades
4. Escalas
5. Músicos e vocalistas
6. Escalas de culto

### Fase 7: Resgatados
1. Gestão de Júniores
2. Gestão de Jovens
3. Eventos
4. Presença
5. Gamificação (futuro)

### Fase 8: Área do Membro
1. Login via usuário
2. Visualização de dados pessoais
3. Solicitação de alterações
4. Visualização de eventos
5. Visualização de escalas
6. Dízimos e ofertas pessoais

### Fase 9: App Mobile
1. API Sanctum completa
2. Autenticação mobile
3. PWA com Capacitor
4. Build iOS
5. Build Android
6. Notificações push

### Fase 10: Resgate AI
1. Integração com IA
2. Assistente virtual
3. Sugestões automáticas
4. Análise de dados

## Regras Contra Duplicidade

### Regra Master
**Sempre que pedir para refazer, corrigir ou mudar algo, NÃO criar versão paralela, duplicada ou acumulada.**

### O Que NÃO Fazer
- Não criar arquivos como: DashboardNovo, DashboardFinal, DashboardCorrigido
- Não criar: Financeiro2, NovoFinanceiro, TelaFinal
- Não criar: ComponenteNovo, ControllerNovo, RotaAlternativa
- Não criar: CopiaDeAlgumaCoisa

### O Que Fazer
- Substituir a versão anterior de forma limpa e controlada
- Verificar se não contém dados reais antes de remover
- Preservar sempre: backups, banco real, usuários, membros, famílias, permissões, financeiro, documentos, fotos, comprovantes, .env

### Como Este Projeto Começou
- Projeto zerado localmente para evitar peso, duplicidade, erros antigos e código acumulado
- Estrutura limpa e profissional desde o início
- Preparada para crescer sem bagagem

## Regra de Comentários no Código

### Objetivo
Todo código criado deve ter comentários didáticos explicando os blocos principais.

### O Que Explicar
- O que aquele bloco faz
- Por que ele existe
- Como se conecta com o sistema
- Qual regra de negócio ele representa
- Quando aquela parte será usada

### Onde Aplicar
- Migrations
- Models
- Relacionamentos Eloquent
- Controllers
- Services
- Requests / Validações
- Policies / Permissões
- Rotas
- Components Vue
- Páginas Inertia
- Jobs
- Commands
- Seeders
- Regras de negócio

### Exemplos

#### Exemplo Ruim
```php
// cria variável
$name = $person->full_name;
```

#### Exemplo Bom
```php
// Relaciona esta pessoa ao usuário de login, quando ela tiver permissão para acessar o sistema.
// Menores de 11 anos não podem ter usuário conforme regra de idade.
$user = $person->user;
```

## Estrutura de Diretórios

```
ministerio-resgate/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controllers por módulo
│   │   ├── Requests/           # Validação de formulários
│   │   └── Middleware/         # Middleware personalizado
│   ├── Models/                 # Models do Eloquent
│   ├── Services/               # Lógica de negócio complexa (futuro)
│   └── Policies/               # Policies de autorização (futuro)
├── database/
│   ├── migrations/             # Migrations do banco
│   └── seeders/                # Seeders de dados
├── resources/
│   ├── js/
│   │   ├── Components/         # Componentes Vue
│   │   ├── Layouts/            # Layouts Inertia
│   │   └── Pages/              # Páginas Inertia
│   └── css/                    # Estilos Tailwind
├── routes/
│   ├── web.php                 # Rotas web
│   └── api.php                 # Rotas API (futuro)
├── tests/                      # Testes
└── public/                     # Arquivos públicos
```

## Padrões de Código

### Controllers
- Um controller por módulo funcional
- Métodos claros e descritivos
- Validação via Form Requests
- Retorno de respostas Inertia para web
- Retorno de JSON para API (futuro)

### Models
- Campos fillable explícitos
- Casts de tipos de dados
- Relacionamentos Eloquent
- Métodos auxiliares (scopes, accessors)
- Soft deletes onde aplicável

### Migrations
- Foreign keys explícitas
- Índices onde faz sentido
- Soft deletes em tabelas sensíveis
- Comentários explicativos
- Ordem correta de dependências

### Vue Components
- Composition API
- Props bem definidas
- Emits de eventos
- Slots para flexibilidade
- Nomes descritivos

## Segurança

### Autenticação
- Laravel Sanctum para API
- Breeze para autenticação web
- Senhas hash automaticamente
- Proteção CSRF

### Autorização
- Gates e Policies (futuro)
- Spatie Laravel Permission (futuro)
- Roles e Permissions
- Middleware de proteção de rotas

### Validação
- Form Requests para validação
- Regras customizadas quando necessário
- Mensagens de erro em português
- Sanitização de inputs

### Auditoria
- Tabela activity_logs para rastrear ações
- Registro de old_values e new_values
- IP address e user agent
- Logs de login

## Performance

### Cache
- Cache de queries frequentes
- Cache de configurações
- Cache de views
- Redis para cache distribuído (futuro)

### Filas
- Processamento assíncrono
- Envio de emails
- Geração de relatórios
- Processamento de imagens

### Otimização
- Lazy loading de relacionamentos
- Eager loading quando necessário
- Índices no banco
- Compressão de assets

## Monitoramento e Logging

### Logs
- Laravel Log para erros
- Monolog para logging estruturado
- Níveis de log apropriados
- Logs de atividades (activity_logs)

### Monitoramento
- Laravel Telescope (futuro)
- Sentry para erros (futuro)
- Uptime monitoring (futuro)
- Performance monitoring (futuro)

## Deploy

### Ambiente
- Homologação antes de produção
- Variáveis de ambiente por ambiente
- CI/CD para deploy automático (futuro)
- Rollback automático em caso de erro

### Backup
- Backup diário do banco
- Backup de arquivos (storage)
- Retention policy
- Teste de restore

## Etapa 4 - Painel Inicial da Secretaria

### Arquitetura do Painel

O painel da Secretaria foi implementado seguindo a arquitetura existente:

**Backend:**
- `SecretaryDashboardController` - Controller dedicado ao painel
- Busca dados usando Eloquent ORM (Person, Family, GuardianShip, FamilyMember)
- Queries otimizadas com eager loading onde necessário
- Acesso seguro com nullsafe operators e valores padrão
- Não usa dados fake ou seeders

**Frontend:**
- `resources/js/Pages/Secretaria/Dashboard.vue` - Página Vue dedicada
- Usa Inertia.js para comunicação server-driven
- Props com valores padrão para evitar erros
- Acesso seguro no Vue (verificações de null/undefined)
- Visual responsivo com Tailwind CSS

**Rota:**
- `GET /secretaria` - Rota protegida por middleware `auth`
- Nome da rota: `secretaria.dashboard`
- Integração com o menu autenticado

**Indicadores Calculados:**
O painel calcula 16 indicadores usando queries SQL diretas nos models:
- Totais (pessoas, famílias, responsáveis ativos)
- Faixa etária (crianças <11, júniores 11-13, jovens 14-17, adultos 18+)
- Atenções (pessoas sem família, menores sem responsável, cadastros incompletos)
- Listas recentes (últimas 5 pessoas, famílias, responsabilidades)

**Regra dos 11 Anos:**
O painel respeita a regra implementada na Etapa 3:
- Responsabilidade legal NÃO acaba automaticamente aos 11 anos
- Mostra aviso para crianças próximas de completar 11 anos
- Recomenda revisão de cadastro ao completar 11 anos

**Campo Quem Indicou / Convidou:**
O campo `invited_by_person_id` está preservado para uso futuro:
- Não implementou pontuação nesta etapa
- Não implementou ranking nesta etapa
- Futuramente poderá alimentar frutos de evangelismo e Membro Destaque

**Não Implementado Nesta Etapa:**
- Sistema completo de alertas
- Aprovação de cadastro
- Notificações reais
- Gráficos complexos
- Relatórios exportáveis

## Conclusão

Esta arquitetura foi projetada para ser:
- **Limpa**: Código organizado e sem duplicidade
- **Profissional**: Padrões de código e melhores práticas
- **Escalável**: Preparada para crescer com o sistema
- **Segura**: Autenticação, autorização e auditoria
- **Manutenível**: Documentação e comentários claros
- **Preparada para o futuro**: API Sanctum, PWA, app mobile

O sistema Ministério Resgate / Família Resgate está pronto para evoluir de forma controlada e profissional, sem acumular bagagem técnica.
