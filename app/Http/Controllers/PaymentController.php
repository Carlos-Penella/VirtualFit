<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function plans()
    {
        return view('plans');
    }

    public function showCheckout(string $plan)
    {
        if (!in_array($plan, ['premium', 'premiumFit'], true)) {
            abort(404);
        }

        $plans = [
            'premium' => [
                'name' => 'Plan Premium',
                'price' => '9,99 € / mes',
                'description' => 'Acceso ilimitado a clases online y prioridad en plazas con pocas vacantes.',
            ],
            'premiumFit' => [
                'name' => 'Plan Premium Fit',
                'price' => '14,99 € / mes',
                'description' => 'Todo lo del Plan Premium + contenido exclusivo, retos y mayor multiplicador de Fitcoins.',
            ],
        ];

        return view('checkout', [
            'planKey' => $plan,
            'plan' => $plans[$plan],
        ]);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:premium,premiumFit',
        ]);

        /** @var Usuario $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para completar la compra.');
        }

        $plan = $request->input('plan');

        // AQUÍ IRÍA LA INTEGRACIÓN REAL CON LA PASARELA (Stripe/PayPal/Redsys...)
        // - Crear intento de pago
        // - Redirigir a la página segura de pago del proveedor
        // - Recibir webhook o callback de confirmación
        // Para este proyecto de ejemplo simulamos que el pago es correcto.

        $user->tipo_usuario = $plan === 'premium' ? 'premium' : 'premiumFit';
        $user->save();

        return redirect()->route('home')->with('status', 'Pago completado con éxito. Ahora tu plan es: ' . $user->tipo_usuario . '.');
    }
}
