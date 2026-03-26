<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email',
            'asunto'  => 'required|string|max:100',
            'mensaje' => 'required|string|min:10',
        ]);

        // Future: send email notification

        return back()->with('success', '¡Mensaje enviado! Te responderemos pronto.');
    }
}
