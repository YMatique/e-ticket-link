<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação Administrativa
|--------------------------------------------------------------------------
|
| Rotas exclusivas para autenticação de administradores do sistema.
| Apenas usuários com is_super_admin = true podem acessar.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Rotas Públicas (Guest)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {
        
        // Login
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])
            ->name('login');
        
        Route::post('login', [AdminAuthController::class, 'login'])
            ->name('login.submit');
        
        // Registo (apenas para criar o primeiro admin)
        // Em produção, pode querer desabilitar esta rota após criar o primeiro admin
        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])
            ->name('register');
        
        Route::post('register', [AdminAuthController::class, 'register'])
            ->name('register.submit');
        
        // Recuperação de Password
        Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])
            ->name('password.request');
        
        Route::post('forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        
        Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])
            ->name('password.reset');
        
        Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])
            ->name('password.update');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Rotas Protegidas (Authenticated + AdminMiddleware)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        
        // Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])
            ->name('logout');
        
        // Dashboard
        // Route::get('/', function() {
        //     return view('admin.dashboard.index');
        // })->name('dashboard');
        
        // Route::get('dashboard', function() {
        //     return view('admin.dashboard.index');
        // })->name('dashboard.index');
        
        /*
        |--------------------------------------------------------------------------
        | Adicione aqui outras rotas administrativas protegidas
        |--------------------------------------------------------------------------
        | Exemplos:
        | - Gestão de empresas
        | - Gestão de usuários
        | - Gestão de rotas
        | - Gestão de autocarros
        | - Relatórios
        | - Configurações
        */
        
        // Gestão de Administradores (criar novos admins)
        Route::prefix('administrators')->name('administrators.')->group(function () {
            Route::get('/', function() {
                // Listar admins
            })->name('index');
            
            Route::get('create', [AdminAuthController::class, 'showRegisterForm'])
                ->name('create');
            
            Route::post('store', [AdminAuthController::class, 'register'])
                ->name('store');
        });
    });
});


