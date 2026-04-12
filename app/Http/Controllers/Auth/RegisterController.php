<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
        $telefonoCompleto = !empty($data['telefono_numero']) ? $data['telefono_prefijo'] . $data['telefono_numero'] : null;

        $user = User::create([
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $telefonoCompleto,
            'tipo_documento'   => $data['tipo_documento'],
            'documento_identidad' => $data['documento_identidad'],
            'rol'      => 'cliente',
        ]);

        Auth::login($user);

        return redirect()->intended('/checkout');
    }

    public function checkDocument(Request $request)
    {
        $exists = User::where('documento_identidad', $request->input('documento_identidad'))
                      ->where('tipo_documento', $request->input('tipo_documento'))
                      ->exists();

        return response()->json(['exists' => $exists]);
    }
}
