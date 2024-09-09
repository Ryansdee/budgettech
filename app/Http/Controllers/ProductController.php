<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Http\Controllers\CartController;


class ProductController extends Controller
{

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // validation pour l'image
        ]);

        // Enregistrer l'image
        $imagePath = $request->file('image')->store('products', 'public');

        // Créer le produit
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Produit ajouté avec succès.');
    }
    public function dashboard(Request $request)
    {
        // Vérifier si l'utilisateur a demandé un tri particulier
        $order = $request->query('order', 'asc'); // Par défaut, tri par ordre croissant (asc)
        
        // Récupérer les produits et les trier en fonction de l'ordre demandé
        $products = Product::orderBy('price', $order)->get();
        
        // Passer les produits triés à la vue dashboard
        return view('dashboard', compact('products', 'order'));
    }
    
    public function edit($id)
    {
        // Trouver le produit par ID
        $product = Product::findOrFail($id);

        // Retourner la vue d'édition avec le produit
        return view('admin.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Autoriser une image optionnelle
        ]);
    
        // Trouver le produit par ID
        $product = Product::findOrFail($id);
    
        // Mettre à jour les informations du produit
        $product->update($request->except('image'));
    
        // Si une nouvelle image est fournie, la traiter
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }
    
        // Rediriger vers le dashboard avec un message de succès
        return redirect()->route('admin.dashboard')->with('success', 'Produit mis à jour avec succès.');
    }

// ProductController.php
public function destroy($id)
{
    $product = Product::find($id);
    if ($product) {
        $product->delete();
    }

    return redirect()->route('product.index')->with('success', 'Produit supprimé avec succès.');
}


    public function show($name)
    {
        // Trouver le produit par nom
        $product = Product::where('name', $name)->firstOrFail();
    
        // Passer le produit à la vue
        return view('products.show', compact('product'));
    }


    public function index($name)
    {
        // Trouver le produit par nom
        $product = Product::where('name', $name)->firstOrFail();
    
        // Passer le produit à la vue
        return view('products.show', compact('product'));
    }

    public function welcome(Request $request)
    {
        // Vérifier si l'utilisateur a demandé un tri particulier
        $order = $request->query('order', 'asc'); // Par défaut, tri par ordre croissant (asc)
        
        // Récupérer les produits et les trier en fonction de l'ordre demandé
        $products = Product::orderBy('price', $order)->get();
        
        // Passer les produits triés à la vue dashboard
        return view('welcome', compact('products', 'order'));
    }
    
}
