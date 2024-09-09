<?php
// app/Http/Middleware/CartCountMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;

class CartCountMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $cartController = new CartController();
        $itemCount = $cartController->getCartItemCount($request);

        // Partager la variable avec toutes les vues
        view()->share('itemCount', $itemCount);

        return $next($request);
    }
}
