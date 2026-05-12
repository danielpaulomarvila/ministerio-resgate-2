# Ministério Resgate

Sistema interno em Laravel + Inertia + Vue para gestão do Ministério Resgate.

## Módulos atuais

- Secretaria: pessoas, famílias, responsáveis, alertas, solicitações e acessos
- Centro Família / área logada
- Departamentos e perfis de acesso

## Requisitos

- PHP 8.3+
- Composer
- Node.js 22+
- Banco compatível com Laravel

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install --ignore-scripts
```

## Desenvolvimento

```bash
composer dev
```

## Validações principais

```bash
composer test
npm run build
```

## Seed inicial

```bash
php artisan db:seed
```

O seed principal cria:

- perfis e permissões base
- departamentos iniciais
- usuário administrador inicial

## Observações

- O usuário administrador inicial é configurado pelo `CreateAdminUserSeeder`
- Defina `ADMIN_DEFAULT_PASSWORD` no `.env` antes de rodar seed em ambiente real
