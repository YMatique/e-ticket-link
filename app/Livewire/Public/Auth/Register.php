<?php

namespace App\Livewire\Public\Auth;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
     public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $accept_terms;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:accounts,email',
        'phone' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
        'accept_terms' => 'accepted',
    ];

      protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais de 255 caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Por favor, insira um email válido.',
        'email.unique' => 'Este email já está registado.',
        'password.required' => 'A palavra-passe é obrigatória.',
        'password.min' => 'A palavra-passe deve ter no mínimo 8 caracteres.',
        'password.confirmed' => 'As palavras-passe não coincidem.',
        'accept_terms.accepted' => 'Você deve aceitar os Termos e Condições.',
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function register()
    {
        $this->validate();

        // Criar Account
        $account = Account::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);

        // Login automático com guard 'account'
        Auth::guard('account')->login($account);

        session()->flash('success', 'Conta criada com sucesso!');

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.public.auth.register') ->layout('layouts.auth');
    }
}
