<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\ProvinceController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
     // Dashboard
    Route::view('dashboard', 'admin.dashboard')
        ->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'index'])
    //     ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | GESTÃO DE VIAGENS
    |--------------------------------------------------------------------------
    */
    
    // Rotas (Routes)
    Route::prefix('routes')->name('routes.')->group(function () {
        Route::get('/', [RouteController::class, 'index'])->name('index');
        Route::get('/create', [RouteController::class, 'create'])->name('create');
        Route::post('/', [RouteController::class, 'store'])->name('store');
        Route::get('/{route}', [RouteController::class, 'show'])->name('show');
        Route::get('/{route}/edit', [RouteController::class, 'edit'])->name('edit');
        Route::put('/{route}', [RouteController::class, 'update'])->name('update');
        Route::delete('/{route}', [RouteController::class, 'destroy'])->name('destroy');
        
        // Activar/Desactivar rota
        Route::patch('/{route}/toggle-status', [RouteController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Horários (Schedules)
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/', [ScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}', [ScheduleController::class, 'show'])->name('show');
        Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');
        
        // Acções específicas
        Route::patch('/{schedule}/cancel', [ScheduleController::class, 'cancel'])->name('cancel');
        Route::get('/{schedule}/seats', [ScheduleController::class, 'seats'])->name('seats');
    });

    // Autocarros (Buses)
    Route::prefix('buses')->name('buses.')->group(function () {
        Route::get('/', [BusController::class, 'index'])->name('index');
        Route::get('/create', [BusController::class, 'create'])->name('create');
        Route::post('/', [BusController::class, 'store'])->name('store');
        Route::get('/{bus}', [BusController::class, 'show'])->name('show');
        Route::get('/{bus}/edit', [BusController::class, 'edit'])->name('edit');
        Route::put('/{bus}', [BusController::class, 'update'])->name('update');
        Route::delete('/{bus}', [BusController::class, 'destroy'])->name('destroy');
        
        // Activar/Desactivar autocarro
        Route::patch('/{bus}/toggle-status', [BusController::class, 'toggleStatus'])->name('toggle-status');
        
        // Configuração de assentos
        Route::get('/{bus}/seat-configuration', [BusController::class, 'seatConfiguration'])->name('seat-configuration');
        Route::put('/{bus}/seat-configuration', [BusController::class, 'updateSeatConfiguration'])->name('update-seat-configuration');
    });

       /*
    |--------------------------------------------------------------------------
    | PASSAGEIROS & BILHETES
    |--------------------------------------------------------------------------
    */
    
    // Passageiros (Passengers)
    Route::prefix('passengers')->name('passengers.')->group(function () {
        Route::get('/', [PassengerController::class, 'index'])->name('index');
        Route::get('/create', [PassengerController::class, 'create'])->name('create');
        Route::post('/', [PassengerController::class, 'store'])->name('store');
        Route::get('/{passenger}', [PassengerController::class, 'show'])->name('show');
        Route::get('/{passenger}/edit', [PassengerController::class, 'edit'])->name('edit');
        Route::put('/{passenger}', [PassengerController::class, 'update'])->name('update');
        Route::delete('/{passenger}', [PassengerController::class, 'destroy'])->name('destroy');
        
        // Histórico de viagens
        Route::get('/{passenger}/travel-history', [PassengerController::class, 'travelHistory'])->name('travel-history');
        
        // Activar/Desactivar passageiro
        Route::patch('/{passenger}/toggle-status', [PassengerController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Bilhetes (Tickets)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
        
        // Validação de bilhetes
        Route::get('/validate/scan', [TicketController::class, 'validatePage'])->name('validate');
        Route::post('/validate/scan', [TicketController::class, 'validateTicket'])->name('validate.scan');
        
        // Cancelar bilhete
        Route::patch('/{ticket}/cancel', [TicketController::class, 'cancel'])->name('cancel');
        
        // Imprimir bilhete (PDF)
        Route::get('/{ticket}/print', [TicketController::class, 'print'])->name('print');
        
        // Enviar bilhete por email
        Route::post('/{ticket}/send-email', [TicketController::class, 'sendEmail'])->name('send-email');
        
        // Enviar bilhete por SMS
        Route::post('/{ticket}/send-sms', [TicketController::class, 'sendSms'])->name('send-sms');
    });

    /*
    |--------------------------------------------------------------------------
    | FINANCEIRO
    |--------------------------------------------------------------------------
    */
    
    // Pagamentos (Payments)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        
        // M-Pesa e e-Mola
        Route::get('/mpesa-emola', [PaymentController::class, 'mpesaEmola'])->name('mpesa');
        
        // Processar pagamento
        Route::post('/process', [PaymentController::class, 'processPayment'])->name('process');
        
        // Verificar status de pagamento
        Route::get('/{payment}/check-status', [PaymentController::class, 'checkStatus'])->name('check-status');
        
        // Reembolso
        Route::post('/{payment}/refund', [PaymentController::class, 'refund'])->name('refund');
        
        // Webhook para M-Pesa/e-Mola
        Route::post('/webhook/mpesa', [PaymentController::class, 'mpesaWebhook'])->name('webhook.mpesa');
        Route::post('/webhook/emola', [PaymentController::class, 'emolaWebhook'])->name('webhook.emola');
    });

    // Relatórios (Reports)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // Relatórios específicos
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/routes', [ReportController::class, 'routes'])->name('routes');
        Route::get('/passengers', [ReportController::class, 'passengers'])->name('passengers');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/buses', [ReportController::class, 'buses'])->name('buses');
        
        // Exportar relatórios
        Route::post('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::post('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });

    /*
    |--------------------------------------------------------------------------
    | LOCALIZAÇÃO
    |--------------------------------------------------------------------------
    */
    
    // Províncias (Provinces)
    Route::prefix('provinces')->name('provinces.')->group(function () {
        Route::get('/', [ProvinceController::class, 'index'])->name('index');
        Route::get('/create', [ProvinceController::class, 'create'])->name('create');
        Route::post('/', [ProvinceController::class, 'store'])->name('store');
        Route::get('/{province}', [ProvinceController::class, 'show'])->name('show');
        Route::get('/{province}/edit', [ProvinceController::class, 'edit'])->name('edit');
        Route::put('/{province}', [ProvinceController::class, 'update'])->name('update');
        Route::delete('/{province}', [ProvinceController::class, 'destroy'])->name('destroy');
        
        // API para obter cidades por província
        Route::get('/{province}/cities', [ProvinceController::class, 'getCities'])->name('cities');
    });

    // Cidades (Cities)
    Route::prefix('cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::get('/create', [CityController::class, 'create'])->name('create');
        Route::post('/', [CityController::class, 'store'])->name('store');
        Route::get('/{city}', [CityController::class, 'show'])->name('show');
        Route::get('/{city}/edit', [CityController::class, 'edit'])->name('edit');
        Route::put('/{city}', [CityController::class, 'update'])->name('update');
        Route::delete('/{city}', [CityController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SISTEMA
    |--------------------------------------------------------------------------
    */
    
    // Utilizadores (Users)
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Activar/Desactivar utilizador
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        
        // Alterar permissões
        Route::put('/{user}/permissions', [UserController::class, 'updatePermissions'])->name('permissions');
    });

    // Configurações (Settings)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        
        // Perfil do utilizador
        Route::get('/profile', [SettingController::class, 'profile'])->name('profile');
        Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
        
        // Alterar password
        Route::put('/password', [SettingController::class, 'updatePassword'])->name('password.update');
        
        // Configurações do sistema (apenas admin)
        Route::middleware('role:admin')->group(function () {
            Route::get('/system', [SettingController::class, 'system'])->name('system');
            Route::put('/system', [SettingController::class, 'updateSystem'])->name('system.update');
            
            // Configurações de email
            Route::get('/email', [SettingController::class, 'email'])->name('email');
            Route::put('/email', [SettingController::class, 'updateEmail'])->name('email.update');
            
            // Configurações de SMS
            Route::get('/sms', [SettingController::class, 'sms'])->name('sms');
            Route::put('/sms', [SettingController::class, 'updateSms'])->name('sms.update');
            
            // Configurações de pagamento
            Route::get('/payment-gateways', [SettingController::class, 'paymentGateways'])->name('payment-gateways');
            Route::put('/payment-gateways', [SettingController::class, 'updatePaymentGateways'])->name('payment-gateways.update');
        });
    });
});
    
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
