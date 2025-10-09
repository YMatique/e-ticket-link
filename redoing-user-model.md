# Modelo de Usuários Redesenhado - Sistema CityLink

## 1. Estratégia de Separação

### 🎯 Arquitetura Escolhida
- **Users**: Funcionários internos da CityLink (admin, operadores, validadores)
- **Passengers**: Clientes que compram bilhetes
- **Justificativa**: Separação clara entre sistema interno e público

## 2. Models Principais

### 👔 **User** (Funcionários Internos)
```
Tabela: users
```
- **Campos**:
  - id
  - employee_code (unique)
  - name
  - email (unique)
  - phone
  - password
  - role (enum: 'admin', 'manager', 'operator', 'validator', 'accountant')
  - department (enum: 'operations', 'finance', 'it', 'management')
  - hire_date
  - is_active (boolean)
  - permissions (JSON) // permissões granulares além do role
  - two_factor_secret (nullable)
  - two_factor_enabled (boolean)
  - last_login_at
  - last_login_ip
  - password_changed_at
  - must_change_password (boolean)
  - email_verified_at
  - remember_token
  - created_at
  - updated_at

- **Relações**:
  - hasMany(AuditLog)
  - hasMany(TicketValidation)
  - hasMany(Schedule) // horários criados
  - belongsToMany(Terminal) // terminais onde pode operar

- **Métodos**:
  - hasPermission($permission)
  - hasRole($role)
  - canAccessModule($module)
  - isAdmin()
  - isOperator()
  - requiresPasswordChange()
  - logActivity($action)

### 👥 **Passenger** (Clientes/Passageiros)
```
Tabela: passengers
```
- **Campos**:
  - id
  - first_name
  - last_name
  - email (unique, nullable)
  - phone (unique)
  - phone_verified_at
  - email_verified_at
  - password
  - document_type (enum: 'bi', 'passport', 'dire', 'outros')
  - document_number
  - date_of_birth
  - gender (enum: 'M', 'F', 'other', null)
  - address
  - city_id (FK)
  - preferred_language (enum: 'pt', 'en')
  - notification_preferences (JSON)
  - is_active (boolean)
  - last_login_at
  - created_at
  - updated_at

- **Relações**:
  - hasMany(Ticket)
  - hasMany(PassengerActivity)
  - belongsTo(City)
  - hasMany(SavedPaymentMethod)

- **Métodos**:
  - getFullName()
  - getAge()
  - canPurchaseTicket()
  - getTravelHistory()
  - getUpcomingTrips()

### 🔐 **UserSession** (Sessões de Funcionários)
```
Tabela: user_sessions
```
- **Campos**:
  - id
  - user_id (FK)
  - token (unique)
  - ip_address
  - user_agent
  - terminal_id (FK, nullable)
  - last_activity
  - expires_at
  - created_at

### 📝 **PassengerActivity** (Atividades dos Passageiros)
```
Tabela: passenger_activities
```
- **Campos**:
  - id
  - passenger_id (FK)
  - activity_type (enum: 'login', 'search', 'booking', 'payment', 'cancellation')
  - description
  - metadata (JSON)
  - ip_address
  - created_at

### 📊 **AuditLog** (Auditoria de Funcionários)
```
Tabela: audit_logs
```
- **Campos**:
  - id
  - user_id (FK)
  - action
  - module
  - entity_type
  - entity_id
  - old_values (JSON)
  - new_values (JSON)
  - ip_address
  - terminal_id (FK, nullable)
  - created_at

### 💳 **SavedPaymentMethod** (Métodos de Pagamento Salvos)
```
Tabela: saved_payment_methods
```
- **Campos**:
  - id
  - passenger_id (FK)
  - type (enum: 'mpesa', 'emola')
  - identifier (phone number)
  - nickname
  - is_default (boolean)
  - is_active (boolean)
  - last_used_at
  - created_at
  - updated_at

### 🖥️ **Terminal** (Bilheteiras/Pontos de Venda)
```
Tabela: terminals
```
- **Campos**:
  - id
  - code
  - name
  - location
  - city_id (FK)
  - type (enum: 'main_station', 'agency', 'kiosk', 'mobile')
  - is_active
  - created_at
  - updated_at

### 🔗 **UserTerminal** (Associação User-Terminal)
```
Tabela: user_terminal (pivot)
```
- **Campos**:
  - user_id (FK)
  - terminal_id (FK)
  - assigned_at
  - assigned_by (FK to users)

## 3. Autenticação Segregada no Laravel

### Guards Configurados
```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'passengers',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'passengers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Passenger::class,
    ],
],
```

## 4. Middleware Específico

### AdminAuth
```php
- Verifica auth('admin')->check()
- Valida is_active
- Verifica must_change_password
- Registra em audit_log
```

### PassengerAuth
```php
- Verifica auth('web')->check()
- Valida is_active
- Verifica phone_verified_at se necessário
```

### Role
```php
- Verifica user->hasRole($role)
- Usado após AdminAuth
```

### Permission
```php
- Verifica user->hasPermission($permission)
- Controle granular de acesso
```

## 5. Rotas Segregadas

```php
// routes/web.php - Área Pública
Route::prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/search', [SearchController::class, 'index']);
    // ... rotas de passageiros
});

// routes/admin.php - Área Administrativa
Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('routes', RouteController::class);
    Route::resource('schedules', ScheduleController::class);
    // ... rotas administrativas
});
```

## 6. Models Relacionados Atualizados

### Ticket
```php
class Ticket extends Model
{
    // Relações
    public function passenger() {
        return $this->belongsTo(Passenger::class);
    }
    
    public function validatedBy() {
        return $this->belongsTo(User::class, 'validated_by_user_id');
    }
}
```

### Schedule
```php
class Schedule extends Model
{
    // Relações
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
    
    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
```

### Payment
```php
class Payment extends Model
{
    // Relações
    public function processedBy() {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }
}
```

## 7. Componentes Livewire Organizados

### Para Passageiros (namespace: App\Http\Livewire\Passenger)
- `Registration` - Registro de novo passageiro
- `Login` - Login de passageiro
- `Profile` - Gestão de perfil
- `BookingHistory` - Histórico de viagens
- `SearchTrips` - Pesquisa de viagens
- `SeatSelection` - Seleção de lugares
- `Checkout` - Processo de pagamento

### Para Funcionários (namespace: App\Http\Livewire\Admin)
- `Login` - Login com 2FA opcional
- `Dashboard` - Painel principal
- `RouteManager` - Gestão de rotas
- `ScheduleManager` - Gestão de horários
- `BusManager` - Gestão de frota
- `TicketValidator` - Validação de bilhetes
- `Reports` - Relatórios e análises

## 8. Políticas de Segurança

### Para Users (Funcionários)
- Senha forte obrigatória (min 10 chars, maiúsculas, números, símbolos)
- Troca de senha a cada 90 dias
- 2FA obrigatório para role 'admin'
- Timeout de sessão: 15 minutos
- Máximo 3 tentativas de login
- Logs de todas as ações

### Para Passengers (Clientes)
- Senha simples (min 6 caracteres)
- Verificação de telefone obrigatória
- Timeout de sessão: 30 minutos
- Máximo 5 tentativas de login
- Recuperação por SMS/Email

## 9. Estrutura de Diretórios Atualizada

```
app/
├── Models/
│   ├── User.php              // Funcionários
│   ├── Passenger.php          // Clientes
│   ├── Ticket.php
│   ├── Terminal.php
│   └── ...
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   └── ...
│   │   └── Passenger/
│   │       ├── BookingController.php
│   │       └── ...
│   ├── Livewire/
│   │   ├── Admin/
│   │   │   ├── Login.php
│   │   │   ├── Dashboard.php
│   │   │   └── ...
│   │   └── Passenger/
│   │       ├── Registration.php
│   │       ├── SearchTrips.php
│   │       └── ...
│   └── Middleware/
│       ├── AdminAuth.php
│       ├── PassengerAuth.php
│       └── ...

resources/
├── views/
│   ├── admin/
│   │   ├── layouts/
│   │   └── ...
│   ├── passenger/
│   │   ├── layouts/
│   │   └── ...
│   └── livewire/
│       ├── admin/
│       └── passenger/
```

## 10. Vantagens desta Abordagem

✅ **Separação Clara**: Sistema interno vs público completamente isolados

✅ **Segurança**: Políticas específicas para cada tipo de usuário

✅ **Manutenibilidade**: Código organizado por domínio

✅ **Escalabilidade**: Fácil separar em microsserviços futuramente

✅ **Compliance**: Dados de clientes isolados (LGPD/GDPR)

✅ **Performance**: Queries otimizadas para cada contexto

Esta arquitetura mantém `Users` para funcionários (como você preferiu) e `Passengers` para clientes, criando uma separação limpa e segura entre os dois sistemas.