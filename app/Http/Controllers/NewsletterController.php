<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmed;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor, introduce una dirección de correo válida.',
            'email.unique' => 'Este correo ya está suscrito a nuestro boletín.',
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
        ]);

        // Enviar correo de confirmación
        Mail::to($subscriber->email)->send(new SubscriptionConfirmed($subscriber));

        return back()->with('success', '¡Gracias por suscribirte! Revisa tu correo para más información.');
    }
}
