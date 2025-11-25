<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TicketPdfController extends Controller
{
    /**
     * Download PDF do bilhete
     */
    public function download($ticketId)
    {
        // Buscar ticket com relacionamentos
        $ticket = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ])->findOrFail($ticketId);

        // Gerar QR Code em base64 para incluir no PDF
        $qrCodeBase64 = $this->generateQrCodeBase64($ticket->qr_code);

        // Gerar PDF
        $pdf = Pdf::loadView('pdfs.ticket', [
            'ticket' => $ticket,
            'passenger' => $ticket->passenger,
            'schedule' => $ticket->schedule,
            'route' => $ticket->schedule->route,
            'qrCodeBase64' => $qrCodeBase64
        ]);

        // Configurações do PDF
        $pdf->setPaper('a4', 'portrait');

        // Nome do arquivo
        $filename = 'bilhete-' . $ticket->ticket_number . '.pdf';

        // Download
        return $pdf->download($filename);
    }

    /**
     * Visualizar PDF no navegador
     */
    public function view($ticketId)
    {
        // Buscar ticket com relacionamentos
        $ticket = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ])->findOrFail($ticketId);

        // Gerar QR Code em base64
        $qrCodeBase64 = $this->generateQrCodeBase64($ticket->qr_code);

        // Gerar PDF
        $pdf = PDF::loadView('pdfs.ticket', [
            'ticket' => $ticket,
            'passenger' => $ticket->passenger,
            'schedule' => $ticket->schedule,
            'route' => $ticket->schedule->route,
            'qrCodeBase64' => $qrCodeBase64
        ]);

        // Configurações
        $pdf->setPaper('a4', 'portrait');

        // Stream (visualizar)
        return $pdf->stream('bilhete-' . $ticket->ticket_number . '.pdf');
    }

    /**
     * Gerar QR Code em Base64 para incluir no PDF
     */
    private function generateQrCodeBase64($qrCodeData)
    {
        if (!$qrCodeData) {
            return '';
        }

        try {
            // Usar API externa para gerar QR Code
            $url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrCodeData);
            
            // Baixar imagem
            $imageData = file_get_contents($url);
            
            if ($imageData === false) {
                \Log::warning('Falha ao gerar QR Code para PDF');
                return '';
            }
            
            // Converter para base64
            return base64_encode($imageData);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar QR Code base64', [
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Download de múltiplos bilhetes em um único PDF
     */
    public function downloadMultiple(Request $request)
    {
        $ticketIds = $request->input('ticket_ids', []);

        if (empty($ticketIds)) {
            abort(400, 'Nenhum bilhete selecionado');
        }

        // Buscar tickets
        $tickets = Ticket::with([
            'passenger',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.bus'
        ])->whereIn('id', $ticketIds)->get();

        if ($tickets->isEmpty()) {
            abort(404, 'Bilhetes não encontrados');
        }

        // Preparar dados para cada ticket
        $ticketsData = $tickets->map(function ($ticket) {
            return [
                'ticket' => $ticket,
                'passenger' => $ticket->passenger,
                'schedule' => $ticket->schedule,
                'route' => $ticket->schedule->route,
                'qrCodeBase64' => $this->generateQrCodeBase64($ticket->qr_code)
            ];
        });

        // Gerar PDF com múltiplos tickets
        $pdf = PDF::loadView('pdfs.tickets-multiple', [
            'ticketsData' => $ticketsData
        ]);

        $pdf->setPaper('a4', 'portrait');

        // Nome do arquivo
        $filename = 'bilhetes-' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }
}
