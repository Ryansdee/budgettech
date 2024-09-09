<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();  // Récupère tous les products
        return view('admin.dashboard', compact('products'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Product::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'product ajouté avec succès');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::find($id);
        $product->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'product mis à jour avec succès');
    }

    public function destroy($id)
    {
        product::find($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'product supprimé avec succès');
    }

    public function dashboard()
    {
        // Vérifier que l'utilisateur est connecté et est admin
        if (!Auth::check() || Auth::user()->email !== 'admin@ryansdee.be') {
            return redirect('/')->with('error', 'Accès non autorisé.');
        }

        // Récupérer tous les products
        $products = Product::all();

        // Passer les products à la vue admin.dashboard
        return view('admin.dashboard', compact('products'));
    }
}
