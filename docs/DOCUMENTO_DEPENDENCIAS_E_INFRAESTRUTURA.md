# Dependências e Infraestrutura - Ministério Resgate

**Data:** 11 de Maio de 2026
**Projeto:** Ministério Resgate / Família Resgate

---

## 1. Pacotes PHP Instalados (composer.json)

### Require (Produção)
```json
{
    "php": "^8.3",
    "inertiajs/inertia-laravel": "^2.0",
    "laravel/framework": "^13.7",
    "laravel/sanctum": "^4.0",
    "laravel/tinker": "^3.0",
    "tightenco/ziggy": "^2.0"
}
```

#### Descrição dos Pacotes

**inertiajs/inertia-laravel: ^2.0**
- **Propósito:** Comunicação entre Laravel e frontend Vue sem API
- **Status:** ✅ Ativo e configurado
- **Uso:** Middleware HandleInertiaRequests, ziggy.js
- **Observação:** Essencial para arquitetura atual, não usar em API mobile

**laravel/framework: ^13.7**
- **Propósito:** Framework principal Laravel
- **Status:** ✅ Ativo (última versão estável)
- **Uso:** Core do sistema
- **Observação:** Manter atualizado

**laravel/sanctum: ^4.0**
- **Propósito:** Autenticação por token para APIs SPA e mobile
- **Status:** ⚠️ Instalado mas não configurado para API
- **Uso:** Atualmente apenas para web, precisa configurar para API mobile
- **Observação:** Essencial para app iOS/Android

**laravel/tinker: ^3.0**
- **Propósito:** Console interativo para Laravel
- **Status:** ✅ Ativo
- **Uso:** `php artisan tinker`
- **Observação:** Ferramenta de desenvolvimento

**tightenco/ziggy: ^2.0**
- **Propósito:** Geração de rotas JavaScript para Inertia
- **Status:** ✅ Ativo e configurado
- **Uso:** ziggy.js gerado automaticamente
- **Observação:** Essencial para Inertia, não usar em API mobile

---

### Require-Dev (Desenvolvimento)
```json
{
    "fakerphp/faker": "^1.23",
    "laravel/breeze": "^2.4",
    "laravel/pail": "^1.2.5",
    "laravel/pao": "^1.0.6",
    "laravel/pint": "^1.27",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "phpunit/phpunit": "^12.5.12"
}
```

#### Descrição dos Pacotes

**fakerphp/faker: ^1.23**
- **Propósito:** Geração de dados falsos para testes
- **Status:** ✅ Ativo
- **Uso:** Factories, Seeders
- **Observação:** Essencial para desenvolvimento e testes

**laravel/breeze: ^2.4**
- **Propósito:** Scaffold de autenticação Laravel
- **Status:** ✅ Ativo (usado para autenticação inicial)
- **Uso:** Login, Register, Password Reset
- **Observação:** Base da autenticação atual

**laravel/pail: ^1.2.5**
- **Propósito:** Logs em tempo real no console
- **Status:** ✅ Ativo
- **Uso:** `php artisan pail`
- **Observação:** Excelente para desenvolvimento

**laravel/pao: ^1.0.6**
- **Propósito:** Otimização de assets
- **Status:** ✅ Ativo
- **Uso:** Otimização de build
- **Observação:** Melhora performance de assets

**laravel/pint: ^1.27**
- **Propósito:** Formatação de código PHP
- **Status:** ✅ Ativo
- **Uso:** `./vendor/bin/pint`
- **Observação:** Mantém código padronizado

**mockery/mockery: ^1.6**
- **Propósito:** Framework de mocking para testes
- **Status:** ✅ Ativo
- **Uso:** Testes PHPUnit
- **Observação:** Essencial para testes

**nunomaduro/collision: ^8.6**
- **Propósito:** Tratamento de erros bonito no console
- **Status:** ✅ Ativo
- **Uso:** Mensagens de erro coloridas
- **Observação:** Melhora experiência de desenvolvimento

**phpunit/phpunit: ^12.5.12**
- **Propósito:** Framework de testes PHP
- **Status:** ✅ Ativo
- **Uso:** `php artisan test`
- **Observação:** Essencial para testes automatizados

---

## 2. Pacotes NPM Instalados (package.json)

### DevDependencies
```json
{
    "@inertiajs/vue3": "^2.0.0",
    "@tailwindcss/forms": "^0.5.3",
    "@tailwindcss/vite": "^4.0.0",
    "@vitejs/plugin-vue": "^6.0.0",
    "autoprefixer": "^10.4.12",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^3.1",
    "postcss": "^8.4.31",
    "tailwindcss": "^3.2.1",
    "vite": "^8.0.0",
    "vue": "^3.4.0"
}
```

#### Descrição dos Pacotes

**@inertiajs/vue3: ^2.0.0**
- **Propósito:** Adaptador Vue para Inertia.js
- **Status:** ✅ Ativo e configurado
- **Uso:** Comunicação Laravel-Vue sem API
- **Observação:** Essencial para arquitetura atual

**@tailwindcss/forms: ^0.5.3**
- **Propósito:** Estilos de formulário Tailwind
- **Status:** ✅ Ativo
- **Uso:** Inputs, selects, checkboxes estilizados
- **Observação:** Melhora UX de formulários

**@tailwindcss/vite: ^4.0.0**
- **Propósito:** Plugin Tailwind para Vite
- **Status:** ✅ Ativo
- **Uso:** Compilação de CSS Tailwind
- **Observação:** Integração Tailwind-Vite

**@vitejs/plugin-vue: ^6.0.0**
- **Propósito:** Suporte Vue no Vite
- **Status:** ✅ Ativo
- **Uso:** Compilação de componentes Vue
- **Observação:** Essencial para Vue + Vite

**autoprefixer: ^10.4.12**
- **Propósito:** Prefixos CSS automáticos
- **Status:** ✅ Ativo
- **Uso:** Compatibilidade cross-browser
- **Observação:** Necessário para Tailwind

**concurrently: ^9.0.1**
- **Propósito:** Execução paralela de scripts
- **Status:** ✅ Ativo
- **Uso:** `composer run dev` (server, queue, logs, vite)
- **Observação:** Melhora desenvolvimento local

**laravel-vite-plugin: ^3.1**
- **Propósito:** Integração Laravel-Vite
- **Status:** ✅ Ativo
- **Uso:** Configuração Vite para Laravel
- **Observação:** Essencial para assets Laravel

**postcss: ^8.4.31**
- **Propósito:** Processamento CSS
- **Status:** ✅ Ativo
- **Uso:** Transformação de CSS
- **Observação:** Necessário para Tailwind

**tailwindcss: ^3.2.1**
- **Propósito:** Framework CSS utility-first
- **Status:** ✅ Ativo e configurado
- **Uso:** Estilos do sistema
- **Observação:** Padrão visual do projeto

**vite: ^8.0.0**
- **Propósito:** Bundler de assets moderno
- **Status:** ✅ Ativo
- **Uso:** Compilação de JS e CSS
- **Observação:** Substituto do Laravel Mix

**vue: ^3.4.0**
- **Propósito:** Framework JavaScript progressivo
- **Status:** ✅ Ativo
- **Uso:** Frontend do sistema
- **Observação:** Versão atual estável

---

## 3. Pacotes Necessários (Não Instalados)

### Para API Mobile
```json
{
    "laravel/sanctum": "^4.0" // Já instalado, precisa configurar
}
```

**Observação:** Sanctum já está instalado, mas precisa ser configurado para API mobile. Não precisa instalar novo pacote.

---

### Para PWA (Se escolher essa rota)
```json
{
    "vite-plugin-pwa": "^0.17.0"
}
```

**Propósito:** Plugin Vite para PWA
**Status:** ❌ Não instalado
**Uso:** Gerar service worker e manifest automaticamente
**Observação:** Não instalar sem estratégia definida

---

### Para Capacitor (Se escolher essa rota)
```json
{
    "@capacitor/android": "^6.0.0",
    "@capacitor/app": "^6.0.0",
    "@capacitor/core": "^6.0.0",
    "@capacitor/haptics": "^6.0.0",
    "@capacitor/ios": "^6.0.0",
    "@capacitor/keyboard": "^6.0.0",
    "@capacitor/status-bar": "^6.0.0"
}
```

**Propósito:** Plugins Capacitor para app híbrido
**Status:** ❌ Não instalado
**Uso:** Empacotar app Vue como app nativo
**Observação:** Não instalar sem estratégia definida

---

### Para Documentação de API
```json
{
    "laravel-apidoc-generator": "^4.8.0"
}
```

**Propósito:** Gerar documentação de API automaticamente
**Status:** ❌ Não instalado
**Uso:** Documentação Swagger/OpenAPI
**Observação:** Recomendado quando API estiver pronta

---

### Para Laravel Reverb (Tempo Real)
```json
{
    "laravel/reverb": "^1.0.0"
}
```

**Propósito:** WebSockets para Laravel
**Status:** ❌ Não instalado
**Uso:** Notificações em tempo real
**Observação:** Não instalar sem necessidade clara

---

### Para Two-Factor Authentication
```json
{
    "laravel/fortify": "^1.0.0"
}
```

**Propósito:** Autenticação com 2FA
**Status:** ❌ Não instalado
**Uso:** Login com código SMS/Authenticator
**Observação:** Opcional, pode instalar depois

---

### Para Rate Limiting Avançado
```json
{
    "laravel/rate-limiter": "^1.0.0"
}
```

**Propósito:** Rate limiting customizado
**Status:** ❌ Não instalado (usando Laravel padrão)
**Uso:** Limitar requisições por endpoint
**Observação:** Laravel padrão pode ser suficiente

---

## 4. Pacotes Opcionais

### Para Analytics
```json
{
    "laravel-analytics": "^1.0.0"
}
```

**Propósito:** Analytics para Laravel
**Status:** ❌ Não instalado
**Uso:** Rastrear uso do sistema
**Observação:** Opcional, pode usar Google Analytics

---

### Para Monitoring
```json
{
    "sentry/sentry-laravel": "^4.0.0"
}
```

**Propósito:** Monitoramento de erros e performance
**Status:** ❌ Não instalado
**Uso:** Rastrear erros em produção
**Observação:** Opcional, Pail é suficiente para desenvolvimento

---

### Para Image Processing
```json
{
    "intervention/image": "^3.0.0"
}
```

**Propósito:** Processamento de imagens
**Status:** ❌ Não instalado
**Uso:** Redimensionamento, crop, otimização
**Observação:** Recomendado para upload de fotos

---

### Para PDF Generation
```json
{
    "barryvdh/laravel-dompdf": "^2.0.0"
}
```

**Propósito:** Geração de PDF
**Status:** ❌ Não instalado
**Uso:** Relatórios em PDF
**Observação:** Opcional, pode instalar depois

---

### Para Excel Import/Export
```json
{
    "maatwebsite/excel": "^3.1.0"
}
```

**Propósito:** Importação/Exportação Excel
**Status:** ❌ Não instalado
**Uso:** Planilhas de dados
**Observação:** Opcional, pode instalar depois

---

### Para Queue Monitoring
```json
{
    "laravel/horizon": "^5.0.0"
}
```

**Propósito:** Monitoramento de filas Redis
**Status:** ❌ Não instalado
**Uso:** Dashboard de filas
**Observação:** Opcional, usa Redis

---

## 5. Comandos Importantes

### Composer (PHP)

#### Instalação
```bash
# Mac
composer install

# Windows
composer install
```

#### Atualização
```bash
# Mac
composer update

# Windows
composer update
```

#### Limpeza de Cache
```bash
# Mac
php artisan optimize:clear

# Windows
php artisan optimize:clear
```

#### Migrações
```bash
# Mac
php artisan migrate

# Windows
php artisan migrate
```

#### Seeder
```bash
# Mac
php artisan db:seed

# Windows
php artisan db:seed
```

#### Tinker (Console)
```bash
# Mac
php artisan tinker

# Windows
php artisan tinker
```

#### Formatação de Código (Pint)
```bash
# Mac
./vendor/bin/pint

# Windows
vendor\bin\pint
```

#### Logs em Tempo Real (Pail)
```bash
# Mac
php artisan pail

# Windows
php artisan pail
```

#### Desenvolvimento Local
```bash
# Mac
composer run dev

# Windows
composer run dev
```

#### Testes
```bash
# Mac
composer run test

# Windows
composer run test
```

#### Conceder Super Admin
```bash
# Mac
php artisan grant:super-admin {email}

# Windows
php artisan grant:super-admin {email}
```

---

### NPM (Node)

#### Instalação
```bash
# Mac
npm install

# Windows
npm install
```

#### Desenvolvimento
```bash
# Mac
npm run dev

# Windows
npm run dev
```

#### Build de Produção
```bash
# Mac
npm run build

# Windows
npm run build
```

#### Atualização
```bash
# Mac
npm update

# Windows
npm update
```

---

### Laravel Artisan

#### Cache
```bash
# Limpar todo cache
php artisan optimize:clear

# Limpar cache específico
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Otimizar cache
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Storage Link
```bash
# Criar link simbólico para storage
php artisan storage:link

# Mac
php artisan storage:link

# Windows (como administrador)
php artisan storage:link
```

#### Queue
```bash
# Iniciar worker de fila
php artisan queue:work

# Iniciar worker com tentativas
php artisan queue:work --tries=3

# Iniciar worker com timeout
php artisan queue:work --timeout=60
```

#### Scheduler
```bash
# Executar scheduler manualmente
php artisan schedule:run

# Verificar próximos agendamentos
php artisan schedule:list
```

#### Ziggy
```bash
# Gerar rotas JavaScript
php artisan ziggy:generate resources/js/ziggy.js
```

#### Key Generate
```bash
# Gerar APP_KEY
php artisan key:generate
```

#### Migrations
```bash
# Executar migrations
php artisan migrate

# Rollback última migration
php artisan migrate:rollback

# Rollback todas migrations
php artisan migrate:reset

# Reset e rodar migrations
php artisan migrate:fresh

# Reset, rodar migrations e seeder
php artisan migrate:fresh --seed
```

#### Testes
```bash
# Executar todos testes
php artisan test

# Executar teste específico
php artisan test --filter TestName

# Executar testes com coverage
php artisan test --coverage
```

#### Routes
```bash
# Listar todas rotas
php artisan route:list

# Listar rotas específicas
php artisan route:list --path=secretaria

# Listar rotas em formato JSON
php artisan route:list --json
```

---

## 6. Observações para Mac

### Homebrew
```bash
# Instalar Homebrew (se não tiver)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Instalar PHP via Homebrew
brew install php

# Instalar Node via Homebrew
brew install node

# Instalar Composer via Homebrew
brew install composer

# Instalar MySQL via Homebrew
brew install mysql

# Iniciar MySQL
brew services start mysql
```

### Permissões
```bash
# Dar permissões ao diretório storage
chmod -R 775 storage bootstrap/cache

# Dar permissões ao diretório storage (mais permissivo se necessário)
chmod -R 777 storage bootstrap/cache
```

### Redis (Opcional)
```bash
# Instalar Redis via Homebrew
brew install redis

# Iniciar Redis
brew services start redis
```

---

## 7. Observações para Windows

### PHP
```bash
# Instalar PHP via XAMPP ou WAMP
# Baixar em: https://www.apachefriends.org/ (XAMPP)
# Ou: https://www.wampserver.com/ (WAMP)
```

### Composer
```bash
# Instalar Composer via installer
# Baixar em: https://getcomposer.org/download/
```

### Node
```bash
# Instalar Node via installer
# Baixar em: https://nodejs.org/
```

### MySQL
```bash
# Já vem com XAMPP ou WAMP
# Ou instalar separadamente
# Baixar em: https://dev.mysql.com/downloads/mysql/
```

### Git
```bash
# Instalar Git via installer
# Baixar em: https://git-scm.com/downloads
```

### Permissões
```bash
# Windows não usa chmod
# Verificar permissões via Explorer > Propriedades > Segurança
```

### Redis (Opcional)
```bash
# Instalar Redis via Chocolatey
choco install redis-64

# Ou baixar binário
# Baixar em: https://github.com/microsoftarchive/redis/releases
```

---

## 8. Configurações de Ambiente

### .env.example
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=pt_BR  # Alterar para português
APP_FALLBACK_LOCALE=pt_BR  # Alterar para português

DB_CONNECTION=mysql  # Alterar para MySQL em produção
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ministerio_resgate
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database  # Usar Redis em produção
CACHE_DRIVER=database  # Usar Redis em produção
SESSION_DRIVER=database

MAIL_MAILER=smtp  # Alterar para SMTP em produção
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### Configurações Recomendadas para Produção
```env
APP_ENV=production
APP_DEBUG=false

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

DB_CONNECTION=mysql
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
```

---

## 9. Serviços de Produção

### Banco de Dados
- **Local:** SQLite (desenvolvimento)
- **Produção:** MySQL ou PostgreSQL recomendado
- **Cloud:** AWS RDS, DigitalOcean Managed Database, ou similar

### Cache
- **Local:** Database (desenvolvimento)
- **Produção:** Redis recomendado
- **Cloud:** AWS ElastiCache, DigitalOcean Redis, ou similar

### Queue
- **Local:** Database (desenvolvimento)
- **Produção:** Redis recomendado
- **Cloud:** AWS ElastiCache, DigitalOcean Redis, ou similar

### Storage
- **Local:** Local (desenvolvimento)
- **Produção:** S3 ou similar recomendado
- **Cloud:** AWS S3, DigitalOcean Spaces, ou similar

### Mail
- **Local:** Log (desenvolvimento)
- **Produção:** SMTP ou API recomendado
- **Cloud:** AWS SES, SendGrid, Mailgun, ou similar

### Logs
- **Local:** Single file (desenvolvimento)
- **Produção:** Daily ou Stack recomendado
- **Cloud:** AWS CloudWatch, ou similar

---

## 10. Dependências de Sistema

### Requisitos Mínimos
- PHP >= 8.3
- Composer >= 2.0
- Node.js >= 18.0
- NPM >= 9.0
- MySQL >= 8.0 ou PostgreSQL >= 14
- Redis >= 6.0 (opcional mas recomendado)
- Git

### Extensões PHP Necessárias
- bcmath
- ctype
- cURL
- dom
- fileinfo
- json
- mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
- Zip

---

## 11. Resumo

### Pacotes PHP Atuais: 6 (produção) + 8 (desenvolvimento)
### Pacotes NPM Atuais: 11 (todos devDependencies)

### Pacotes Recomendados para API Mobile
- Sanctum (já instalado, precisa configurar)
- intervention/image (para processamento de imagens)
- laravel-apidoc-generator (para documentação)

### Pacotes Recomendados para PWA
- vite-plugin-pwa (apenas se escolher PWA)

### Pacotes Recomendados para Capacitor
- Capacitor core e plugins (apenas se escolher Capacitor)

### Pacotes Opcionais
- laralytics (analytics)
- sentry (monitoring)
- barryvdh/laravel-dompdf (PDF)
- maatwebsite/excel (Excel)
- laravel/horizon (monitoramento de filas)

### Próximos Passos
1. Configurar Sanctum para API
2. Instalar intervention/image para upload de fotos
3. Instalar laravel-apidoc-generator quando API estiver pronta
4. Não instalar Capacitor ou PWA sem estratégia definida
