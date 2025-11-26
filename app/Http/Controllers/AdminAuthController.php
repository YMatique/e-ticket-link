<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AdminAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.auth.admin-login');
    }

    /**
     * Processar login administrativo
     */
    public function login(Request $request)
    {
        // Verificar rate limiting
        $this->checkTooManyFailedAttempts($request);

        // Validar dados
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');

        // Tentar autenticar
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // ⚠️ CRÍTICO: Verificar se é super admin
            // if (!$user->is_super_admin) {
            //     Auth::logout();
            //     RateLimiter::hit($this->throttleKey($request));

            //     throw ValidationException::withMessages([
            //         'email' => 'Credenciais inválidas para acesso administrativo.',
            //     ]);
            // }

            // Limpar rate limiting
            RateLimiter::clear($this->throttleKey($request));

            // Regenerar sessão
            $request->session()->regenerate();

            // Atualizar informações do usuário
            $user->update([
                'last_login_at' => now(),
                'last_activity_at' => now(),
                'login_ip' => $request->ip(),
            ]);

            // Log da atividade
            Log::info('Admin login realizado', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString()
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Bem-vindo de volta, {$user->name}!");
        }

        // Login falhou
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => 'As credenciais fornecidas não coincidem com nossos registros.',
        ]);
    }

    /**
     * Exibir formulário de registo de administrador
     */
    public function showRegisterForm()
    {
        return view('admin.auth.admin-register');
    }

    /**
     * Processar registo de novo administrador
     * NOTA: Apenas super admins podem criar outros admins
     */
    public function register(Request $request)
    {
        // Verificar se usuário atual é super admin (para criar outro admin)
        if (Auth::check() && !Auth::user()->is_super_admin) {
            abort(403, 'Acesso negado. Apenas super administradores podem criar novos administradores.');
        }

        // Validar dados
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Criar novo administrador
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_super_admin' => true, // ⚠️ Definir como super admin
            'email_verified_at' => now(), // Admins não precisam verificar email
        ]);

        // Log da criação
        Log::info('Novo administrador criado', [
            'created_by' => Auth::id(),
            'created_by_name' => Auth::user()?->name,
            'new_admin_id' => $user->id,
            'new_admin_name' => $user->name,
            'new_admin_email' => $user->email,
            'ip' => $request->ip(),
        ]);

        // Se quem criou está autenticado (admin criando outro admin)
        if (Auth::check()) {
            return redirect()->route('admin.dashboard')
                ->with('success', "Administrador {$user->name} criado com sucesso!");
        }

        // Se é o primeiro admin sendo criado (sem autenticação)
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Conta administrativa criada com sucesso!');
    }

    /**
     * Logout administrativo
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log do logout
        Log::info('Admin logout realizado', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'ip' => $request->ip(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Verificar rate limiting
     */
    protected function checkTooManyFailedAttempts(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
        ]);
    }

    /**
     * Obter chave de throttle
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    /**
     * Exibir formulário de recuperação de password
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.admin-forgot-password');
    }

    /**
     * Exibir formulário de reset de password
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('admin.auth.admin-reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }
}
