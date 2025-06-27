@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Shopping Cart</h4>
        </div>
        <div class="card-body">
            @if (isset($cart) && count($cart->cartItems) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart->cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" width="50"
                                                    height="50" alt="{{ $item->product->name }}" class="me-2">
                                            @else
                                                <img src="{{ asset('images/no-image.jpg') }}" width="50" height="50"
                                                    alt="No Image" class="me-2">
                                            @endif
                                            <span>{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->product->is_discount_active)
                                            <span class="text-danger fw-bold">Rp
                                                {{ number_format($item->product->final_price, 0, ',', '.') }}</span><br>
                                            <span class="text-muted text-decoration-line-through">Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                        @else
                                            Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="decrementQuantity('quantity-{{ $item->id }}')">-</button>
                                                <input type="number" name="quantity" id="quantity-{{ $item->id }}"
                                                    class="form-control text-center" value="{{ $item->quantity }}"
                                                    min="1" max="{{ $item->product->stock_quantity }}">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="incrementQuantity('quantity-{{ $item->id }}', {{ $item->product->stock_quantity }})">+</button>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                                        </form>
                                    </td>
                                    <td>
                                        Rp {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>Rp
                                        {{ number_format($cart->cartItems->sum(fn($item) => $item->product->final_price * $item->quantity), 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">continue shopping</a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success">proceed to checkout</a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x mb-3 text-muted"></i>
                    <h5>Your cart is empty</h5>
                    <p>Looks like you haven't added any products to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">continue shopping</a>
                </div>
            @endif
        </div>
    </div>

@section('scripts')
    <script>
        function incrementQuantity(inputId, max) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decrementQuantity(inputId) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
@endsection
@endsection
