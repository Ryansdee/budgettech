@php
    function formatDescription($text) {
        // Formatage du texte avec des sauts de ligne après les points, points d'exclamation, et deux-points
        $text = preg_replace('/([.!])\s*/', '$1<br>', $text);
        $text = preg_replace('/([:,])\s*/', '$1<br>', $text);
        $text = preg_replace('/\[\*\]\s*/', '<br>', $text);
        return $text;
    }

    function getProductIcons($description) {
        // Déterminer les icônes à afficher en fonction de la description
        $icons = [];

        if (preg_match('/\b(intel|ryzen|amd)\b/i', $description)) {
            $icons[] = 'https://cdn-icons-png.flaticon.com/512/2432/2432574.png'; // Processeur
        }

        if (preg_match('/\b(B\d{3}|Z\d{3})\b/i', $description)) {
            $icons[] = 'https://cdn-icons-png.flaticon.com/512/5921/5921800.png'; // Carte mère
        }

        if (preg_match('/\b(DDR4|DDR5)\b/i', $description)) {
            $icons[] = 'https://cdn-icons-png.flaticon.com/512/707/707577.png'; // RAM
        }

        if (preg_match('/\b(nvme|sata|ssd)\b/i', $description)) {
            $icons[] = 'https://cdn-icons-png.flaticon.com/512/16335/16335283.png'; // Stockage
        }

        if (preg_match('/\b\d{3,4}W\b/i', $description)) {
            $icons[] = 'https://cdn-icons-png.flaticon.com/512/4617/4617782.png'; // Alimentation
        }

        return $icons;
    }
@endphp

<x-app-layout>

    <div class="py-12 bg-dark">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card mb-4 shadow-lg p-3 bg-dark rounded text-center text-white">
                <!-- Image du produit avec des animations de hover -->
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top img-fluid mx-auto rounded hover-zoom" 
                     alt="{{ $product->name }}" style="max-width: 400px;">
                
                <div class="card-body">
                    <!-- Titre du produit avec une typographie améliorée -->
                    <h2 class="card-title font-weight-bold mb-4">{{ $product->name }}</h2>
                    
                    <!-- Description formatée -->
                    <strong><p class="card-text text-white mb-4">{!! formatDescription($product->description) !!}</p></strong>
                    
                    <!-- Prix stylé et grand -->
                    <p class="card-text display-4 text-danger mb-3">
                        <strong>{{ number_format($product->price, 2) }} €</strong>
                    </p>
                    
                    <!-- Stock avec une icône -->
                    <p class="card-text mb-4 text-warning">
                        <i class="fas fa-box-open"></i> 
                        <strong>Stock :</strong> {{ $product->stock }}
                    </p>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ajouter au panier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lien vers Bootstrap & FontAwesome pour les icônes et styles -->
    <script src="https://kit.fontawesome.com/09ead4cec6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</x-app-layout>
