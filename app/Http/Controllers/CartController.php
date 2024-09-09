<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Voir le panier
    public function viewCart()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        } else {
            $cartItems = collect();
        }
        
        $totalPrice = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
    
        return view('cart.index', [
            'cart' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }
    public function show()
    {
        // Récupérer le panier pour l'utilisateur connecté
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
    
        // Si le panier n'existe pas, en créer un nouveau
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }
    
        // Calculer le prix total du panier
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    
        return view('cart.index', compact('cart', 'totalPrice'));
    }

    // Ajouter un produit au panier
    public function addToCart(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
    
            if ($product->stock <= 0) {
                return redirect()->route('cart.view')->with('error', 'Produit non disponible.');
            }
    
            $cart = $this->getCart();
            $item = $cart->items()->where('product_id', $id)->first();
    
            if ($item) {
                if ($product->stock < $item->quantity + 1) {
                    return redirect()->route('cart.view')->with('error', 'Quantité insuffisante.');
                }
                $item->quantity++;
                $item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $id,
                    'quantity' => 1,
                    'price' => $product->price,
                ]);
            }
    
            return redirect()->route('cart.view')->with('success', 'Produit ajouté au panier !');
        } catch (\Exception $e) {
            return redirect()->route('cart.view')->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    

    // Supprimer un produit du panier
    public function removeFromCart(Request $request, $id)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('product_id', $id)->first();
        if ($item) {
            $item->delete();
        }
    
        return redirect()->route('cart.view')->with('success', 'Produit supprimé du panier !');
    }
    // Checkout - finaliser la commande (exemple basique sans paiement)

    public function update(Request $request)
    {
        $cart = $this->getCart();

        foreach ($request->input('cart', []) as $id => $data) {
            $item = $cart->items()->where('product_id', $id)->first();
            if ($item) {
                $item->quantity = $data['quantity'];
                $item->save();
            }
        }

        return redirect()->route('cart.view')->with('success', 'Panier mis à jour avec succès.');
    }

    private function getCart()
    {
        if (!Auth::check()) {
            throw new \Exception('Vous devez être connecté pour accéder au panier.');
        }
    
        return Cart::where('user_id', Auth::id())->firstOrCreate(['user_id' => Auth::id()]);
    }
    public function getCartItemCount(Request $request)
    {
        try {
            $cart = $this->getCart();
            $itemCount = $cart->items->sum('quantity');
            return response()->json(['count' => $itemCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403); // Return an error message if not authenticated
        }
    }

    public function updateQuantity(Request $request)
{
    $cart = $this->getCart();
    $item = $cart->items()->where('id', $request->input('id'))->first();

    if ($item) {
        $item->quantity = $request->input('quantity');
        $item->save();
    }

    return response()->json(['success' => true]);
}
public function remove($productId) {
    $cart = Cart::where('user_id', auth()->id())->first();
    if ($cart) {
        $cart->items()->where('product_id', $productId)->delete();
    }
}

}
