<?php

use App\Livewire\Public\AvailableTrips;
use App\Livewire\Public\MyTickets;
use App\Livewire\Public\PassengerInfo;
use App\Livewire\Public\SearchTickets;
use App\Livewire\Public\SeatSelection;
use App\Livewire\Public\Ticketconfirmation;
use App\Livewire\Public\ValidateTicket;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS - ÁREA DE COMPRA DE BILHETES
|--------------------------------------------------------------------------
| 
| Estas rotas são acessíveis sem autenticação e permitem que qualquer
| pessoa possa buscar, visualizar e comprar bilhetes de autocarro online.
|
*/

// Homepage - Busca de Bilhetes
Route::get('/', SearchTickets::class)->name('public.home');

// Resultados de Viagens Disponíveis
Route::get('/viagens', AvailableTrips::class)->name('public.trips');

// Seleção de Assentos
Route::get('/assentos/{schedule}', SeatSelection::class)->name('public.seats');

// Informações do Passageiro e Pagamento
Route::get('/checkout/{schedule}', PassengerInfo::class)->name('public.checkout');
// Route::get('/checkout/{schedule}/{seat}', PassengerInfo::class)->name('public.checkout');
Route::get('/confirmacao', Ticketconfirmation::class)->name('public.ticket-confirmation');
// Meus Bilhetes (permite buscar por número de bilhete ou email)
Route::get('/meus-bilhetes', MyTickets::class)->name('public.my-tickets');

// Página de Ajuda
Route::view('/ajuda', 'livewire.public.help')->name('public.help');

// Validar Bilhete (para motoristas/agentes)
Route::get('/validar-bilhete', ValidateTicket::class)->name('public.validate-ticket');


Route::middleware(['auth.account'])->group(function () {
    // Meus bilhetes
    Route::get('/my-tickets', [MyTicketsController::class, 'index'])->name('my-tickets');
    
    // Perfil
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile');
    
    // Processo de compra (Livewire)
    // Estas rotas já devem existir
    Route::get('/booking/passenger-info/{schedule}', PassengerInfo::class)->name('booking.passenger-info');
    // Route::get('/booking/payment', [BookingController::class, 'payment'])->name('booking.payment');
});