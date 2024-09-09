<x-app-layout class="bg-dark">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <div class="py-12 bg-dark">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-danger text-white text-center">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Panier</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($cart->isEmpty())
                                <div class="alert alert-info text-center p-4">
                                    <i class="fas fa-info-circle"></i> Votre panier est vide.
                                </div>
                            @else
                                <form id="cart-form" action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-hover text-light">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Produit</th>
                                                    <th>Quantité</th>
                                                    <th>Prix Unitaire</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cart as $item)
                                                    <tr data-id="{{ $item->product_id }}" data-stock="{{ $item->product->stock }}">
                                                        <td>
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="80" class="img-thumbnail mx-auto">
                                                        </td>
                                                        <td>{{ $item->product->name }}</td>
                                                        <td>
                                                            <input type="number" name="cart[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control quantity-input" data-id="{{ $item->product_id }}">
                                                        </td>
                                                        <td class="item-price">{{ number_format($item->price, 2) }} €</td>
                                                        <td class="item-total">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-sm" onclick="removeItem('{{ $item->product_id }}')">
                                                                <i class="fas fa-trash"></i> Supprimer
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-4">
                                        <h4>Frais de livraison : <strong id="shipping-fee">5,00 €</strong></h4>
                                        <h4>Total : <strong id="grand-total">{{ number_format($totalPrice + 5, 2) }} €</strong></h4>
                                        <a href="{{ route('payment.show') }}" class="btn btn-danger btn-lg mt-3">
                                            <i class="fas fa-credit-card"></i> Payer maintenant
                                        </a>
                                    </div>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS and Font Awesome CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+fg2knZz7sNvXpA5ptdtV70q8IQwB" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartForm = document.getElementById('cart-form');
            const shippingFee = 5;
            const grandTotalElement = document.getElementById('grand-total');

            function updateTotals() {
                let totalPrice = 0;

                document.querySelectorAll('tbody tr').forEach(function(row) {
                    const quantityInput = row.querySelector('input.quantity-input');
                    const quantity = parseFloat(quantityInput.value);
                    const maxQuantity = parseFloat(row.getAttribute('data-stock'));
                    const price = parseFloat(row.querySelector('td.item-price').textContent.replace(' €', '').trim());
                    const total = quantity * price;

                    if (quantity > maxQuantity) {
                        quantityInput.value = maxQuantity;
                        alert('La quantité ne peut pas dépasser le stock disponible.');
                    }

                    row.querySelector('.item-total').textContent = total.toFixed(2) + ' €';
                    totalPrice += total;
                });

                const grandTotal = totalPrice + shippingFee;
                grandTotalElement.textContent = grandTotal.toFixed(2) + ' €';
            }

            cartForm.addEventListener('input', function(event) {
                if (event.target.type === 'number' && event.target.classList.contains('quantity-input')) {
                    updateTotals();
                }
            });

            window.removeItem = function(id) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ? Recharger la page une fois ;)')) {
                    fetch(`{{ route('cart.remove', ':id') }}`.replace(':id', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    }).then(() => {
                        // Rafraîchir la page après la suppression
                        window.location.reload();
                    }).catch(error => {
                        console.error('Erreur lors de la suppression :', error);
                    });
                }
            }
        });
    </script>
</x-app-layout>
