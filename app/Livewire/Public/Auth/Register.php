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

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:accounts,email',
        'phone' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ];

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
