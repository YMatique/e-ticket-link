# Arquitetura do Sistema CityLink e-Ticket com Livewire

## 1. Models (Eloquent ORM)

### Models Principais

#### User
- **Campos**: id, name, email, password, phone, role (enum: passenger, operator, admin), email_verified_at
- **Relações**: hasMany(Ticket), hasMany(AuditLog)
- **Métodos**: isAdmin(), isOperator(), canManageSchedules()

#### Province
- **Campos**: id, name, code
- **Relações**: hasMany(City)

#### City
- **Campos**: id, name, province_id
- **Relações**: belongsTo(Province), hasMany(Route-origin), hasMany(Route-destination)

#### Route
- **Campos**: id, origin_city_id, destination_city_id, distance_km, duration_minutes, is_active
- **Relações**: belongsTo(City-origin), belongsTo(City-destination), hasMany(Schedule)
- **Métodos**: getFormattedDuration(), isAvailable()

#### Bus
- **Campos**: id, registration_number, model, manufacturer, capacity, seat_configuration (JSON), is_active
- **Relações**: hasMany(Seat), hasMany(Schedule)
- **Métodos**: getAvailableSeats(), getSeatMap()

#### Seat
- **Campos**: id, bus_id, seat_number, row, column, seat_class (enum: standard, premium), is_active
- **Relações**: belongsTo(Bus), hasMany(Ticket)

#### Schedule
- **Campos**: id, route_id, bus_id, departure_date, departure_time, arrival_time, base_price, status (enum: scheduled, boarding, departed, arrived, cancelled)
- **Relações**: belongsTo(Route), belongsTo(Bus), hasMany(Ticket)
- **Métodos**: getAvailableSeatsCount(), isBookable(), calculateArrivalTime()

#### Ticket
- **Campos**: id, ticket_number (unique), user_id, schedule_id, seat_id, price, status (enum: reserved, paid, validated, cancelled), qr_code, validated_at, expires_at
- **Relações**: belongsTo(User), belongsTo(Schedule), belongsTo(Seat), hasOne(Payment)
- **Métodos**: generateQRCode(), validate(), cancel(), isExpired()

#### Payment
- **Campos**: id, ticket_id, transaction_reference, amount, payment_method (enum: mpesa, emola, cash), status (enum: pending, processing, completed, failed), gateway_response (JSON), paid_at
- **Relações**: belongsTo(Ticket)
- **Métodos**: process(), confirm(), refund()

#### TemporaryReservation
- **Campos**: id, session_id, seat_id, schedule_id, expires_at
- **Relações**: belongsTo(Seat), belongsTo(Schedule)
- **Métodos**: extend(), release(), isExpired()

#### AuditLog
- **Campos**: id, user_id, action, model_type, model_id, old_values (JSON), new_values (JSON), ip_address, user_agent
- **Relações**: belongsTo(User)

## 2. Componentes Livewire

### Componentes Públicos (Passageiros)

#### SearchTrips
- **Propriedades**: originCity, destinationCity, travelDate, searchResults
- **Métodos**: search(), resetSearch()
- **Validações**: datas futuras, cidades diferentes
- **Eventos**: emite 'tripSelected' quando usuário escolhe horário

#### SeatSelection
- **Propriedades**: schedule, seats, selectedSeat, temporaryReservation
- **Métodos**: selectSeat(), confirmSelection(), releaseReservation()
- **Real-time**: atualiza disponibilidade via polling
- **Eventos**: emite 'seatSelected' com dados do lugar

#### BookingCheckout
- **Propriedades**: ticket, paymentMethod, phoneNumber, agreementAccepted
- **Métodos**: initiatePayment(), processPayment(), handlePaymentCallback()
- **Integrações**: M-Pesa API, e-Mola API
- **Eventos**: escuta 'paymentConfirmed' do webhook

#### TicketDisplay
- **Propriedades**: ticket, qrCode
- **Métodos**: downloadPDF(), sendEmail(), generateQR()
- **Features**: geração dinâmica de QR Code e PDF

#### UserProfile
- **Propriedades**: user, tickets, upcomingTrips
- **Métodos**: updateProfile(), changePassword(), viewTicketHistory()
- **Paginação**: histórico de bilhetes com infinite scroll

### Componentes Administrativos

#### AdminDashboard
- **Propriedades**: salesStats, occupancyStats, revenueStats, period
- **Métodos**: refreshStats(), exportReport(), changePeriod()
- **Charts**: integração com Chart.js para visualizações
- **Real-time**: atualização automática a cada 30 segundos

#### RouteManager
- **Propriedades**: routes, cities, searchTerm
- **Métodos**: createRoute(), editRoute(), toggleRouteStatus()
- **Validações**: rotas duplicadas, distâncias válidas
- **Features**: bulk operations, filtros avançados

#### BusManager
- **Propriedades**: buses, seatConfigurations
- **Métodos**: addBus(), configureSeatLayout(), deactivateBus()
- **Features**: editor visual de configuração de assentos

#### ScheduleManager
- **Propriedades**: schedules, routes, buses, filters
- **Métodos**: createSchedule(), editSchedule(), cancelSchedule(), duplicateSchedule()
- **Validações**: conflitos de horário, disponibilidade de ônibus
- **Features**: calendário visual, criação em lote

#### TicketValidator
- **Propriedades**: scannedCode, ticket, validationResult
- **Métodos**: scanQRCode(), manualValidation(), checkTicket()
- **Real-time**: feedback instantâneo de validação
- **Features**: modo offline com sincronização posterior

#### ReportGenerator
- **Propriedades**: reportType, dateRange, filters, reportData
- **Métodos**: generateReport(), exportPDF(), exportExcel(), scheduleReport()
- **Features**: relatórios customizáveis, agendamento

#### PaymentMonitor
- **Propriedades**: pendingPayments, failedPayments, successfulPayments
- **Métodos**: retryPayment(), manualConfirmation(), issueRefund()
- **Real-time**: webhook listener para atualizações

## 3. Serviços (Service Layer)

### PaymentService
- **Métodos**: 
  - initiateMPesaPayment()
  - initiateEMolaPayment()
  - verifyPaymentStatus()
  - processRefund()
  - handleWebhook()

### TicketService
- **Métodos**:
  - generateTicketNumber()
  - createQRCode()
  - generatePDF()
  - sendTicketEmail()
  - validateTicket()

### ReservationService
- **Métodos**:
  - createTemporaryReservation()
  - convertToPermanent()
  - cleanExpiredReservations()
  - checkSeatAvailability()

### NotificationService
- **Métodos**:
  - sendSMS()
  - sendEmail()
  - sendPushNotification()
  - queueBulkNotifications()

### ReportService
- **Métodos**:
  - generateSalesReport()
  - generateOccupancyReport()
  - generateRevenueReport()
  - exportToExcel()

## 4. Middleware

### AuthenticationMiddleware
- Verifica autenticação do usuário
- Redireciona para login se necessário

### RoleMiddleware
- Verifica permissões baseadas em papel
- Bloqueia acesso não autorizado

### LocalizationMiddleware
- Define idioma baseado em preferências
- Suporte para português e inglês

### MaintenanceMiddleware
- Modo de manutenção com exceções
- Página de manutenção customizada

### ThrottleMiddleware
- Rate limiting para APIs
- Prevenção de ataques brute force

## 5. Jobs (Background Tasks)

### CleanExpiredReservations
- **Frequência**: A cada 5 minutos
- **Ação**: Remove reservas temporárias expiradas

### ProcessPaymentConfirmation
- **Trigger**: Webhook de pagamento
- **Ação**: Confirma pagamento e emite bilhete

### SendTicketEmail
- **Trigger**: Após pagamento confirmado
- **Ação**: Envia email com PDF do bilhete

### GenerateDailyReports
- **Frequência**: Diariamente às 23h
- **Ação**: Gera e envia relatórios aos administradores

### SyncPaymentStatus
- **Frequência**: A cada 10 minutos
- **Ação**: Verifica status de pagamentos pendentes

### BackupDatabase
- **Frequência**: Diariamente às 3h
- **Ação**: Backup completo da base de dados

## 6. Eventos e Listeners

### Eventos
- TicketPurchased
- PaymentConfirmed
- SeatReserved
- TicketValidated
- ScheduleCancelled

### Listeners
- SendTicketConfirmation
- UpdateSeatAvailability
- LogUserActivity
- NotifyAdminOfCancellation
- UpdateStatistics

## 7. Estrutura de Diretórios

```
app/
├── Http/
│   ├── Livewire/
│   │   ├── Public/
│   │   │   ├── SearchTrips.php
│   │   │   ├── SeatSelection.php
│   │   │   ├── BookingCheckout.php
│   │   │   └── TicketDisplay.php
│   │   ├── Admin/
│   │   │   ├── Dashboard.php
│   │   │   ├── RouteManager.php
│   │   │   ├── BusManager.php
│   │   │   └── ScheduleManager.php
│   │   └── Operator/
│   │       └── TicketValidator.php
│   ├── Middleware/
│   └── Controllers/
│       └── WebhookController.php
├── Models/
├── Services/
├── Jobs/
├── Events/
├── Listeners/
└── Policies/

resources/
├── views/
│   ├── livewire/
│   │   ├── public/
│   │   ├── admin/
│   │   └── operator/
│   └── layouts/
│       ├── app.blade.php
│       ├── admin.blade.php
│       └── guest.blade.php
```

## 8. Configurações e Integrações

### Integrações Externas
- **M-Pesa API**: Configuração em config/mpesa.php
- **e-Mola API**: Configuração em config/emola.php
- **Mail Service**: Configuração SMTP em .env
- **SMS Gateway**: Integração com provedor local

### Cache Strategy
- **Sessions**: Redis/Database
- **Cache**: Redis para dados frequentes
- **Queue**: Database/Redis para jobs

### Security
- **CSRF**: Habilitado globalmente
- **XSS**: Blade escaping automático
- **SQL Injection**: Eloquent ORM
- **Rate Limiting**: 60 requests/minute

## 9. Fluxos Principais

### Fluxo de Compra
1. SearchTrips → pesquisa viagens
2. SeatSelection → escolhe lugar
3. TemporaryReservation → reserva por 10 min
4. BookingCheckout → dados e pagamento
5. PaymentService → processa pagamento
6. TicketService → gera bilhete
7. NotificationService → envia confirmação

### Fluxo de Validação
1. TicketValidator → lê QR Code
2. TicketService → verifica autenticidade
3. Ticket Model → marca como validado
4. AuditLog → registra operação

## 10. Considerações de Performance

- **Eager Loading**: Prevenir N+1 queries
- **Indexação**: Índices em campos de busca
- **Paginação**: Limitar resultados
- **Cache**: Dados estáticos e consultas frequentes
- **Queue**: Operações pesadas em background
- **CDN**: Assets estáticos
- **Compressão**: Gzip para responses

Esta arquitetura com Livewire proporciona:
- Desenvolvimento rápido e manutenção simplificada
- Lógica centralizada no servidor (mais seguro)
- Experiência reativa sem complexidade de SPA
- Melhor performance em conexões lentas
- Stack único (PHP/Laravel)