<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Bilhete - {{ $ticket->ticket_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: #667eea;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .ticket-number {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .ticket-number h2 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .ticket-number p {
            color: #666;
            font-size: 14px;
        }
        .route {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .route-city {
            text-align: center;
            flex: 1;
        }
        .route-city h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }
        .route-city p {
            color: #666;
            font-size: 14px;
        }
        .route-arrow {
            font-size: 24px;
            color: #667eea;
            padding: 0 20px;
        }
        .details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .detail-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .detail-item label {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .detail-item strong {
            color: #333;
            font-size: 16px;
        }
        .passenger-info {
            background: #fff9e6;
            border: 2px solid #ffd700;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .passenger-info h4 {
            color: #333;
            margin-bottom: 10px;
        }
        .qr-code {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 200px;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .qr-code p {
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-reserved {
            background: #fff3cd;
            color: #856404;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .instructions {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
        }
        .instructions h4 {
            color: #2196F3;
            margin-bottom: 10px;
        }
        .instructions ul {
            margin-left: 20px;
            color: #333;
        }
        .instructions li {
            margin: 8px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 2px solid #eee;
        }
        .footer p {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
            .route {
                flex-direction: column;
            }
            .route-arrow {
                transform: rotate(90deg);
                margin: 10px 0;
            }
            .details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎫 Seu Bilhete está Pronto!</h1>
            <p>Obrigado por escolher a CityLink</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <p>Olá <strong>{{ $passenger->first_name }} {{ $passenger->last_name }}</strong>,</p>
            <p style="margin-top: 10px;">Sua compra foi confirmada com sucesso! Abaixo estão os detalhes da sua viagem.</p>

            <!-- Ticket Number -->
            <div class="ticket-number">
                <h2>{{ $ticket->ticket_number }}</h2>
                <p>Número do Bilhete</p>
            </div>

            <!-- Status -->
            <div style="text-align: center; margin: 20px 0;">
                <span class="status-badge status-{{ $ticket->status }}">
                    @if($ticket->status === 'paid')
                        ✅ Pago
                    @elseif($ticket->status === 'reserved')
                        ⏳ Reservado
                    @else
                        {{ ucfirst($ticket->status) }}
                    @endif
                </span>
            </div>

            <!-- Route -->
            <div class="route">
                <div class="route-city">
                    <h3>{{ $route->originCity->name }}</h3>
                    <p>{{ $schedule->departure_time }}</p>
                </div>
                <div class="route-arrow">→</div>
                <div class="route-city">
                    <h3>{{ $route->destinationCity->name }}</h3>
                    <p>{{ $schedule->arrival_time }}</p>
                </div>
            </div>

            <!-- Details -->
            <div class="details">
                <div class="detail-item">
                    <label>Data da Viagem</label>
                    <strong>{{ $schedule->departure_date->format('d/m/Y') }}</strong>
                </div>
                <div class="detail-item">
                    <label>Horário de Partida</label>
                    <strong>{{ $schedule->departure_time }}</strong>
                </div>
                <div class="detail-item">
                    <label>Assento</label>
                    <strong>{{ $ticket->seat_number }}</strong>
                </div>
                <div class="detail-item">
                    <label>Autocarro</label>
                    <strong>{{ $schedule->bus->plate_number }}</strong>
                </div>
                <div class="detail-item">
                    <label>Preço</label>
                    <strong>{{ number_format($ticket->price, 2) }} MT</strong>
                </div>
                <div class="detail-item">
                    <label>Método de Pagamento</label>
                    <strong>
                        @if($ticket->status === 'paid')
                            Pago Online
                        @else
                            Pagar no Terminal
                        @endif
                    </strong>
                </div>
            </div>

            <!-- Passenger Info -->
            <div class="passenger-info">
                <h4>📋 Dados do Passageiro</h4>
                <p><strong>Nome:</strong> {{ $passenger->first_name }} {{ $passenger->last_name }}</p>
                <p><strong>Documento:</strong> {{ strtoupper($passenger->document_type) }} - {{ $passenger->document_number }}</p>
                <p><strong>Email:</strong> {{ $passenger->email }}</p>
                <p><strong>Telefone:</strong> {{ $passenger->phone }}</p>
            </div>

            <!-- QR Code -->
            @if($ticket->qr_code)
            <div class="qr-code">
                <h4 style="margin-bottom: 15px;">QR Code do Bilhete</h4>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->qr_code) }}" 
                     alt="QR Code">
                <p><strong>Apresente este código no embarque</strong></p>
            </div>
            @endif

            <!-- Instructions -->
            <div class="instructions">
                <h4>📌 Instruções Importantes</h4>
                <ul>
                    <li>Chegue ao terminal com <strong>30 minutos de antecedência</strong></li>
                    <li>Apresente este bilhete (impresso ou digital) e seu documento de identificação</li>
                    <li>O QR Code acima serve para validação rápida no embarque</li>
                    @if($ticket->status === 'reserved')
                        <li><strong>⚠️ Lembre-se:</strong> Este bilhete está reservado. Complete o pagamento no terminal antes da viagem</li>
                    @endif
                    <li>Em caso de dúvidas, entre em contato conosco</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('public.my-tickets') }}?search_value={{ $passenger->email }}" class="button">
                    Ver Meus Bilhetes
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>CityLink e-Ticket</strong></p>
            <p>Sistema de Bilhetes Electrónicos</p>
            <p style="margin-top: 15px;">
                📞 +258 84 000 0000 | 
                📧 <a href="mailto:suporte@citylink.co.mz">suporte@citylink.co.mz</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                Este é um email automático. Por favor, não responda.
            </p>
        </div>
    </div>
</body>
</html>