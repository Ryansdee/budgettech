<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class kernel extends HttpKernel
{
    protected $middleware = [
        // Autres middlewares globaux...
        \App\Http\Middleware\CartCountMiddleware::class,
    ];

    // ...
}