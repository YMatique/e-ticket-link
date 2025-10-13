# Arquitetura Focada - Sistema CityLink e-Ticket

## 1. MODELS ESSENCIAIS

### 👤 **Autenticação e Usuários**

#### **User** (Funcionários)
```
Tabela: users
```
- **Campos:**
  - id, name, email, password
  - role (enum: 'admin', 'operator')
  - is_active, email_verified_at
  - created_at, updated_at

- **Relações:**
  - hasMany(Schedule, 'created_by')
  - hasMany(Ticket, 'validated_by')

#### **Passenger** (Clientes)
```
Tabela: passengers
```
- **Campos:**
  - id, first_name, last_name, email, phone, password
  - document_type, document_number
  - is_active, phone_verified_at
  - created_at, updated_at

- **Relações:**
  - hasMany(Ticket)
  - belongsTo(City)

### 📍 **Localização**

#### **Province**
```
Tabela: provinces
```
- **Campos:** id, name, code
- **Relações:** hasMany(City)

#### **City**
```
Tabela: cities
```
- **Campos:** id, name, province_id
- **Relações:** belongsTo(Province)

### 🚌 **Operações**

#### **Route**
```
Tabela: routes
```
- **Campos:**
  - id, origin_city_id, destination_city_id
  - distance_km, estimated_duration_minutes
  - is_active

- **Relações:**
  - belongsTo(City, 'origin')
  - belongsTo(City, 'destination')
  - hasMany(Schedule)

#### **Bus**
```
Tabela: buses
```
- **Campos:**
  - id, registration_number, model
  - total_seats, seat_configuration (JSON)
  - is_active

- **Relações:**
  - hasMany(Schedule)

#### **Schedule**
```
Tabela: schedules
```
- **Campos:**
  - id, route_id, bus_id
  - departure_date, departure_time
  - price, status (enum: 'active', 'full', 'departed', 'cancelled')
  - created_by_user_id

- **Relações:**
  - belongsTo(Route)
  - belongsTo(Bus)
  - hasMany(Ticket)
  - belongsTo(User, 'created_by')

### 🎫 **Bilhetagem**

#### **Ticket**
```
Tabela: tickets
```
- **Campos:**
  - id, ticket_number (unique)
  - passenger_id, schedule_id, seat_number
  - price, status (enum: 'reserved', 'paid', 'validated', 'cancelled')
  - qr_code, validated_at, validated_by_user_id
  - created_at, updated_at

- **Relações:**
  - belongsTo(Passenger)
  - belongsTo(Schedule)
  - hasOne(Payment)
  - belongsTo(User, 'validated_by')

#### **Payment**
```
Tabela: payments
```
- **Campos:**
  - id, ticket_id, transaction_reference
  - amount, payment_method (enum: 'mpesa', 'emola')
  - status (enum: 'pending', 'completed', 'failed')
  - gateway_response (JSON)
  - paid_at

- **Relações:**
  - belongsTo(Ticket)

#### **TemporaryReservation**
```
Tabela: temporary_reservations
```
- **Campos:**
  - id, session_id, schedule_id
  - seat_number, expires_at
  - created_at

- **Relações:**
  - belongsTo(Schedule)

---

## 2. COMPONENTES LIVEWIRE FOCADOS

### 🌐 **Área Pública (Passageiros)**

#### **SearchTrips**
- Busca origem, destino e data
- Lista horários disponíveis

#### **SeatSelection**
- Visualiza mapa de assentos
- Seleciona lugar específico
- Cria reserva temporária

#### **Checkout**
- Resumo da compra
- Integração M-Pesa/e-Mola
- Processa pagamento

#### **TicketView**
- Exibe bilhete com QR Code
- Download PDF
- Envio por email

#### **MyTickets**
- Lista bilhetes comprados
- Reimpressão

#### **PassengerAuth**
- Login/Registro
- Recuperação de senha

### 👔 **Área Administrativa**

#### **AdminDashboard**
- Total vendas do dia/mês
- Ocupação por rota
- Receitas

#### **RouteManagement**
- Cadastro de rotas
- Ativar/desativar

#### **BusManagement**
- Cadastro de ônibus
- Configuração de assentos

#### **ScheduleManagement**
- Criar horários
- Editar preços
- Cancelar viagens

#### **TicketValidator**
- Leitura QR Code
- Validação manual
- Lista de passageiros

#### **SalesReport**
- Relatório de vendas
- Export Excel/PDF

---

## 3. SERVICES ESSENCIAIS

### **PaymentService**
```php
- initiateMPesaPayment($amount, $phone)
- processWebhook($data)
- confirmPayment($reference)
```

### **TicketService**
```php
- generateTicket($passengerId, $scheduleId, $seatNumber)
- generateQRCode($ticket)
- validateTicket($qrCode)
- generatePDF($ticket)
```

### **ReservationService**
```php
- createTemporaryReservation($sessionId, $scheduleId, $seatNumber)
- checkAvailability($scheduleId, $seatNumber)
- cleanExpiredReservations()
```

### **NotificationService**
```php
- sendTicketEmail($ticket)
- sendSMS($phone, $message)
```

---

## 4. JOBS ESSENCIAIS

### **CleanExpiredReservations**
- Frequência: Cada 5 minutos
- Remove reservas temporárias expiradas

### **ProcessPaymentWebhook**
- Processa confirmação de pagamento
- Gera bilhete

### **SendTicketEmail**
- Envia email com bilhete
- Queue com retry

---

## 5. ROTAS SIMPLIFICADAS

### **Web Routes (Público)**
```php
// Home e Pesquisa
Route::get('/', [HomeController::class, 'index']);
Route::livewire('/search', 'search-trips');

// Área do Passageiro
Route::middleware(['passenger'])->group(function () {
    Route::livewire('/booking/seats/{schedule}', 'seat-selection');
    Route::livewire('/checkout', 'checkout');
    Route::livewire('/my-tickets', 'my-tickets');
});
```

### **Admin Routes**
```php
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::livewire('/dashboard', 'admin.dashboard');
    Route::livewire('/routes', 'admin.route-management');
    Route::livewire('/buses', 'admin.bus-management');
    Route::livewire('/schedules', 'admin.schedule-management');
    Route::livewire('/validate', 'admin.ticket-validator');
    Route::livewire('/reports', 'admin.sales-report');
});
```

### **API Routes**
```php
// Webhooks
Route::post('/webhook/mpesa', [WebhookController::class, 'mpesa']);
Route::post('/webhook/emola', [WebhookController::class, 'emola']);
```

---

## 6. FLUXO PRINCIPAL DE COMPRA

```
1. PESQUISA
   └── Passageiro busca viagem (origem, destino, data)

2. SELEÇÃO
   ├── Escolhe horário
   └── Seleciona assento

3. RESERVA TEMPORÁRIA
   └── Sistema bloqueia assento por 10 minutos

4. CHECKOUT
   ├── Dados do passageiro
   └── Escolha de pagamento (M-Pesa/e-Mola)

5. PAGAMENTO
   ├── Integração com gateway
   └── Aguarda confirmação via webhook

6. EMISSÃO
   ├── Gera bilhete com QR Code
   ├── Envia por email
   └── Libera para download

7. VALIDAÇÃO
   └── QR Code lido no embarque
```

---

## 7. CONFIGURAÇÃO MÍNIMA

### **.env Essencial**
```env
# Database
DB_DATABASE=citylink
DB_USERNAME=root
DB_PASSWORD=

# Pagamento
MPESA_API_KEY=
MPESA_PUBLIC_KEY=
EMOLA_API_KEY=

# Email
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=

# Queue
QUEUE_CONNECTION=database
```

---

## 8. SEGURANÇA BÁSICA

- HTTPS obrigatório
- Bcrypt para senhas
- CSRF protection
- Rate limiting
- Validação de inputs
- Sanitização de outputs

---

## 9. BANCO DE DADOS SIMPLIFICADO

```sql
Tabelas Principais (11 apenas):
- users
- passengers
- provinces
- cities
- routes
- buses
- schedules
- tickets
- payments
- temporary_reservations
- migrations
```

---

## 10. ESTRUTURA DE DIRETÓRIOS LIMPA

```
app/
├── Models/          # 10 models
├── Http/
│   ├── Livewire/
│   │   ├── SearchTrips.php
│   │   ├── SeatSelection.php
│   │   ├── Checkout.php
│   │   ├── Admin/
│   │   │   ├── Dashboard.php
│   │   │   ├── ScheduleManagement.php
│   │   │   └── TicketValidator.php
│   └── Controllers/
│       └── WebhookController.php
├── Services/        # 4 services
└── Jobs/           # 3 jobs

resources/views/
├── livewire/       # componentes
├── layouts/        # templates
└── pdf/           # template do bilhete
```

---

## RESUMO DA ARQUITETURA FOCADA

✅ **Foco Principal**: Venda de bilhetes online
✅ **Models**: Apenas 10 essenciais
✅ **Componentes**: 12 Livewire focados
✅ **Services**: 4 principais
✅ **Jobs**: 3 essenciais
✅ **Integrações**: M-Pesa e e-Mola
✅ **Segurança**: Implementações básicas mas sólidas

**Removido**:
- Gestão de motoristas
- Manutenção de frota
- Audit logs complexos
- BusTypes desnecessários
- PassengerActivity tracking
- Múltiplos departamentos
- Permissões granulares
- 2FA (pode adicionar depois)

Esta arquitetura é **enxuta, focada e funcional** - perfeita para MVP e para o escopo acadêmico do projeto!
