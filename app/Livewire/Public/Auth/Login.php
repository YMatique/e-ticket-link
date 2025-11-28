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

    public function login()
    {
        $this->validate();

        // ⚠️ IMPORTANTE: usar guard 'account'
        if (Auth::guard('account')->attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            
            session()->regenerate();
            
            return redirect()->intended('/'); // ou /my-tickets
        }

        $this->addError('email', 'Credenciais inválidas.');
    }
    public function render()
    {
        return view('livewire.public.auth.login')->layout('layouts.auth');;
    }
}
