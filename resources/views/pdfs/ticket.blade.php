<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bilhete - {{ $ticket->ticket_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
            padding: 15px;
        }
        
        .ticket-container {
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 15px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Header compacto */
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            margin-bottom: 12px;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 2px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 9px;
        }
        
        /* Ticket number e status */
        .ticket-header {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        
        .ticket-number {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }
        
        .ticket-number h2 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 2px;
        }
        
        .ticket-number small {
            color: #999;
            font-size: 8px;
        }
        
        .ticket-status {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-reserved {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        /* Main content - 2 colunas */
        .content {
            display: table;
            width: 100%;
        }
        
        .left-column {
            display: table-cell;
            width: 65%;
            vertical-align: top;
            padding-right: 15px;
        }
        
        .right-column {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
        }
        
        /* Route */
        .route-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .route-cities {
            display: table;
            width: 100%;
        }
        
        .city {
            display: table-cell;
            width: 45%;
            text-align: center;
        }
        
        .arrow {
            display: table-cell;
            width: 10%;
            text-align: center;
            font-size: 14px;
            color: #667eea;
        }
        
        .city-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .city-time {
            font-size: 9px;
            color: #666;
        }
        
        /* Details grid */
        .details-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .detail-row {
            display: table-row;
        }
        
        .detail-cell {
            display: table-cell;
            padding: 5px 8px;
            border: 1px solid #dee2e6;
            background: #fff;
        }
        
        .detail-cell.header {
            background: #f8f9fa;
            font-weight: bold;
            width: 35%;
            font-size: 9px;
            color: #666;
        }
        
        .detail-cell.value {
            width: 65%;
            font-size: 10px;
            color: #333;
        }
        
        /* Passenger info */
        .passenger-box {
            background: #fffbf0;
            border: 1px solid #ffd700;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 10px;
        }
        
        .passenger-box h4 {
            font-size: 10px;
            color: #333;
            margin-bottom: 5px;
            border-bottom: 1px solid #ffd700;
            padding-bottom: 3px;
        }
        
        .passenger-info {
            font-size: 9px;
            line-height: 1.5;
        }
        
        .passenger-info div {
            margin: 2px 0;
        }
        
        .passenger-info strong {
            color: #666;
            display: inline-block;
            min-width: 70px;
        }
        
        /* QR Code section */
        .qr-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }
        
        .qr-box h4 {
            font-size: 9px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .qr-box img {
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .qr-info {
            font-size: 7px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Instructions */
        .instructions {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-left: 3px solid #2196F3;
            border-radius: 4px;
            padding: 8px;
            margin-top: 10px;
        }
        
        .instructions h4 {
            font-size: 9px;
            color: #2196F3;
            margin-bottom: 5px;
        }
        
        .instructions ul {
            margin-left: 15px;
            font-size: 8px;
            line-height: 1.5;
        }
        
        .instructions li {
            margin: 3px 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .footer .company {
            font-weight: bold;
            color: #667eea;
            font-size: 9px;
        }
        
        .footer .contacts {
            margin-top: 3px;
        }
        
        /* Print optimization */
        @media print {
            body {
                padding: 10px;
            }
            .ticket-container {
                border: 2px solid #667eea;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="header">
            <h1>🚌 CityLink e-Ticket</h1>
            <div class="subtitle">Bilhete Electrónico de Viagem</div>
        </div>
        
        <!-- Ticket Number & Status -->
        <div class="ticket-header">
            <div class="ticket-number">
                <h2>{{ $ticket->ticket_number }}</h2>
                <small>Número do Bilhete</small>
            </div>
            <div class="ticket-status">
                <span class="status-badge status-{{ $ticket->status }}">
                    @if($ticket->status === 'paid')
                        ✓ PAGO
                    @elseif($ticket->status === 'reserved')
                        ⏳ RESERVADO
                    @else
                        {{ strtoupper($ticket->status) }}
                    @endif
                </span>
            </div>
        </div>
        
        <!-- Main Content: 2 Columns -->
        <div class="content">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Route -->
                <div class="route-box">
                    <div class="route-cities">
                        <div class="city">
                            <div class="city-name">{{ $route->originCity->name }}</div>
                            <div class="city-time">{{ $schedule->departure_time }}</div>
                        </div>
                        <div class="arrow">→</div>
                        <div class="city">
                            <div class="city-name">{{ $route->destinationCity->name }}</div>
                            <div class="city-time">{{ $schedule->arrival_time }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Details -->
                <div class="details-grid">
                    <div class="detail-row">
                        <div class="detail-cell header">Data</div>
                        <div class="detail-cell value">{{ $schedule->departure_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-cell header">Assento</div>
                        <div class="detail-cell value">{{ $ticket->seat_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-cell header">Autocarro</div>
                        <div class="detail-cell value">{{ $schedule->bus->plate_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-cell header">Preço</div>
                        <div class="detail-cell value">{{ number_format($ticket->price, 2) }} MT</div>
                    </div>
                </div>
                
                <!-- Passenger Info -->
                <div class="passenger-box">
                    <h4>Passageiro</h4>
                    <div class="passenger-info">
                        <div><strong>Nome:</strong> {{ $passenger->first_name }} {{ $passenger->last_name }}</div>
                        <div><strong>Doc:</strong> {{ strtoupper($passenger->document_type) }} {{ $passenger->document_number }}</div>
                        <div><strong>Tel:</strong> {{ $passenger->phone }}</div>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="instructions">
                    <h4>📌 Instruções</h4>
                    <ul>
                        <li>Chegue <strong>30 minutos antes</strong></li>
                        <li>Apresente este bilhete + documento</li>
                        <li>Use o QR Code para validação rápida</li>
                        @if($ticket->status === 'reserved')
                        <li><strong>⚠️ Complete pagamento no terminal</strong></li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- Right Column: QR Code -->
            <div class="right-column">
                @if($ticket->qr_code)
                <div class="qr-box">
                    <h4>QR Code</h4>
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" 
                         alt="QR Code" 
                         width="150" 
                         height="150">
                    <div class="qr-info">
                        Apresente no embarque
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="company">CityLink e-Ticket</div>
            <div class="contacts">
                📞 +258 84 000 0000 | 
                📧 suporte@citylink.co.mz | 
                🌐 www.citylink.co.mz
            </div>
            <div style="margin-top: 4px; font-size: 7px; color: #999;">
                Emitido em {{ now()->format('d/m/Y H:i') }} | Válido com documento de identificação
            </div>
        </div>
    </div>
</body>
</html>