<?php

use App\Livewire\Public\AvailableTrips;
use App\Livewire\Public\MyTickets;
use App\Livewire\Public\PassengerInfo;
use App\Livewire\Public\SearchTickets;
use App\Livewire\Public\SeatSelection;
use App\Livewire\Public\Ticketconfirmation;
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
/*
// Página de Ajuda
Route::view('/ajuda', 'public.help')->name('public.help');

// Validar Bilhete (para motoristas/agentes)
Route::view('/validar-bilhete', 'public.validate-ticket')->name('public.validate-ticket');
*/