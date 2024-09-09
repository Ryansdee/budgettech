<h1>{{ $produit->nom }}</h1>
<p>{{ $produit->description }}</p>
<p>Prix : ${{ $produit->prix }}</p>

<form action="{{ route('paiement') }}" method="POST">
    @csrf
    <script
        src="https://checkout.stripe.com/checkout.js"
        class="stripe-button"
        data-key="{{ env('STRIPE_KEY') }}"
        data-amount="{{ $produit->prix * 100 }}"
        data-name="Votre Site"
        data-description="{{ $produit->nom }}"
        data-currency="usd">
    </script>
</form>
