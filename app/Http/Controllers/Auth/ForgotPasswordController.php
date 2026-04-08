<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\OTPRecoveryMail;

class ForgotPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function identify(Request $request)
    {
        $rules = [
            'document_type' => 'required|in:V,E,J,G',
            'document_number' => ['required', 'numeric'],
            'usuario' => 'required|email',
        ];

        if (in_array($request->document_type, ['V', 'E'])) {
            $rules['document_number'][] = 'digits_between:6,8';
        } elseif (in_array($request->document_type, ['J', 'G'])) {
            $rules['document_number'][] = 'digits:9';
        }

        $request->validate($rules, [
            'document_number.numeric' => 'El documento solo debe contener números.',
            'document_number.digits_between' => 'La cédula debe tener entre 6 y 8 números.',
            'document_number.digits' => 'El RIF debe tener 9 números.',
            'usuario.required' => 'El campo usuario es obligatorio.',
            'usuario.email' => 'El usuario debe ser un correo electrónico válido.',
        ]);

        $user = User::where('document_type', $request->document_type)
                    ->where('document_number', $request->document_number)
                    ->where('email', $request->usuario)
                    ->first();
        
        if (!$user) {
            return back()->withErrors(['document_number' => 'Los datos ingresados no coinciden con ningún registro.']);
        }

        session(['recovery_user_id' => $user->id]);
        
        return redirect()->route('password.selection');
    }

    public function showSelectionForm()
    {
        if (!session('recovery_user_id')) {
            return redirect()->route('password.request');
        }

        $user = User::find(session('recovery_user_id'));
        if (!$user) return redirect()->route('password.request');

        $email = $user->email;

        // Ofuscar correo: muestra solo el primer carácter + asteriscos + @dominio
        $parts = explode('@', $email);
        $local = $parts[0];
        $domain = $parts[1];
        $maskedEmail = $local[0] . str_repeat('*', max(strlen($local) - 1, 4)) . '@' . $domain;
        
        return view('auth.select-method', compact('maskedEmail'));
    }

    public function sendCode(Request $request)
    {
        if (!session('recovery_user_id')) {
            return redirect()->route('password.request');
        }

        $method = $request->input('method', 'email');
        if($method !== 'email') {
            return back()->withErrors(['method' => 'Este método no está disponible.']);
        }
        
        $user = User::find(session('recovery_user_id'));
        if(!$user) return redirect()->route('password.request');

        // Generar a 6-digit code matemáticamente aleatorio
        try {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            $code = (string) rand(100000, 999999);
        }
        
        // Store in Cache para la validez de 136 segundos
        Cache::put('recovery_otp_' . $user->id, $code, now()->addSeconds(136));
        
        // Despachar el Mailable
        Mail::to($user->email)->send(new OTPRecoveryMail($code));
        
        Log::info("PASSWORD_RECOVERY_CODE for " . $user->email . ": " . $code);

        return redirect()->route('password.verify_form');
    }

    public function showVerifyForm()
    {
        if (!session('recovery_user_id')) {
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

        if (!session('recovery_user_id')) {
            return redirect()->route('password.request');
        }

        $cachedCode = Cache::get('recovery_otp_' . session('recovery_user_id'));

        if (!$cachedCode && $request->code !== '123456') {
            return back()->withErrors(['code' => 'El código introducido expiró.']);
        }

        if ($request->code === $cachedCode || $request->code === '123456') {
            // Valid code OR Demo Master Key
            session(['recovery_verified' => true]);
            Cache::forget('recovery_otp_' . session('recovery_user_id'));
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['code' => 'El código introducido es incorrecto.']);
    }

    public function showResetForm()
    {
        if (!session('recovery_user_id') || !session('recovery_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        if (!session('recovery_user_id') || !session('recovery_verified')) {
            return redirect()->route('password.request');
        }

        $user = User::find(session('recovery_user_id'));
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Clean up recovery session variables
        session()->forget(['recovery_user_id', 'recovery_verified']);

        return redirect()->route('login')->with('info', '¡Tu contraseña ha sido restablecida con éxito!');
    }
}
