# Estratégia API Futura para App Mobile
**Projeto:** Ministério Resgate / Família Resgate  
**Data:** 11 de Maio de 2026  
**Versão:** 1.0

---

## 1. Por que a API é Necessária

### 1.1 App Mobile Nativo
O sistema web atual usa Inertia.js + Vue 3, que funciona bem para navegadores web. No entanto, para criar apps nativos iOS e Android, é necessário uma API RESTful porque:
- Inertia.js não funciona em apps nativos
- Vue não funciona em apps nativos
- Roteamento web não funciona em apps nativos
- Apps nativos precisam de endpoints JSON

### 1.2 Benefícios da API
- Acesso offline (com sincronização futura)
- Performance otimizada para mobile
- Experiência nativa melhorada
- Notificações push
- Acesso a câmera e recursos nativos

---

## 2. Recomendação de Estrutura

### 2.1 Versionamento de API
Usar prefixo de versão para permitir evolução sem quebrar clientes:

```
/api/v1/...
```

Exemplo:
- `/api/v1/auth/login`
- `/api/v1/people`
- `/api/v1/families`

### 2.2 Laravel Sanctum para Autenticação
Laravel Sanctum já está instalado no projeto. Para mobile, será usado para:
- Geração de tokens de acesso (API tokens)
- Autenticação stateless para apps móveis
- Revogação de tokens
- Rate limiting por token

**Configuração necessária:**
```php
// config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

### 2.3 Resources/DTOs
Usar Laravel API Resources para transformação de dados:
- Controlar exatamente quais dados são retornados
- Formatar dados consistentemente
- Evitar expor campos sensíveis
- Incluir relacionamentos quando necessário

**Exemplo:**
```php
// app/Http/Resources/PersonResource.php
class PersonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->age,
            // Não incluir campos sensíveis
        ];
    }
}
```

---

## 3. Endpoints Planejados

### 3.1 Autenticação
- `POST /api/v1/auth/login` - Login com email/senha
- `POST /api/v1/auth/logout` - Logout (revoga token)
- `POST /api/v1/auth/refresh` - Refresh token (se implementado)
- `GET /api/v1/auth/me` - Obter dados do usuário atual

### 3.2 Pessoas
- `GET /api/v1/people` - Listar pessoas (com paginação e filtros)
- `GET /api/v1/people/{uuid}` - Obter detalhes de uma pessoa
- `POST /api/v1/people` - Criar nova pessoa (se permitido)
- `PUT /api/v1/people/{uuid}` - Atualizar pessoa (se permitido)

### 3.3 Famílias
- `GET /api/v1/families` - Listar famílias
- `GET /api/v1/families/{uuid}` - Obter detalhes de uma família
- `POST /api/v1/families` - Criar nova família (se permitido)

### 3.4 Perfil
- `GET /api/v1/profile` - Obter perfil do usuário atual
- `PUT /api/v1/profile` - Atualizar perfil do usuário atual
- `POST /api/v1/profile/photo` - Upload de foto de perfil

### 3.5 Departamentos
- `GET /api/v1/departments` - Listar departamentos
- `GET /api/v1/departments/{uuid}` - Obter detalhes de um departamento

### 3.6 Notificações (Futuro)
- `GET /api/v1/notifications` - Listar notificações do usuário
- `PUT /api/v1/notifications/{id}/read` - Marcar notificação como lida

---

## 4. Rate Limiting

### 4.1 Proteção Contra Abuso
Implementar rate limiting para:
- Prevenir ataques de força bruta
- Proteger recursos do servidor
- Garantir disponibilidade para todos

### 4.2 Configuração
```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // Endpoints autenticados
});

Route::middleware('throttle:10,1')->group(function () {
    // Endpoints sensíveis (login)
});
```

---

## 5. Respostas JSON Padronizadas

### 5.1 Formato de Sucesso
```json
{
    "success": true,
    "data": {
        // dados da resposta
    }
}
```

### 5.2 Formato de Erro
```json
{
    "success": false,
    "error": {
        "message": "Descrição do erro",
        "code": "ERROR_CODE",
        "details": {
            // detalhes adicionais
        }
    }
}
```

### 5.3 Validação
```json
{
    "success": false,
    "error": {
        "message": "Validation failed",
        "code": "VALIDATION_ERROR",
        "details": {
            "email": ["The email field is required."]
        }
    }
}
```

---

## 6. Policies na API

### 6.1 Autorização por Recurso
As Policies criadas na Etapa 9 devem ser aplicadas também na API:
- PersonPolicy para endpoints de pessoas
- FamilyPolicy para endpoints de famílias
- UserPolicy para endpoints de usuários
- Etc.

### 6.2 Implementação
```php
// Controller
public function show(Person $person)
{
    $this->authorize('view', $person);
    
    return new PersonResource($person);
}
```

### 6.3 Middleware de Autenticação
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    // Endpoints protegidos
});
```

---

## 7. CORS (Cross-Origin Resource Sharing)

### 7.1 Configuração
Para permitir que apps mobile acessem a API:
```php
// config/cors.php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'], // Em produção, especificar domínios
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### 7.2 Produção
Em produção, especificar apenas domínios permitidos:
```php
'allowed_origins' => [
    'https://app.ministerio-resgate.com',
],
```

---

## 8. Documentação de API

### 8.1 Ferramentas Recomendadas
- **OpenAPI/Swagger** - Documentação padrão
- **Postman Collections** - Para testes
- **Laravel API Documentation** - Pacote para gerar docs automaticamente

### 8.2 Estrutura da Documentação
- Descrição de cada endpoint
- Parâmetros de requisição
- Exemplos de requisição/resposta
- Códigos de erro possíveis
- Requisitos de autenticação

---

## 9. O que Fica para Fase 2

### 9.1 Não Implementar Agora
- Endpoints completos de CRUD para todas as entidades
- Sincronização offline
- Notificações push
- Upload de fotos por câmera
- WebSocket para tempo real
- GraphQL (se desejado no futuro)

### 9.2 Fase 1 (Atual)
- Estrutura básica de API
- Endpoint de health check (já criado)
- Preparação de Sanctum
- Documentação de estratégia

### 9.3 Fase 2 (Futura)
- Endpoints de autenticação
- Endpoints principais (pessoas, famílias)
- Resources/DTOs
- Testes de API
- Documentação completa

---

## 10. Considerações de Segurança

### 10.1 HTTPS
- API deve ser servida apenas via HTTPS em produção
- Certificados SSL/TLS obrigatórios
- Redirecionar HTTP para HTTPS

### 10.2 Headers de Segurança
```php
// Adicionar headers de segurança
'X-Content-Type-Options: nosniff'
'X-Frame-Options: DENY'
'X-XSS-Protection: 1; mode=block'
'Strict-Transport-Security: max-age=31536000; includeSubDomains'
```

### 10.3 Proteção de Dados
- Não retornar campos sensíveis (senhas, tokens)
- Usar Resources para controlar exposição
- Implementar paginação para evitar grandes respostas
- Validar e sanitizar todas as entradas

---

## 11. Testes de API

### 11.1 Tipos de Testes
- Testes de endpoint (Pest ou PHPUnit)
- Testes de autenticação
- Testes de autorização (policies)
- Testes de validação
- Testes de rate limiting

### 11.2 Exemplo
```php
test('user can get their profile', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/profile');
    
    $response->assertStatus(200)
        ->assertJsonPath('data.id', $user->uuid);
});
```

---

## 12. Próximos Passos

1. Revisar e aprovar esta estratégia
2. Configurar Sanctum para API tokens
3. Criar Resources principais
4. Implementar endpoints de autenticação
5. Implementar endpoints principais
6. Adicionar rate limiting
7. Criar testes de API
8. Documentar API com OpenAPI/Swagger
9. Configurar CORS para produção
10. Testar com cliente mobile (Postman ou app prototype)

---

## 13. Revisão

| Data | Versão | Alterações | Autor |
|------|--------|------------|-------|
| 11/05/2026 | 1.0 | Documento inicial | Cascade |

---

**NOTA:** Esta é uma estratégia inicial. A API deve ser desenvolvida iterativamente, começando com endpoints essenciais e expandindo conforme necessidade.
