<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function identify(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'No encontramos una cuenta con ese correo electrónico.'
        ]);

        $user = User::where('email', $request->email)->first();
        
        session(['recovery_email' => $user->email]);
        
        return redirect()->route('password.selection');
    }

    public function showSelectionForm()
    {
        if (!session('recovery_email')) {
            return redirect()->route('password.request');
        }

        $email = session('recovery_email');

        // Ofuscar correo: muestra solo el primer carácter + asteriscos + @dominio
        // Ejemplo: valengomezb@gmail.com → v*********@gmail.com
        $parts = explode('@', $email);
        $local = $parts[0];
        $domain = $parts[1];
        $maskedEmail = $local[0] . str_repeat('*', max(strlen($local) - 1, 4)) . '@' . $domain;
        
        return view('auth.select-method', compact('maskedEmail'));
    }

    public function sendCode(Request $request)
    {
        if (!session('recovery_email')) {
            return redirect()->route('password.request');
        }

        $method = $request->input('method', 'email');
        
        // Generate a 6-digit code (hardcoded to 123456 for testing)
        $code = '123456'; // str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store in session (expires when session ends)
        session(['recovery_code' => $code]);
        session(['recovery_method' => $method]);
        
        // For development/testing: Log the code
        Log::info("PASSWORD_RECOVERY_CODE for " . session('recovery_email') . ": " . $code);
        
        // In a real scenario, dispatch email/SMS job here depending on $method

        return redirect()->route('password.verify_form');
    }

    public function showVerifyForm()
    {
        if (!session('recovery_email') || !session('recovery_code')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ], [
            'code.required' => 'Debes introducir un código para continuar.',
            'code.size' => 'El código debe tener exactamente 6 dígitos.'
        ]);

        if (!session('recovery_email') || !session('recovery_code')) {
            return redirect()->route('password.request');
        }

        if ($request->code === session('recovery_code')) {
            // Valid code
            session(['recovery_verified' => true]);
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['code' => 'El código introducido es incorrecto.']);
    }

    public function showResetForm(Request $request)
    {
        if (!session('recovery_email') || !session('recovery_verified')) {
            return redirect()->route('password.request');
        }

        $email = session('recovery_email');
        $token = 'verified'; // To satisfy the blade template that requires $token and $email

        return view('auth.reset-password', compact('email', 'token'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('recovery_email') || !session('recovery_verified')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', session('recovery_email'))->first();
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Clean up recovery session variables
        session()->forget(['recovery_email', 'recovery_code', 'recovery_method', 'recovery_verified']);

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida con éxito!');
    }
}
