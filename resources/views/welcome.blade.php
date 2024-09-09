<!-- resources/views/dashboard.blade.php -->

@php
    function formatDescription($text) {
        $text = preg_replace('/([.!])\s*/', '$1<br>', $text);
        $text = preg_replace('/([:,])\s*/', '$1<br>', $text);
        return $text;
    }
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    body {
        background-color: #000; /* Fond noir pour le corps */
        color: #fff; /* Texte en blanc */
    }

    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #333; /* Bordure gris très foncé */
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease-in-out;
        background-color: #1e1e1e; /* Fond de carte gris foncé */
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
    }

    .card-img-top {
        width: 50%;
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #ff0000; /* Texte du titre en rouge */
    }

    .card-text {
        font-size: 1rem;
        color: #ccc; /* Texte en gris clair */
    }

    .card-text strong {
        color: #ff0000; /* Prix en rouge */
    }

    .btn-primary {
        background-color: #ff0000; /* Boutons rouges */
        border-color: #ff0000;
    }

    .btn-primary:hover {
        background-color: #cc0000; /* Boutons rouges plus foncés au survol */
        border-color: #cc0000;
    }

    .dropdown-label {
        font-weight: 500;
        margin-right: 1rem;
        color: #fff; /* Texte en rouge pour le label du dropdown */
    }

    .container-padding {
        padding: 1rem;
    }

    .section-divider {
        border-top: 1px solid #333; /* Trait gris foncé pour la séparation des sections */
        margin: 2rem 0;
    }

    .featured-banner {
        background-color: #ff0000; /* Fond de la bannière en rouge */
        color: #fff; /* Texte de la bannière en blanc */
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-weight: bold;
    }

    .alert-info {
        background-color: #333; /* Fond de l'alerte en gris foncé */
        color: #fff; /* Texte de l'alerte en blanc */
        border-color: #444; /* Bordure de l'alerte en gris foncé */
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight" style="color: white;">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 container-padding">

                    @if($products->isEmpty())
                        <div class="alert alert-info" role="alert">
                            Aucun produit disponible.
                        </div>
                    @else
                        <!-- Ajouter un dropdown pour trier les produits -->
                        <div class="d-flex align-items-center mb-4">
                            <label for="order" class="dropdown-label">Trier par:</label>
                            <select id="order" class="form-select">
                                <option value="asc">Prix croissant</option>
                                <option value="desc">Prix décroissant</option>
                            </select>
                        </div>

                        <!-- Affichage des produits -->
                        <div id="products-container" class="row g-4">
                            @foreach($products as $product)
                                <div class="col-md-4 mb-4" data-price="{{ $product->price }}">
                                    <a href="{{ route('products.show', ['name' => $product->name]) }}">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top mx-auto" alt="{{ $product->name }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text">{!! formatDescription($product->description) !!}</p>
                                            <p class="card-text"><strong>{{ number_format($product->price, 2) }} €</strong></p>
                                            <a href="{{ route('products.show', ['name' => $product->name]) }}" class="btn btn-primary">Voir le produit</a>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('order').addEventListener('change', function() {
            let order = this.value;
            let container = document.getElementById('products-container');
            let products = Array.from(container.getElementsByClassName('col-md-4'));

            products.sort((a, b) => {
                let priceA = parseFloat(a.getAttribute('data-price'));
                let priceB = parseFloat(b.getAttribute('data-price'));
                return (order === 'asc' ? priceA - priceB : priceB - priceA);
            });

            // Réajuster l'ordre des produits dans le conteneur
            products.forEach(product => container.appendChild(product));
        });
    </script>
</x-app-layout>
