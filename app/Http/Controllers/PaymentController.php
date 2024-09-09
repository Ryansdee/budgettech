<?php

// App\Http\Controllers\PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use App\Models\Cart; // Assurez-vous d'avoir le bon namespace pour Cart
use App\Models\CartItem;

class PaymentController extends Controller
{
    public function showPaymentPage()
    {
        // Récupérer les éléments du panier avec les produits associés
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();
        
        if (!$cart) {
            // Gérer le cas où le panier est vide ou non trouvé
            return redirect()->route('cart.view')->with('error', 'Panier non trouvé.');
        }
    
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    
        return view('payment', [
            'cart' => $cart->items,
            'totalPrice' => $totalPrice
        ]);
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'stripeToken' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        Stripe::setApiKey(config('services.stripe.secret')); // Définir la clé API ici

        try {
            $charge = Charge::create([
                'amount' => $validated['amount'] * 100, // Montant en cents
                'currency' => 'eur',
                'description' => 'Exemple de paiement',
                'source' => $validated['stripeToken'],
            ]);

            // Traitement en cas de succès
            return redirect()->route('payment.show')->with('success', 'Paiement réussi !');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->route('payment.show')->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function getCartTotal()
    {
        $cart = $this->getCart();
        return $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    private function getCart()
    {
        if (!Auth::check()) {
            throw new \Exception('Vous devez être connecté pour accéder au panier.');
        }

        return Cart::where('user_id', Auth::id())->firstOrCreate(['user_id' => Auth::id()]);
    }
}
