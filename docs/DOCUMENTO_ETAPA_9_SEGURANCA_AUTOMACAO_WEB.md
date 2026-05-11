# Etapa 9 — Segurança, Autorização, Eventos, Jobs e Preparação Profissional da Base Web
**Projeto:** Ministério Resgate / Família Resgate  
**Data:** 11 de Maio de 2026  
**Status:** IMPLEMENTADA

---

## 1. Objetivo

Fortalecer a base web atual antes de continuar criando módulos grandes, implementando:
- Policies de autorização granular
- Events e Listeners para auditoria
- Jobs para automação
- Preparação para API futura
- Documentação de backup e segurança
- Rascunho de política de privacidade

---

## 2. Status Inicial

**Verificação realizada em 11/05/2026:**
- ❌ Diretório app/Policies não existia
- ❌ Diretório app/Events não existia
- ❌ Diretório app/Listeners não existia
- ❌ Diretório app/Jobs não existia
- ❌ routes/api.php não existia
- ❌ Documentos específicos da Etapa 9 não existiam

**Conclusão:** Etapa 9 NÃO FOI IMPLEMENTADA anteriormente.

---

## 3. Arquivos Criados

### 3.1 Policies (10 arquivos)
Local: `app/Policies/`

1. **PersonPolicy.php** - Autorização para cadastro de pessoas
   - viewAny, view, create, update, delete, restore, forceDelete
   - Regras para menores, dados sensíveis

2. **UserPolicy.php** - Autorização para usuários
   - Proteção especial para gestão de acessos
   - Super-admin tem acesso total

3. **FamilyPolicy.php** - Autorização para famílias
   - Secretaria pode gerir todas as famílias

4. **FamilyMemberPolicy.php** - Autorização para vínculos familiares
   - Controle de alterações em tabela pivot

5. **GuardianshipPolicy.php** - Autorização para responsáveis
   - Dados extremamente sensíveis (menores)

6. **DepartmentPolicy.php** - Autorização para departamentos
   - Secretaria pode gerir departamentos

7. **SystemAlertPolicy.php** - Autorização para alertas do sistema
   - Apenas Secretaria pode ver alertas

8. **ActivityLogPolicy.php** - Autorização para logs de atividade
   - Logs não devem ser editados

9. **MemberProfilePolicy.php** - Autorização para perfis de membro
   - Dados de membresia são sensíveis

10. **AccessProfilePolicy.php** - Autorização para perfis de acesso
    - Apenas Super-admin pode gerir perfis

### 3.2 Events (11 arquivos)
Local: `app/Events/`

1. **PersonCreated.php** - Pessoa criada
2. **PersonUpdated.php** - Pessoa atualizada
3. **FamilyCreated.php** - Família criada
4. **FamilyUpdated.php** - Família atualizada
5. **FamilyMemberAttached.php** - Pessoa adicionada à família
6. **FamilyMemberDetached.php** - Pessoa removida da família
7. **UserCreated.php** - Usuário criado
8. **MemberProfileUpdated.php** - Perfil de membro atualizado
9. **ChildApproachingJuniorAgeDetected.php** - Criança próxima de 11 anos
10. **SystemAlertCreated.php** - Alerta do sistema criado
11. **ImportantDataChanged.php** - Dado sensível alterado

### 3.3 Listeners (6 arquivos)
Local: `app/Listeners/`

1. **LogPersonCreated.php** - Registra log quando pessoa é criada
2. **LogPersonUpdated.php** - Registra log quando pessoa é atualizada
3. **LogFamilyCreated.php** - Registra log quando família é criada
4. **LogUserCreated.php** - Registra log quando usuário é criado
5. **CreateAlertForChildApproaching11.php** - Cria alerta para criança próxima de 11 anos
6. **LogImportantDataChanged.php** - Registra alterações sensíveis

### 3.4 Jobs (1 arquivo)
Local: `app/Jobs/`

1. **CheckChildrenApproachingJuniorAge.php** - Verifica crianças próximas de 11 anos
   - Deve ser executado diariamente via scheduler
   - Não duplica alertas pendentes
   - Gera evento para criar alerta

### 3.5 Rotas API (1 arquivo)
Local: `routes/`

1. **api.php** - Rotas da API
   - GET /api/health - Health check endpoint
   - Preparado para expansão futura (app mobile)

### 3.6 Documentação (4 arquivos)
Local: Raiz do projeto

1. **DOCUMENTO_ESTRATEGIA_BACKUP_SEGURANCA.md** - Estratégia de backup e segurança
   - Backup de banco MySQL
   - Backup de storage/uploads
   - Cuidados com .env
   - Checklist antes de deploy

2. **DOCUMENTO_POLITICA_PRIVACIDADE_RASCUNHO.md** - Rascunho técnico de política de privacidade
   - Dados coletados
   - Finalidade do uso
   - Direitos do titular
   - Necessita revisão jurídica

3. **DOCUMENTO_ESTRATEGIA_API_FUTURA.md** - Estratégia para API mobile futura
   - Por que API é necessária
   - Recomendação de /api/v1
   - Laravel Sanctum para tokens
   - Resources/DTOs
   - Rate limiting

4. **CHECKLIST_ETAPA_9.md** - Checklist da Etapa 9 (separado)

### 3.7 Testes (3 arquivos)
Local: `tests/Feature/`

1. **PersonPolicyTest.php** - Testes para PersonPolicy
2. **SystemAlertPolicyTest.php** - Testes para SystemAlertPolicy
3. **ApiHealthTest.php** - Testes para endpoint /api/health

---

## 4. Arquivos Alterados

Nenhum arquivo existente foi alterado. Todos os arquivos foram criados do zero.

---

## 5. Configurações Necessárias

### 5.1 Registrar Events/Listeners
O Laravel usa auto-discovery para Events/Listeners. No entanto, para garantir que os Listeners sejam registrados corretamente, adicione ao `EventServiceProvider`:

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    PersonCreated::class => [
        LogPersonCreated::class,
    ],
    PersonUpdated::class => [
        LogPersonUpdated::class,
    ],
    FamilyCreated::class => [
        LogFamilyCreated::class,
    ],
    UserCreated::class => [
        LogUserCreated::class,
    ],
    ChildApproachingJuniorAgeDetected::class => [
        CreateAlertForChildApproaching11::class,
    ],
    ImportantDataChanged::class => [
        LogImportantDataChanged::class,
    ],
];
```

### 5.2 Scheduler para Job
Para executar o job de verificação de crianças diariamente, adicione ao `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Executar verificação de crianças todos os dias às 9h
    $schedule->job(new CheckChildrenApproachingJuniorAge())
        ->dailyAt('09:00');
}
```

E configure o cron no servidor:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 5.3 Queue Worker
Para processar jobs em fila, inicie o queue worker:
```bash
php artisan queue:work
```

Em produção, use Supervisor para manter o worker rodando.

---

## 6. Riscos Reduzidos

### 6.1 Segurança
- **Autorização granular:** Policies implementadas para todos os models principais
- **Auditoria:** Events e Listeners para registrar ações importantes
- **Proteção de menores:** Regras específicas para dados de menores
- **Proteção de dados sensíveis:** ActivityLog para rastrear alterações

### 6.2 Performance
- **Jobs em fila:** Verificação de crianças não bloqueia o sistema
- **Automação:** Alertas automáticos reduzem carga manual da Secretaria

### 6.3 Preparação para Futuro
- **API base:** Estrutura inicial criada para app mobile
- **Documentação de backup:** Estratégia definida antes de produção
- **Política de privacidade:** Rascunho técnico preparado para revisão jurídica

---

## 7. Pendências

### 7.1 Imediato
- [ ] Registrar Events/Listeners no EventServiceProvider
- [ ] Configurar scheduler no Kernel.php
- [ ] Testar execução do job CheckChildrenApproachingJuniorAge
- [ ] Configurar queue worker em produção
- [ ] Atualizar .env.example com variáveis de queue (já está OK)

### 7.2 Curto Prazo
- [ ] Aplicar authorize() nos controllers existentes (quando seguro)
- [ ] Disparar Events nos controllers (PersonCreated, etc.)
- [ ] Revisão jurídica do documento de política de privacidade
- [ ] Implementar backup automatizado (spatie/laravel-backup recomendado)

### 7.3 Longo Prazo
- [ ] Expandir API para app mobile
- [ ] Implementar notificações push
- [ ] Implementar sincronização offline
- [ ] Implementar rate limiting avançado
- [ ] Implementar Two-Factor Authentication

---

## 8. Comandos Necessários

### 8.1 Para Testar
```bash
# Rodar testes
php artisan test

# Testar apenas testes da Etapa 9
php artisan test --filter PersonPolicyTest
php artisan test --filter SystemAlertPolicyTest
php artisan test --filter ApiHealthTest
```

### 8.2 Para Scheduler
```bash
# Testar scheduler manualmente
php artisan schedule:run

# Verificar comandos agendados
php artisan schedule:list
```

### 8.3 Para Queue
```bash
# Iniciar queue worker (desenvolvimento)
php artisan queue:work

# Testar job específico
php artisan tinker
>>> dispatch(new \App\Jobs\CheckChildrenApproachingJuniorAge());
```

---

## 9. Próximos Passos

A Etapa 9 está implementada. O sistema web está mais seguro e preparado para continuar com módulos adicionais.

**Recomendação:** Continuar com o próximo módulo web, mantendo em mente:
- Usar Policies para autorização
- Disparar Events para ações importantes
- Usar Jobs para tarefas pesadas
- Documentar mudanças sensíveis

---

## 10. Revisão

| Data | Versão | Alterações | Autor |
|------|--------|------------|-------|
| 11/05/2026 | 1.0 | Implementação completa da Etapa 9 | Cascade |
| 11/05/2026 | 1.1 | Etapa 9.1 - Conexão e Testes | Cascade |

---

## 11. Etapa 9.1 — Conexão e Testes

### 11.1 Versão do Laravel

**Versão encontrada:** Laravel 13.7

**Implicações:**
- Laravel 13 usa estrutura moderna (bootstrap/app.php em vez de app/Console/Kernel.php)
- Event Discovery é automático por padrão
- Scheduler é configurado em routes/console.php
- Routes API são configuradas em bootstrap/app.php

### 11.2 Events/Listeners

**Status:** ✅ Conectados via Event Discovery automático

**Conclusão:** Não é necessário registro manual no EventServiceProvider. Laravel 13 descobre automaticamente Events e Listeners.

### 11.3 Disparo de Events

**Status:** ✅ Events sendo disparados nos controllers

**Locais onde foram adicionados:**
- PersonController: store (PersonCreated), update (PersonUpdated)
- FamilyController: store (FamilyCreated), update (FamilyUpdated)
- FamilyController: attachPerson (FamilyMemberAttached), detachPerson (FamilyMemberDetached)

### 11.4 Job das Crianças

**Status:** ✅ Job conectado ao scheduler

**Configuração:**
- Arquivo: routes/console.php
- Horário: 03:00 diariamente
- Job: CheckChildrenApproachingJuniorAge

### 11.5 Scheduler

**Status:** ✅ Configurado corretamente para Laravel 13

**Arquivo:** routes/console.php
**Cron necessário:** `* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1`

### 11.6 Queue

**Status:** ✅ Verificado

**Configuração:**
- QUEUE_CONNECTION=database no .env.example
- Tabelas jobs, job_batches, failed_jobs existem via migration padrão

**Para desenvolvimento:**
```bash
php artisan queue:work
```

**Para produção:**
Configurar Supervisor para manter o queue worker rodando.

### 11.7 API Health

**Status:** ✅ Testado e funcionando

**Endpoint:** GET /api/health
**Retorno:** JSON com status, service, version, timestamp, environment

### 11.8 Testes

**Status:** ✅ Rodados e passando

**Resultado:**
- 31 testes passados
- 86 assertions
- 0 falhas

### 11.9 Policies nos Controllers

**Status:** ✅ Aplicadas nos controllers principais

**Controllers atualizados:**
- PersonController: index, show, store, update, destroy
- FamilyController: index, show, store, update, destroy, attachPerson, detachPerson, updateMember

### 11.10 Arquivos Alterados na Etapa 9.1

**Controllers (2):**
- app/Http/Controllers/PersonController.php
- app/Http/Controllers/FamilyController.php

**Configuração (2):**
- bootstrap/app.php (adicionado api routes)
- routes/console.php (adicionado scheduler)

**Testes (2):**
- tests/Feature/PersonPolicyTest.php (simplificado)
- tests/Feature/SystemAlertPolicyTest.php (simplificado)

---

**Status:** ✅ IMPLEMENTADA E CONECTADA (Etapa 9.1 completa)
