# Estratégia para App iOS e Android - Ministério Resgate

**Data:** 11 de Maio de 2026
**Projeto:** Ministério Resgate / Família Resgate

---

## Resumo Executivo

O sistema atual é uma aplicação web Laravel + Inertia + Vue. Para criar apps iOS e Android nativos, precisamos definir uma estratégia clara. Este documento apresenta as opções disponíveis, recomendações, e o que precisa ser feito antes de iniciar o desenvolvimento mobile.

---

## 1. Análise da Arquitetura Atual

### Stack Atual
- **Backend:** Laravel 13.7
- **Frontend:** Vue 3.4 + Inertia.js 2.0
- **CSS:** Tailwind CSS 3.2
- **Bundler:** Vite 8.0
- **Autenticação:** Laravel Breeze + Sanctum

### Compatibilidade com Mobile

**Funciona para Mobile:**
- ✅ Laravel como backend API
- ✅ Sanctum para autenticação por token
- ✅ Estrutura de banco sólida
- ✅ Sistema de permissões implementado
- ✅ Business logic em Services

**Não Funciona para Mobile:**
- ❌ Inertia.js (requer navegador)
- ❌ Vue rendering (requer navegador)
- ❌ Roteamento web (requer navegador)
- ❌ Blade templates (requer servidor)

---

## 2. Opções de Estratégia

### Opção 1: Laravel + API RESTful + Sanctum + App Nativo (RECOMENDADO)

#### Descrição
Criar uma API RESTful completa em Laravel, usar Sanctum para autenticação por token, e desenvolver apps nativos separados (React Native ou Flutter).

#### Arquitetura
```
┌─────────────────┐
│  App iOS (Swift │
│  ou React Native)│
└────────┬────────┘
         │ API RESTful
         │ Sanctum Token
         ↓
┌─────────────────┐
│  Laravel API    │
│  (routes/api.php)│
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  MySQL Database │
└─────────────────┘
```

#### Vantagens
- ✅ Performance nativa superior
- ✅ Acesso completo a APIs nativas (câmera, GPS, notificações)
- ✅ Experiência de usuário nativa
- ✅ Pode ser publicado na App Store/Google Play
- ✅ Funciona offline com sincronização
- ✅ Push notifications nativas
- ✅ Deep links funcionam corretamente

#### Desvantagens
- ❌ Precisa desenvolver duas interfaces (web + mobile)
- ❌ Curva de aprendizado maior
- ❌ Manutenção de duas codebases
- ❌ Tempo de desenvolvimento mais longo

#### O Que Precisa Ser Feito
1. Criar `routes/api.php` com todos os endpoints necessários
2. Configurar Sanctum para tokens de API
3. Criar API Resources para transformação de dados
4. Implementar autenticação por token (login, logout, refresh)
5. Implementar versionamento de API (`/api/v1`)
6. Configurar CORS para mobile
7. Criar tratamento padronizado de erros JSON
8. Implementar rate limiting para API
9. Criar documentação de API (Swagger/OpenAPI)
10. Escolher framework mobile (React Native ou Flutter)
11. Desenvolver app nativo
12. Implementar sincronização offline
13. Implementar notificações push (FCM/APNS)

#### Custo Estimado
- **Tempo:** 3-6 meses para MVP completo
- **Equipe:** 1 backend + 1-2 mobile developers
- **Manutenção:** Médio-Alto (duas codebases)

---

### Opção 2: Laravel + Inertia + PWA (Progressive Web App)

#### Descrição
Converter a aplicação web atual em uma PWA instalável, com service worker para offline e manifest para instalação.

#### Arquitetura
```
┌─────────────────┐
│  PWA (Vue +     │
│  Inertia)       │
│  Service Worker │
└────────┬────────┘
         │ Inertia
         │ Session Auth
         ↓
┌─────────────────┐
│  Laravel Web    │
│  (routes/web.php)│
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  MySQL Database │
└─────────────────┘
```

#### Vantagens
- ✅ Única codebase (web + mobile)
- ✅ Reutiliza todo o código Vue existente
- ✅ Curva de aprendizado menor
- ✅ Manutenção simplificada
- ✅ Pode ser instalada como app
- ✅ Funciona offline com service worker

#### Desvantagens
- ❌ Performance inferior a nativo
- ❌ Não tem acesso completo a APIs nativas
- ❌ Não pode ser publicado na App Store/Google Play facilmente
- ❌ Experiência de usuário não é nativa
- ❌ Limitações de hardware (câmera, GPS)
- ❌ Push notifications limitadas
- ❌ Deep links limitados

#### O Que Precisa Ser Feito
1. Criar `manifest.json` com metadados do app
2. Criar service worker para cache offline
3. Implementar estratégia de cache
4. Criar ícones PWA (192x192, 512x512)
5. Configurar theme color e background color
6. Implementar PWA install prompt
7. Testar em dispositivos móveis
8. Otimizar performance mobile
9. Implementar sincronização offline
10. Implementar push notifications (Web Push API)

#### Custo Estimado
- **Tempo:** 1-2 meses
- **Equipe:** 1 full-stack developer
- **Manutenção:** Baixo (única codebase)

---

### Opção 3: Laravel + Vue + Capacitor

#### Descrição
Empacotar a aplicação Vue atual com Capacitor para criar apps híbridos que funcionam como apps nativos.

#### Arquitetura
```
┌─────────────────┐
│  Capacitor App  │
│  (Vue Bundle)   │
│  Plugins Nativos│
└────────┬────────┘
         │ WebView
         │ Session Auth
         ↓
┌─────────────────┐
│  Laravel Web    │
│  (routes/web.php)│
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  MySQL Database │
└─────────────────┘
```

#### Vantagens
- ✅ Reutiliza todo o código Vue existente
- ✅ Pode ser publicado na App Store/Google Play
- ✅ Acesso a algumas APIs nativas via plugins Capacitor
- ✅ Curva de aprendizado menor que nativo
- ✅ Única codebase base

#### Desvantagens
- ❌ Performance inferior a nativo (WebView)
- ❌ Acesso limitado a APIs nativas
- ❌ Experiência de usuário não é nativa
- ❌ WebView pode ter bugs em alguns dispositivos
- ❌ Tamanho do app maior (inclui WebView)
- ❌ Atualizações dependem de atualização do app

#### O Que Precisa Ser Feito
1. Instalar Capacitor
2. Configurar Capacitor para iOS e Android
3. Criar ícones e splash screens
4. Configurar plugins Capacitor (Camera, Geolocation, etc.)
5. Empacotar app Vue com Capacitor
6. Testar em dispositivos iOS e Android
7. Submeter para App Store/Google Play
8. Implementar sincronização offline
9. Implementar notificações push (Capacitor Push)

#### Custo Estimado
- **Tempo:** 2-3 meses
- **Equipe:** 1 full-stack + 1 mobile developer
- **Manutenção:** Médio (WebView + plugins)

---

## 3. Recomendação

### Estratégia Recomendada: Opção 1 (API + App Nativo)

**Por que:**
1. **Performance:** Apps nativos têm performance superior
2. **Experiência:** Experiência de usuário nativa é melhor
3. **Funcionalidades:** Acesso completo a APIs nativas
4. **Escalabilidade:** Pode crescer sem limitações de WebView
5. **Profissionalismo:** App nativo transmite mais profissionalismo
6. **Futuro:** Pode evoluir para recursos avançados (AR, ML, etc.)

### Quando Implementar
- **Fase 1 (Agora):** Preparar API base
  - Criar `routes/api.php`
  - Configurar Sanctum para API
  - Criar Resources básicos
  - Implementar autenticação por token

- **Fase 2 (3-6 meses):** Desenvolver MVP mobile
  - Escolher framework (React Native recomendado)
  - Desenvolver funcionalidades básicas
  - Testar em dispositivos

- **Fase 3 (6-12 meses):** Lançamento
  - Publicar na App Store/Google Play
  - Implementar sincronização offline
  - Implementar notificações push

---

## 4. O Que Precisa Antes de Começar App Mobile

### Crítico (Obrigatório)
- ✅ API RESTful implementada
- ✅ Sanctum configurado para tokens móveis
- ✅ Resources/DTOs criados
- ✅ Autenticação por token (login, logout, refresh)
- ✅ Permissões implementadas na API
- ✅ Tratamento padronizado de erros JSON
- ✅ Versionamento de API (`/api/v1`)
- ✅ CORS configurado
- ✅ Rate limiting configurado

### Importante
- ✅ Estratégia de sincronização offline
- ✅ Estratégia de notificações push
- ✅ Endpoint de upload de fotos
- ✅ Deep links configurados
- ✅ Documentação de API completa
- ✅ Testes de API automatizados

### Desejável
- ✅ Estratégia de cache mobile
- ✅ Estratégia de atualização incremental
- ✅ Analytics mobile configurado
- ✅ Crash reporting configurado
- ✅ Monitoramento de performance

---

## 5. O Que Deve Virar API

### CRUD Básicos
- ✅ Pessoas (CRUD completo)
- ✅ Famílias (CRUD completo)
- ✅ Responsáveis (CRUD completo)
- ✅ Departamentos (CRUD completo)
- ✅ Usuários (CRUD completo)
- ✅ Perfis de Acesso (CRUD completo)

### Funcionalidades Específicas
- ✅ Login por token
- ✅ Logout (revogar token)
- ✅ Refresh token
- ✅ Obter perfil do usuário atual
- ✅ Atualizar perfil do usuário atual
- ✅ Upload de foto de perfil
- ✅ Upload de documentos
- ✅ Listar alertas do usuário
- ✅ Listar solicitações do usuário
- ✅ Criar solicitação
- ✅ Ver detalhes de pessoa
- ✅ Ver detalhes de família
- ✅ Buscar pessoas
- ✅ Buscar famílias

### Endpoints de Sincronização
- ✅ Obter dados para sincronização inicial
- ✅ Enviar mudanças locais
- ✅ Resolver conflitos
- ✅ Ver status de sincronização

---

## 6. O Que Pode Continuar Só no Web

### Dashboard Administrativo
- ✅ Dashboard da Secretaria
- ✅ Relatórios complexos
- ✅ Gráficos e analytics
- ✅ Gestão de permissões
- ✅ Configurações do sistema

### Funcionalidades Avançadas
- ✅ Edição avançada de formulários
- ✅ Importação/Exportação de dados
- ✅ Gestão de backups
- ✅ Logs de auditoria detalhados
- ✅ Configurações de sistema

---

## 7. Estrutura de API Recomendada

### Versionamento
```
/api/v1/pessoas
/api/v1/familias
/api/v1/acessos
```

### Autenticação
```
POST /api/v1/auth/login
POST /api/v1/auth/logout
POST /api/v1/auth/refresh
GET  /api/v1/auth/me
```

### Pessoas
```
GET    /api/v1/pessoas
GET    /api/v1/pessoas/{id}
POST   /api/v1/pessoas
PUT    /api/v1/pessoas/{id}
DELETE /api/v1/pessoas/{id}
GET    /api/v1/pessoas/buscar?q={termo}
```

### Famílias
```
GET    /api/v1/familias
GET    /api/v1/familias/{id}
POST   /api/v1/familias
PUT    /api/v1/familias/{id}
DELETE /api/v1/familias/{id}
```

### Usuários
```
GET    /api/v1/usuarios/me
PUT    /api/v1/usuarios/me
POST   /api/v1/usuarios/me/foto
```

### Sincronização
```
GET    /api/v1/sync/inicial
POST   /api/v1/sync/enviar
GET    /api/v1/sync/status
```

---

## 8. Capacitor - Quando e Como Usar

### Quando Usar Capacitor
- **NÃO recomendado** como estratégia principal
- Pode ser usado como **ponte temporária** enquanto API é desenvolvida
- Pode ser usado para **protótipo rápido** antes de decidir por nativo

### Riscos do Capacitor
- ❌ Performance WebView inferior
- ❌ Bugs específicos de WebView
- ❌ Limitações de hardware
- ❌ Atualizações dependem de atualização do app
- ❌ Tamanho do app maior
- ❌ Experiência não nativa

### Arquivos que Capacitor Criaria
```
capacitor.config.json
ios/ (diretório completo iOS)
android/ (diretório completo Android)
app/src/assets/ (ícones, splash screens)
```

### Como Manter Separado
- Criar diretório `mobile/` fora do Laravel
- Usar Git submodules ou repositório separado
- Manter código mobile em repositório independente
- Usar API como única integração

---

## 9. Framework Mobile Recomendado

### React Native (RECOMENDADO)

#### Vantagens
- ✅ JavaScript/TypeScript (mesma linguagem do frontend)
- ✅ Comunidade enorme
- ✅ Bibliotecas abundantes
- ✅ Hot reload rápido
- ✅ Fácil de aprender para desenvolvedores web
- ✅ Expo para desenvolvimento rápido
- ✅ Performance boa (próximo de nativo)

#### Desvantagens
- ❌ Curva de aprendizado inicial
- ❌ Debugging pode ser complexo
- ❌ Algumas bibliotecas podem ter bugs

#### Custo
- **Tempo:** 3-4 meses para MVP
- **Equipe:** 1-2 mobile developers
- **Manutenção:** Médio

---

### Flutter

#### Vantagens
- ✅ Performance excelente (compila para nativo)
- ✅ Hot reload rápido
- ✅ UI consistente em todas as plataformas
- ✅ Google mantém ativamente
- ✅ Widgets pré-construídos

#### Desvantagens
- ❌ Dart (nova linguagem para aprender)
- ❌ Comunidade menor que React Native
- ❌ Bibliotecas menos abundantes
- ❌ Debugging pode ser complexo

#### Custo
- **Tempo:** 3-4 meses para MVP
- **Equipe:** 1-2 mobile developers
- **Manutenção:** Médio

---

## 10. Notificações Push

### Firebase Cloud Messaging (FCM)
- ✅ Gratuito
- ✅ Suporta iOS e Android
- ✅ Fácil integração
- ✅ Console para gerenciamento

### Apple Push Notification Service (APNS)
- ✅ Necessário para iOS
- ✅ Integração via FCM ou direta
- ❌ Requer certificado Apple

### Implementação
1. Criar tabela `push_tokens` no banco
2. Endpoint para registrar token do dispositivo
3. Endpoint para enviar notificação
4. Integração com FCM/APNS
5. Preferências de notificação por usuário

---

## 11. Sincronização Offline

### Estratégia Recomendada
1. **Sincronização Inicial:** Download completo de dados do usuário
2. **Sincronização Incremental:** Download apenas de mudanças
3. **Conflitos:** Estratégia de "last write wins" ou resolução manual
4. **Fila:** Mudanças locais enfileiradas e enviadas quando online
5. **Status:** Indicador de sincronização no app

### Implementação
1. Criar tabela `sync_logs` para rastrear sincronizações
2. Endpoint para obter dados iniciais
3. Endpoint para obter mudanças desde última sincronização
4. Endpoint para enviar mudanças locais
5. Lógica de resolução de conflitos

---

## 12. Política de Privacidade e Termos

### O Que Precisa
- ✅ Política de privacidade (LGPD compliance)
- ✅ Termos de uso
- ✅ Política de cookies
- ✅ Direito ao esquecimento
- ✅ Retenção de dados
- ✅ Compartilhamento de dados
- ✅ Segurança de dados

### Implementação
1. Criar páginas no app para exibir políticas
2. Criar endpoint para obter políticas atualizadas
3. Implementar consentimento explícito
4. Implementar opção de excluir conta
5. Implementar opção de exportar dados

---

## 13. Deep Links

### Implementação
1. Configurar deep links no app nativo
2. Criar rotas no backend para redirecionamento
3. Exemplo: `ministerioresgate://pessoa/123`
4. Abrir tela específica do app

### Casos de Uso
- Link de email para ver detalhes de pessoa
- Link de notificação para ver alerta
- Link de site para abrir app específico

---

## 14. Plano de Implementação

### Fase 1: Preparação da API (1-2 meses)
1. Criar `routes/api.php`
2. Configurar Sanctum para API
3. Criar Resources/DTOs
4. Implementar autenticação por token
5. Implementar endpoints básicos
6. Implementar permissões na API
7. Configurar CORS
8. Criar documentação de API
9. Criar testes de API

### Fase 2: Protótipo Mobile (1-2 meses)
1. Escolher framework (React Native)
2. Configurar projeto mobile
3. Implementar autenticação
4. Implementar listagem básica
5. Implementar detalhes
6. Testar em dispositivos

### Fase 3: MVP Completo (2-3 meses)
1. Implementar CRUD completo
2. Implementar upload de fotos
3. Implementar sincronização offline
4. Implementar notificações push
5. Implementar deep links
6. Testar extensivamente
7. Publicar beta

### Fase 4: Lançamento (1 mês)
1. Correções finais
2. Publicar na App Store
3. Publicar no Google Play
4. Monitorar crashes
5. Coletar feedback

---

## 15. Conclusão

### Recomendação Final
**Usar Opção 1: Laravel + API RESTful + Sanctum + React Native**

### Justificativa
- Performance superior
- Experiência nativa
- Acesso completo a APIs nativas
- Escalabilidade a longo prazo
- Profissionalismo

### Próximos Passos Imediatos
1. Criar `routes/api.php`
2. Configurar Sanctum para API
3. Criar Resources/DTOs
4. Implementar autenticação por token
5. Documentar API

### Não Fazer Agora
- ❌ Não instalar Capacitor sem estratégia definida
- ❌ Não começar app nativo sem API pronta
- ❌ Não usar PWA como solução definitiva

### Quando Começar App Mobile
- **Após:** API completa e testada
- **Após:** Documentação de API completa
- **Após:** Estratégia de sincronização definida
- **Após:** Equipe mobile disponível
