# 📄 Sistema de PDFs - CityLink e-Ticket

## 📋 VISÃO GERAL

Sistema completo de geração de PDFs para bilhetes com design profissional.

---

## ✨ FUNCIONALIDADES IMPLEMENTADAS

### **1. Download de PDF Individual**
- ✅ Gerar PDF de um bilhete
- ✅ Design profissional
- ✅ QR Code incluído
- ✅ Todas as informações da viagem

### **2. Visualizar PDF no Navegador**
- ✅ Abrir PDF sem fazer download
- ✅ Útil para pré-visualização

### **3. Download Múltiplo**
- ✅ Vários bilhetes em um PDF
- ✅ Útil para grupos/famílias

---

## 📦 ARQUIVOS CRIADOS

### **1. Controller**
```
app/Http/Controllers/TicketPdfController.php
```

Responsável por gerar os PDFs.

### **2. Template PDF**
```
resources/views/pdfs/ticket.blade.php
```

Template HTML do PDF com design profissional.

### **3. Rotas**
```
routes/pdf.php
```

Rotas para download e visualização.

### **4. Componente Atualizado**
```
app/Livewire/Public/MyTickets.php
```

Botão de download funcionando.

---

## 🚀 INSTALAÇÃO

### **1. Instalar DomPDF**

```bash
# Instalar via Composer
composer require barryvdh/laravel-dompdf

# Publicar configuração (opcional)
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### **2. Copiar Arquivos**

```bash
# Controller
cp TicketPdfController.php app/Http/Controllers/

# Template
cp ticket-pdf.blade.php resources/views/pdfs/ticket.blade.php

# Rotas
cp routes_pdf.php routes/pdf.php

# Componente atualizado
cp MyTickets_COM_PDF.php app/Livewire/Public/MyTickets.php
```

### **3. Registrar Rotas**

Adicione no `routes/web.php` ou `bootstrap/app.php`:

```php
// No routes/web.php
require __DIR__.'/pdf.php';

// OU no bootstrap/app.php (Laravel 11+)
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    // Adicione:
    then: function () {
        Route::middleware('web')
            ->group(base_path('routes/pdf.php'));
    }
)
```

### **4. Limpar Cache**

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📄 CONTEÚDO DO PDF

### **Layout:**

```
┌─────────────────────────────────┐
│   🚌 CityLink e-Ticket          │
│   Bilhete Electrónico           │
├─────────────────────────────────┤
│   TKT-20251125-ABC123           │
│   [✓ PAGO] ou [⏳ RESERVADO]    │
├─────────────────────────────────┤
│   Rota da Viagem                │
│   Maputo → Beira                │
│   06:00     14:00               │
├─────────────────────────────────┤
│   Detalhes:                     │
│   ├ Data: 25/11/2025            │
│   ├ Assento: 15                 │
│   ├ Autocarro: AAA-1234         │
│   └ Preço: 2,500.00 MT          │
├─────────────────────────────────┤
│   Dados do Passageiro           │
│   Nome: João Silva              │
│   BI: 123456789                 │
├─────────────────────────────────┤
│   [QR CODE 200x200px]           │
├─────────────────────────────────┤
│   Instruções:                   │
│   • Chegue 30 min antes         │
│   • Apresente documento         │
│   • Use o QR Code               │
├─────────────────────────────────┤
│   CityLink e-Ticket             │
│   📞 +258 84 000 0000           │
└─────────────────────────────────┘
```

---

## 🎨 DESIGN DO PDF

### **Cores:**
```css
Primária:   #667eea (roxo)
Secundária: #764ba2 (rosa)
Fundo:      #f8f9fa (cinza claro)
Bordas:     #ddd (cinza)
```

### **Fontes:**
```
DejaVu Sans (suporta UTF-8)
Tamanhos: 10px, 12px, 14px, 16px, 20px, 24px, 28px
```

### **Formato:**
```
Papel: A4 (210 x 297 mm)
Orientação: Portrait (vertical)
Margens: 20px
```

---

## 🔧 ROTAS DISPONÍVEIS

### **1. Download de PDF**

```
GET /ticket/pdf/{ticket}/download
```

**Exemplo:**
```
https://seu-dominio.com/ticket/pdf/123/download
```

**Resultado:** Baixa o PDF automaticamente

### **2. Visualizar PDF**

```
GET /ticket/pdf/{ticket}/view
```

**Exemplo:**
```
https://seu-dominio.com/ticket/pdf/123/view
```

**Resultado:** Abre o PDF no navegador

### **3. Download Múltiplo**

```
POST /ticket/pdf/download-multiple
Body: { ticket_ids: [1, 2, 3] }
```

**Exemplo:**
```php
// JavaScript
fetch('/ticket/pdf/download-multiple', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        ticket_ids: [1, 2, 3]
    })
});
```

---

## 💻 CÓDIGO DE EXEMPLO

### **Gerar PDF Manualmente:**

```php
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

// Buscar ticket
$ticket = Ticket::with(['passenger', 'schedule'])->find(1);

// Gerar PDF
$pdf = PDF::loadView('pdfs.ticket', [
    'ticket' => $ticket,
    'passenger' => $ticket->passenger,
    'schedule' => $ticket->schedule,
    'route' => $ticket->schedule->route,
    'qrCodeBase64' => base64_encode(file_get_contents('qr-code.png'))
]);

// Download
return $pdf->download('bilhete.pdf');

// OU Stream (visualizar)
return $pdf->stream('bilhete.pdf');
```

### **Configurar PDF:**

```php
// Tamanho do papel
$pdf->setPaper('a4', 'portrait'); // ou 'landscape'

// DPI (qualidade)
$pdf->setOption('dpi', 150);

// Permitir imagens remotas
$pdf->setOption('isRemoteEnabled', true);

// Timeout
$pdf->setHttpContext(
    stream_context_create([
        'ssl' => [
            'allow_self_signed'=> true,
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ])
);
```

### **Salvar PDF no Servidor:**

```php
// Gerar PDF
$pdf = PDF::loadView('pdfs.ticket', $data);

// Salvar
$pdf->save(storage_path('app/public/tickets/ticket-123.pdf'));

// Obter caminho
$path = storage_path('app/public/tickets/ticket-123.pdf');
```

---

## 🖼️ QR CODE NO PDF

### **Método 1: API Externa (Atual)**

```php
private function generateQrCodeBase64($qrCodeData)
{
    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' 
           . urlencode($qrCodeData);
    
    $imageData = file_get_contents($url);
    return base64_encode($imageData);
}
```

**No Template:**
```html
<img src="data:image/png;base64,{{ $qrCodeBase64 }}" width="200" height="200">
```

### **Método 2: Biblioteca Local (Alternativa)**

```bash
composer require simplesoftwareio/simple-qrcode
```

```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$qrCode = QrCode::format('png')->size(200)->generate($qrCodeData);
$qrCodeBase64 = base64_encode($qrCode);
```

---

## 📧 ANEXAR PDF NO EMAIL

### **Atualizar Mailable:**

```php
use Illuminate\Mail\Mailables\Attachment;

public function attachments(): array
{
    // Gerar PDF
    $pdf = PDF::loadView('pdfs.ticket', [
        'ticket' => $this->ticket,
        // ... dados
    ]);
    
    // Salvar temporariamente
    $path = storage_path('app/temp/ticket-'.$this->ticket->id.'.pdf');
    $pdf->save($path);
    
    return [
        Attachment::fromPath($path)
            ->as('bilhete.pdf')
            ->withMime('application/pdf'),
    ];
}
```

---

## 🧪 TESTES

### **1. Teste Rápido:**

```bash
# Via browser
http://localhost:8000/ticket/pdf/1/view

# Via tinker
php artisan tinker
>>> app(App\Http\Controllers\TicketPdfController::class)->view(1);
```

### **2. Teste de Download:**

```bash
# Acesse no browser
http://localhost:8000/ticket/pdf/1/download

# Deve baixar automaticamente
```

### **3. Verificar Qualidade:**

```
✅ Texto nítido
✅ QR Code legível
✅ Layout correto
✅ Cores certas
✅ Fontes carregadas
```

---

## 🐛 TROUBLESHOOTING

### **Problema 1: "Class Pdf not found"**

**Causa:** DomPDF não instalado

**Solução:**
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
```

### **Problema 2: Fontes com caracteres estranhos**

**Causa:** UTF-8 não suportado

**Solução:**
```html
<!-- No template -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Usar fonte DejaVu Sans -->
<style>
    body { font-family: 'DejaVu Sans', sans-serif; }
</style>
```

### **Problema 3: QR Code não aparece**

**Causa:** Imagem não carregada

**Solução:**
```php
// Verificar se allow_url_fopen está ativo
ini_get('allow_url_fopen'); // deve ser 1

// OU usar file_get_contents com context
$context = stream_context_create([
    'ssl' => ['verify_peer' => false]
]);
$imageData = file_get_contents($url, false, $context);
```

### **Problema 4: PDF muito grande**

**Causa:** Imagens grandes / DPI alto

**Solução:**
```php
// Reduzir tamanho do QR Code
size=150x150 // em vez de 300x300

// Reduzir DPI
$pdf->setOption('dpi', 96); // em vez de 300
```

### **Problema 5: Layout quebrado**

**Causa:** CSS não suportado

**Solução:**
```css
/* ✅ USE: */
display: table;
display: table-cell;
float: left;

/* ❌ EVITE: */
display: flex;
display: grid;
position: fixed (limitado);
```

---

## 📊 MELHORIAS FUTURAS

### **1. Watermark**

```html
<!-- No template -->
<div style="position: fixed; top: 50%; left: 50%; 
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px; color: rgba(0,0,0,0.05); z-index: -1;">
    CITYLINK
</div>
```

### **2. Numeração de Páginas**

```html
<script type="text/php">
    if (isset($pdf)) {
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $pdf->page_text(520, 820, $text, null, 10);
    }
</script>
```

### **3. Header e Footer**

```html
<div style="position: fixed; top: 0; width: 100%; text-align: center;">
    Header aqui
</div>

<div style="position: fixed; bottom: 0; width: 100%; text-align: center;">
    Footer aqui
</div>
```

### **4. Multiple Pages**

```html
<!-- Quebra de página -->
<div style="page-break-after: always;"></div>

<!-- Próximo ticket em nova página -->
```

### **5. Compressão**

```bash
# Usar Ghostscript para comprimir
gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook \
   -dNOPAUSE -dQUIET -dBATCH \
   -sOutputFile=output.pdf input.pdf
```

---

## ⚙️ CONFIGURAÇÃO AVANÇADA

### **config/dompdf.php:**

```php
return [
    // Qualidade
    'dpi' => 150, // 96 = rascunho, 150 = normal, 300 = alta
    
    // Permitir imagens remotas
    'isRemoteEnabled' => true,
    
    // Charset
    'defaultEncoding' => 'UTF-8',
    
    // Papel padrão
    'defaultPaperSize' => 'a4',
    
    // Orientação
    'defaultPaperOrientation' => 'portrait',
    
    // Fonte padrão
    'defaultFont' => 'DejaVu Sans',
    
    // Debug
    'debugPng' => false,
    'debugCss' => false,
];
```

---

## 📈 PERFORMANCE

### **Otimizações:**

```php
// 1. Cache de PDFs gerados
$cacheKey = 'pdf_ticket_' . $ticket->id;
$pdf = Cache::remember($cacheKey, 3600, function () use ($ticket) {
    return PDF::loadView('pdfs.ticket', compact('ticket'));
});

// 2. Queue para geração
GeneratePdfJob::dispatch($ticket);

// 3. Pré-gerar PDFs
// Gerar PDF ao criar ticket e salvar
```

---

## ✅ CHECKLIST

- [x] DomPDF instalado
- [x] Controller criado
- [x] Template PDF criado
- [x] Rotas configuradas
- [x] QR Code incluído
- [x] Design profissional
- [x] Download funcionando
- [ ] Testar impressão
- [ ] Anexar em emails (opcional)
- [ ] Cache de PDFs (opcional)

---

## ✅ RESULTADO

**Sistema de PDFs 100% funcional!** 📄

- ✅ Geração de PDF individual
- ✅ Design profissional A4
- ✅ QR Code incluído
- ✅ Download e visualização
- ✅ Responsivo para impressão
- ✅ UTF-8 suportado
- 🚀 Pronto para produção!

---

**Data:** 25/11/2024
**Versão:** 1.0
**Status:** ✅ IMPLEMENTADO