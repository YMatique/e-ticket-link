<?php

namespace App\Livewire\Public;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    // Informações Pessoais
    public $name = '';
    public $email = '';
    public $phone = '';

    // Alterar Senha
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    // Estados
    public $editingInfo = false;
    public $changingPassword = false;

    public function mount()
    {
        $account = Auth::guard('account')->user();
        
        $this->name = $account->name;
        $this->email = $account->email;
        $this->phone = $account->phone ?? '';
    }
    // ==========================================
    // ATUALIZAR INFORMAÇÕES PESSOAIS
    // ==========================================

    public function editInfo()
    {
        $this->editingInfo = true;
    }

    public function cancelEditInfo()
    {
        $account = Auth::guard('account')->user();
        
        $this->name = $account->name;
        $this->email = $account->email;
        $this->phone = $account->phone ?? '';
        
        $this->editingInfo = false;
        $this->resetValidation();
    }

    public function updateInfo()
    {
        $account = Auth::guard('account')->user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $account->id,
            'phone' => 'nullable|string',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'email.unique' => 'Este email já está em uso.',
        ]);

        $account->update($validated);

        $this->editingInfo = false;

        session()->flash('success', 'Informações atualizadas com sucesso!');
    }

    // ==========================================
    // ALTERAR SENHA
    // ==========================================

    public function toggleChangePassword()
    {
        $this->changingPassword = !$this->changingPassword;
        
        if (!$this->changingPassword) {
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
            $this->resetValidation();
        }
    }

    public function updatePassword()
    {
        $account = Auth::guard('account')->user();

        $validated = $this->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
            'new_password.confirmed' => 'As senhas não coincidem.',
        ]);

        // Verificar senha atual
        if (!Hash::check($this->current_password, $account->password)) {
            $this->addError('current_password', 'A senha atual está incorreta.');
            return;
        }

        // Atualizar senha
        $account->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->changingPassword = false;

        session()->flash('success', 'Senha alterada com sucesso!');
    }

    // ==========================================
    // ESTATÍSTICAS DA CONTA
    // ==========================================

    public function getStats()
    {
        $account = Auth::guard('account')->user();

        return [
            'total_tickets' => $account->getTotalTickets(),
            'active_tickets' => $account->getActiveTickets(),
            'total_passengers' => $account->passengers()->count(),
            'member_since' => $account->created_at->format('F Y'),
        ];
    }
    public function render()
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return redirect()->route('account.login');
        }

        $stats = $this->getStats();
        return view('livewire.public.profile', [
            'account' => $account,
            'stats' => $stats,
        ])->layout('layouts.passenger');
    }
}
