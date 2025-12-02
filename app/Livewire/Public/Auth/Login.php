<?php

namespace App\Livewire\Public\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Por favor, insira um email válido.',
        'password.required' => 'A palavra-passe é obrigatória.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();

        // ⚠️ IMPORTANTE: usar guard 'account'
        if (Auth::guard('account')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            session()->regenerate();
            session()->flash('success', 'Bem-vindo(a) de volta!');

            return redirect()->intended('/'); // ou /my-tickets
        }

        $this->addError('email', 'Credenciais inválidas. Verifique seu email e palavra-passe.');
    }

    public function render()
    {
        return view('livewire.public.auth.login')->layout('layouts.auth');
    }
}
