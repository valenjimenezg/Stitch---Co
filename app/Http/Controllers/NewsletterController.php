<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificacionCrm;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:notificaciones_crm,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo ya está suscrito a nuestras novedades.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        NotificacionCrm::create([
            'email' => $request->email,
            'tipo' => 'newsletter',
            'procesado' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Listo! Ya eres parte de Stitch & Co.'
        ]);
    }
}
