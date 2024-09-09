<?php

// app/Http/Controllers/PaiementController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaiementController extends Controller
{
    public function paiement(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $charge = Charge::create([
            'amount' => $request->input('amount'),
            'currency' => 'eur',
            'source' => $request->stripeToken,
            'description' => 'Paiement produit',
        ]);

        return redirect()->back()->with('success', 'Paiement réussi !');
    }
}
