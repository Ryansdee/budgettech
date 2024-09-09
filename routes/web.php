<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route pour la page d'accueil
Route::get('/', [ProductController::class, 'welcome'])->name('welcome');

// Route pour le dashboard
Route::get('/dashboard', [ProductController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Routes pour la gestion du profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour le panier
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/item-count', [CartController::class, 'getCartItemCount'])->name('cart.item-count');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    
    // Routes pour le paiement
    Route::get('/payment', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

    // Routes pour le dashboard administrateur
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Routes pour la gestion des produits
    Route::get('/admin/produits/edit/{id}', [ProductController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/produits/update/{id}', [ProductController::class, 'update'])->name('admin.update');
    Route::delete('/admin/produits/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.destroy');
    
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{name}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.delete');
});

// Route pour le paiement via un autre contrôleur (vérifiez si nécessaire)
Route::post('/paiement', [PaiementController::class, 'paiement'])->name('paiement');

require __DIR__.'/auth.php';
