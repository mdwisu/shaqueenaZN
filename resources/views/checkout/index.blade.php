@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Checkout</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <h5>Shipping Information</h5>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('shipping_address') is-invalid @enderror"
                                id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}"
                                required>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('shipping_city') is-invalid @enderror"
                                        id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control @error('shipping_state') is-invalid @enderror"
                                        id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}"
                                        required>
                                    @error('shipping_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_zipcode" class="form-label">Zipcode</label>
                                    <input type="text"
                                        class="form-control @error('shipping_zipcode') is-invalid @enderror"
                                        id="shipping_zipcode" name="shipping_zipcode" value="{{ old('shipping_zipcode') }}"
                                        required>
                                    @error('shipping_zipcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror"
                                        id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                        required>
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart->cartItems as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>Rp {{ number_format($cart->total, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <h5 class="mt-4">Payment Method</h5>
                        <div class="alert alert-info">
                            <p>After placing your order, you'll need to transfer the total amount to one of our bank
                                accounts and upload the payment proof in your order details.</p>
                            <ul class="mb-0">
                                <li>Bank BCA: 1234567890 (John Doe)</li>
                                <li>Bank Mandiri: 0987654321 (John Doe)</li>
                            </ul>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the terms and conditions
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Place Order</button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Back to Cart</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
