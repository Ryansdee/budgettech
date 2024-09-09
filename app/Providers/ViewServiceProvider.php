<?php

// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CartController;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Utiliser le view composer pour partager la variable avec toutes les vues
        View::composer('*', function ($view) {
            $cartController = app(CartController::class);
            $itemCount = $cartController->getCartItemCount(request());
            $view->with('itemCount', $itemCount);
        });
    }
}
