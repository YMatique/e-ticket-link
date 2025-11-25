# 🔒 QR Code Seguro com Hash HMAC

## 📋 VISÃO GERAL

Sistema de QR Code **anti-falsificação** usando hash HMAC SHA256.

---

## 🔐 COMO FUNCIONA?

### **Estrutura do QR Code:**

```
BASE64( NUMERO_BILHETE | TIMESTAMP | HASH_HMAC )
```

### **Exemplo Real:**

```
1. Dados originais:
   - Número: TKT-20251125-ABC123
   - Timestamp: 1764058740
   - Hash: a1b2c3d4e5f6... (64 caracteres)

2. Concatenado:
   TKT-20251125-ABC123|1764058740|a1b2c3d4e5f6...

3. Base64:
   VEtULTIwMjUxMTI1LUFCQ...
   
4. Isso vai para o banco de dados e para o QR Code visual
```

---

## 🛡️ SEGURANÇA

### ✅ **O que PREVINE:**

1. **Falsificação de QR Code**
   - Não pode criar QR Code falso sem a chave secreta (`APP_KEY`)
   - Hash garante autenticidade

2. **Modificação de Dados**
   - Alterar número do bilhete invalida o hash
   - Alterar timestamp invalida o hash

3. **Reutilização Maliciosa**
   - Timestamp registra quando foi criado
   - Pode implementar expiração

### ⚠️ **O que NÃO previne (mas é OK):**

- Alguém tirar foto do QR Code válido
  - **Solução:** Validar só uma vez (campo `validated_at`)
  
- Scanner offline
  - **Solução:** App precisa de internet (por enquanto)

---

## 💻 IMPLEMENTAÇÃO

### **1. Geração do QR Code**

```php
// app/Livewire/Public/PassengerInfo.php

private function generateQrCode($ticketNumber)
{
    $timestamp = now()->timestamp;
    $data = $ticketNumber . '|' . $timestamp;
    
    // Hash HMAC com chave secreta
    $hash = hash_hmac('sha256', $data, config('app.key'));
    
    // Formato: TICKET|TIMESTAMP|HASH
    $fullData = $data . '|' . $hash;
    
    return base64_encode($fullData);
}
```

**Quando é gerado:**
- Ao criar o bilhete (depois de confirmar pagamento)
- Uma única vez por bilhete
- Armazenado no campo `tickets.qr_code`

---

### **2. Validação do QR Code**

```php
// app/Livewire/Public/ValidateTicket.php

private function validateQrCode($qrCode)
{
    try {
        // 1. Decodificar Base64
        $decoded = base64_decode($qrCode, true);
        
        if ($decoded === false) {
            return false; // Não é Base64 válido
        }
        
        // 2. Separar componentes
        $parts = explode('|', $decoded);
        
        if (count($parts) !== 3) {
            return false; // Formato inválido
        }
        
        list($ticketNumber, $timestamp, $hash) = $parts;
        
        // 3. Verificar hash (CRÍTICO!)
        $expectedHash = hash_hmac(
            'sha256', 
            $ticketNumber . '|' . $timestamp, 
            config('app.key')
        );
        
        if (!hash_equals($expectedHash, $hash)) {
            \Log::warning('QR Code falsificado detectado!');
            return false; // HASH INVÁLIDO = FALSIFICADO!
        }
        
        // 4. Tudo OK!
        return [
            'ticket_number' => $ticketNumber,
            'timestamp' => (int) $timestamp,
            'valid' => true
        ];
        
    } catch (\Exception $e) {
        return false;
    }
}
```

**Quando é validado:**
- No embarque (ValidateTicket)
- Antes de marcar como `validated`
- Log de tentativas de falsificação

---

## 🔄 FLUXO COMPLETO

### **Compra do Bilhete:**

```
1. Cliente preenche dados
   ↓
2. Confirma pagamento
   ↓
3. Sistema cria Ticket
   ↓
4. generateQrCode() gera:
   - Número: TKT-20251125-ABC123
   - Timestamp: 1764058740
   - Hash: a1b2c3... (calculado com APP_KEY)
   ↓
5. Base64: VEtULTIwMjUxMTI1...
   ↓
6. Salva no banco: tickets.qr_code
   ↓
7. Cliente recebe bilhete com QR Code
```

### **Validação no Embarque:**

```
1. Motorista escaneia QR Code
   ↓
2. Sistema recebe: VEtULTIwMjUxMTI1...
   ↓
3. validateQrCode() decodifica:
   - Base64 → String
   - String → TICKET|TIMESTAMP|HASH
   ↓
4. Recalcula hash com APP_KEY
   ↓
5. Compara hashes:
   - ✅ Iguais = VÁLIDO
   - ❌ Diferentes = FALSIFICADO
   ↓
6. Se válido, busca ticket no banco
   ↓
7. Verifica status/data/pagamento
   ↓
8. Permite embarque e marca como validated
```

---

## 📊 EXEMPLO PRÁTICO

### **Bilhete Real:**

```php
// Criação
$ticket = Ticket::create([
    'ticket_number' => 'TKT-20251125-ABC123',
    'passenger_id' => 1,
    'schedule_id' => 5,
    'seat_number' => '15',
    'price' => 2500.00,
    'status' => 'reserved',
]);

// Gerar QR Code
$qrCode = $this->generateQrCode($ticket->ticket_number);
// Resultado: "VEtULTIwMjUxMTI1LUFCQ..."

// Atualizar ticket
$ticket->update(['qr_code' => $qrCode]);
```

### **Validação:**

```php
// Escanear
$scanned = "VEtULTIwMjUxMTI1LUFCQ...";

// Validar
$result = $this->validateQrCode($scanned);

if ($result) {
    // QR Code válido!
    echo "Bilhete: " . $result['ticket_number'];
    echo "Criado em: " . date('Y-m-d H:i:s', $result['timestamp']);
    
    // Buscar no banco
    $ticket = Ticket::where('ticket_number', $result['ticket_number'])->first();
    
} else {
    // QR Code inválido ou falsificado
    echo "ERRO: QR Code inválido!";
}
```

---

## 🧪 TESTE DE SEGURANÇA

### **Tentativa de Falsificação:**

```php
// ❌ TENTATIVA 1: Modificar número do bilhete
$fake = base64_encode("TKT-FAKE-123|1764058740|a1b2c3d4...");
validateQrCode($fake); // FALSE - Hash não bate!

// ❌ TENTATIVA 2: Copiar hash de outro bilhete
$stolen = "TKT-20251125-ABC123|1764058740|HASH_DE_OUTRO_BILHETE";
validateQrCode(base64_encode($stolen)); // FALSE - Hash não bate!

// ❌ TENTATIVA 3: Gerar próprio hash sem APP_KEY
$myHash = hash_hmac('sha256', "TKT-FAKE|123", "wrong-key");
$fake = base64_encode("TKT-FAKE|123|" . $myHash);
validateQrCode($fake); // FALSE - APP_KEY errada!

// ✅ ÚNICO JEITO: Ter acesso ao APP_KEY (impossível!)
```

---

## 🔧 CONFIGURAÇÃO

### **Requisitos:**

1. ✅ `APP_KEY` definida no `.env`
   ```env
   APP_KEY=base64:your-secret-key-here
   ```

2. ✅ PHP 7.0+ (hash_hmac disponível)

3. ✅ PHP 5.6+ (hash_equals para comparação segura)

### **Verificar APP_KEY:**

```bash
# Ver APP_KEY atual
php artisan tinker
>>> config('app.key');

# Gerar nova (cuidado! Invalida QR Codes antigos)
php artisan key:generate
```

⚠️ **IMPORTANTE:** 
- Nunca mude `APP_KEY` em produção!
- Se mudar, todos os QR Codes antigos ficam inválidos!

---

## 📈 VANTAGENS vs DESVANTAGENS

### ✅ **Vantagens:**

| Aspecto | Benefício |
|---------|-----------|
| **Segurança** | Não pode ser falsificado sem APP_KEY |
| **Performance** | Rápido (hash local, sem DB) |
| **Offline** | Validação pode ser offline (depois implementar) |
| **Auditoria** | Timestamp registra quando foi criado |
| **Simples** | Não precisa biblioteca extra |

### ⚠️ **Limitações:**

| Aspecto | Como Resolver |
|---------|---------------|
| **Foto do QR** | Validar só uma vez (`validated_at`) ✅ Já tem! |
| **QR Longo** | Base64 deixa grande, mas QR Code suporta ✅ OK |
| **APP_KEY vazada** | Manter `.env` seguro, não commitar ✅ |

---

## 📱 EXIBINDO O QR CODE

### **Opção 1: API Externa (Atual)**

```blade
<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->qr_code) }}" 
     alt="QR Code">
```

✅ Funciona
✅ Não precisa instalar nada
❌ Depende de internet

### **Opção 2: Biblioteca Local (Recomendado depois)**

```bash
composer require simplesoftwareio/simple-qrcode
```

```blade
{!! QrCode::size(200)->generate($ticket->qr_code) !!}
```

✅ Offline
✅ Mais rápido
✅ Personalizável

---

## 🚀 PRÓXIMOS PASSOS (OPCIONAL)

### **Melhorias Futuras:**

1. **Expiração de QR Code**
   ```php
   // Verificar se QR Code é muito antigo
   $maxAge = 30 * 24 * 60 * 60; // 30 dias
   if (time() - $timestamp > $maxAge) {
       return false; // QR Code expirado
   }
   ```

2. **QR Code Dinâmico**
   - Gerar novo QR Code a cada validação
   - Impossível reutilizar foto

3. **Scanner com Câmera**
   - Usar biblioteca JavaScript
   - Scanner nativo no browser

4. **Validação Offline**
   - App mobile com cache
   - Sincroniza depois

---

## 📋 CHECKLIST

- [x] Geração de QR Code com hash
- [x] Validação com verificação de hash
- [x] Log de tentativas de falsificação
- [x] Compatível com busca por número
- [x] Timestamp incluído
- [ ] Biblioteca QR Code local (opcional)
- [ ] Expiração de QR Code (opcional)
- [ ] Scanner com câmera (futuro)

---

## ✅ RESULTADO FINAL

**QR Code SEGURO implementado!** 🎉

- 🔒 **Anti-falsificação:** Hash HMAC SHA256
- ⏱️ **Timestamp:** Registra quando foi criado
- ✅ **Validação:** Verifica autenticidade
- 📝 **Log:** Registra tentativas de fraude
- 🚀 **Pronto para produção!**

---

**Data:** 25/11/2024
**Versão:** 2.0 (Seguro)
**Status:** ✅ IMPLEMENTADO