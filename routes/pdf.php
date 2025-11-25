<?php

use App\Http\Controllers\TicketPdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PDF Routes
|--------------------------------------------------------------------------
|
| Rotas para geração e download de PDFs de bilhetes
|
*/

Route::prefix('ticket/pdf')->name('ticket.pdf.')->group(function () {
    
    // Download de PDF individual
    Route::get('/{ticket}/download', [TicketPdfController::class, 'download'])
        ->name('download');
    
    // Visualizar PDF no navegador
    Route::get('/{ticket}/view', [TicketPdfController::class, 'view'])
        ->name('view');
    
    // Download de múltiplos PDFs
    Route::post('/download-multiple', [TicketPdfController::class, 'downloadMultiple'])
        ->name('download-multiple');
});