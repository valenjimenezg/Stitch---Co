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

        $user = User::create([
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $data['telefono'] ?? null,
            'document_type'   => $data['document_type'],
            'document_number' => $data['document_number'],
            'rol'      => 'cliente',
        ]);

        Auth::login($user);

        return redirect()->intended('/checkout');
    }

    public function checkDocument(Request $request)
    {
        $exists = User::where('document_number', $request->input('document_number'))
                      ->where('document_type', $request->input('document_type'))
                      ->exists();

        return response()->json(['exists' => $exists]);
    }
}
