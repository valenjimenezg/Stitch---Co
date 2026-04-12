<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login-register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => [
                'required', 
                'email:rfc,filter', 
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
                'regex:/^\S*$/'
            ],
            'password' => ['required', 'string', 'not_regex:/\s/'],
        ], [
            'email.regex' => 'El correo es inválido o contiene espacios.',
            'email.email' => 'Debes ingresar un formato de correo electrónico válido.',
            'password.not_regex' => 'La contraseña no puede contener espacios en blanco.'
        ]);

        $throttleKey = \Illuminate\Support\Str::lower($request->input('email')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return back()->withErrors([
                'email' => 'Has excedido el número máximo de intentos permitidos. Por tu seguridad, tu cuenta ha sido bloqueada temporalmente.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return Auth::user()->isAdmin()
                ? redirect()->intended('/admin/dashboard')
                : redirect()->intended('/');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 900); // 15 mins
        
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/acceso');
    }
}
