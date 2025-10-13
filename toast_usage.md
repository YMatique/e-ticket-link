# 🔔 Guia de Uso - Toast Notifications

## 📦 **Arquivos Criados**

1. ✅ **public/js/toast.js** - Script principal
2. ✅ **Layout atualizado** - Container e scripts adicionados
3. ✅ **Detecção automática** - Mensagens do Laravel aparecem automaticamente

---

## 🚀 **Uso nos Controllers**

### **Método 1: Session Flash Messages (Recomendado)**

```php
// Sucesso
return redirect()->back()->with('success', 'Bilhete emitido com sucesso!');

// Erro
return redirect()->back()->with('error', 'Não foi possível processar o pagamento.');

// Aviso
return redirect()->back()->with('warning', 'Este autocarro está em manutenção.');

// Informação
return redirect()->back()->with('info', 'Nova actualização disponível.');
```

### **Exemplo Completo em Controller**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'passenger_id' => 'required|exists:passengers,id',
                'schedule_id' => 'required|exists:schedules,id',
                'seat_number' => 'required|string',
            ]);

            $ticket = Ticket::create($validated);

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Bilhete emitido com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao emitir bilhete: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Ticket $ticket)
    {
        if (!$ticket->isCancellable()) {
            return redirect()
                ->back()
                ->with('warning', 'Este bilhete não pode ser cancelado.');
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Bilhete cancelado com sucesso!');
    }
}
```

---

## 💻 **Uso no JavaScript**

### **Métodos Disponíveis**

```javascript
// Sucesso
toast.success('Operação realizada com sucesso!');
toast.success('Rota criada!', 'Sucesso');
toast.success('Dados salvos!', 'Concluído', 8000); // 8 segundos

// Erro
toast.error('Falha na operação!');
toast.error('Email inválido!', 'Erro de Validação');

// Aviso
toast.warning('Atenção aos dados!');
toast.warning('Poucos assentos!', 'Aviso');

// Informação
toast.info('Processamento iniciado.');
toast.info('Sistema será reiniciado.', 'Manutenção');

// Personalizado
toast.show('success', 'Mensagem', 'Título', 10000);
```

### **Exemplo com AJAX**

```javascript
// Exemplo: Validar bilhete via AJAX
document.getElementById('validateBtn').addEventListener('click', function() {
    const ticketNumber = document.getElementById('ticketNumber').value;

    fetch(`/api/tickets/${ticketNumber}/check`)
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                toast.success('Bilhete válido!', 'Validação');
            } else {
                toast.error('Bilhete inválido ou já usado!', 'Erro');
            }
        })
        .catch(error => {
            toast.error('Erro ao validar bilhete.', 'Erro de Conexão');
        });
});
```

### **Exemplo com Livewire**

```php
// No componente Livewire
public function save()
{
    $this->validate();

    $this->ticket->save();

    // Disparar evento para mostrar toast
    $this->dispatch('show-toast', [
        'type' => 'success',
        'message' => 'Bilhete salvo com sucesso!',
        'title' => 'Sucesso'
    ]);
}
```

```html
<!-- Na view Livewire -->
<script>
    window.addEventListener('show-toast', event => {
        const data = event.detail[0];
        toast[data.type](data.message, data.title);
    });
</script>
```

---

## 🎨 **Tipos de Toast**

| Tipo | Cor | Ícone | Uso |
|------|-----|-------|-----|
| `success` | Verde | ✓ | Operações bem-sucedidas |
| `error` | Vermelho | ✗ | Erros e falhas |
| `warning` | Amarelo | ⚠ | Avisos e alertas |
| `info` | Azul | ℹ | Informações gerais |

---

## ⚙️ **Configurações**

### **Duração Padrão**

```javascript
// Modificar em public/js/toast.js
success: 5000,  // 5 segundos
error: 7000,    // 7 segundos
warning: 6000,  // 6 segundos
info: 5000      // 5 segundos
```

### **Posição do Container**

```html
<!-- No layout, alterar classes do .toast-container -->

<!-- Top Right (padrão) -->
<div class="toast-container position-fixed top-0 end-0 p-3">

<!-- Top Left -->
<div class="toast-container position-fixed top-0 start-0 p-3">

<!-- Bottom Right -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">

<!-- Bottom Left -->
<div class="toast-container position-fixed bottom-0 start-0 p-3">

<!-- Top Center -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
```

---

## 🧪 **Testar Toasts**

### **Criar Página de Teste**

```php
// routes/web.php
Route::get('/test-toast', function() {
    return view('test-toast');
});
```

```blade
{{-- resources/views/test-toast.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Testar Toast Notifications</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <button onclick="toast.success('Operação bem-sucedida!')" class="btn btn-success w-100">
                    <i class="ph-check-circle me-2"></i>
                    Toast Sucesso
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.error('Algo deu errado!')" class="btn btn-danger w-100">
                    <i class="ph-x-circle me-2"></i>
                    Toast Erro
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.warning('Atenção necessária!')" class="btn btn-warning w-100">
                    <i class="ph-warning-circle me-2"></i>
                    Toast Aviso
                </button>
            </div>
            <div class="col-md-3">
                <button onclick="toast.info('Informação útil!')" class="btn btn-info w-100">
                    <i class="ph-info me-2"></i>
                    Toast Info
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## ✅ **Checklist de Implementação**

- [ ] Criar arquivo `public/js/toast.js`
- [ ] Adicionar script no layout `@stack('scripts')`
- [ ] Adicionar container de toasts no layout
- [ ] Testar com `return redirect()->with('success', 'Teste!')`
- [ ] Testar no JavaScript com `toast.success('Teste!')`
- [ ] Verificar se Bootstrap está carregado

---

## 🐛 **Troubleshooting**

### **Toast não aparece**

1. Verificar se Bootstrap JS está carregado
2. Verificar console do navegador para erros
3. Confirmar que `toast.js` foi carregado
4. Verificar se container existe no DOM

### **Toast aparece mas não desaparece**

- Verificar parâmetro `duration`
- Verificar se `autohide: true` está configurado

### **Múltiplos toasts sobrepondo**

- Normal! Os toasts empilham automaticamente
- Para evitar, adicionar delay entre toasts

---

Pronto! Sistema de toasts completo e funcional! 🎉