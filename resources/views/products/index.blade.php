<x-app-layout>
<h1>Liste des produits</h1>

@if($products->isEmpty())
    <p>Aucun produit disponible.</p>
@else
    <div class="product-list">
        @foreach($products as $product)
            <div class="product-item">
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p>Prix: {{ number_format($product->price, 2) }} €</p>
                <p>Stock: {{ $product->stock }}</p>
            </div>
        @endforeach
    </div>
@endif
</x-app-layout>