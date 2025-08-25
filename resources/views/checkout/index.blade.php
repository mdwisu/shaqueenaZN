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
                                    <label for="shipping_state" class="form-label">State</label>
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
                                    <label for="shipping_zipcode" class="form-label">Zip Code</label>
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
                            <label for="notes" class="form-label">Notes</label>
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
                                            <td>
                                                @if ($item->product->is_discount_active)
                                                    <span class="text-danger fw-bold">Rp
                                                        {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}</span><br>
                                                    <span class="text-muted text-decoration-line-through">Rp
                                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                                @else
                                                    Rp
                                                    {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end">Subtotal:</td>
                                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end">Estimasi Ongkos Kirim:</td>
                                        <td>
                                            @if($estimatedShippingCost == 0)
                                                <span class="text-success">Gratis</span>
                                            @else
                                                Rp {{ number_format($estimatedShippingCost, 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="2" class="text-end"><strong>Estimasi Total:</strong></td>
                                        <td><strong>Rp {{ number_format($subtotal + $estimatedShippingCost, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-info-circle"></i> Informasi Ongkos Kirim:</h6>
                            <ul class="mb-2">
                                <li>Pembelian ≥ Rp 500.000: <strong>Gratis ongkir</strong></li>
                                <li>Pembelian Rp 200.000 - Rp 499.999: Rp 15.000</li>
                                <li>Pembelian Rp 100.000 - Rp 199.999: Rp 25.000</li>
                                <li>Pembelian < Rp 100.000: Rp 35.000</li>
                            </ul>
                            <div class="alert alert-info mb-0">
                                <small><i class="fas fa-exclamation-triangle"></i> <strong>Catatan:</strong> Ongkos kirim di atas adalah estimasi untuk wilayah Jakarta dan sekitarnya. Admin akan mengkonfirmasi ongkos kirim final berdasarkan alamat pengiriman Anda.</small>
                            </div>
                        </div>

                        <h5 class="mt-4">Payment Method</h5>
                        <div class="alert alert-info">
                            <p>Setelah melakukan pemesanan, Anda perlu mentransfer jumlah total ke salah satu rekening bank
                                kami dan mengunggah bukti pembayaran di rincian pesanan Anda.</p>
                            <ul class="mb-0">
                                <li>Bank BCA: 1740874558 (A.n Selvyra citha dewi)</li>
                                <li>Bank BRI: 227401012529530 (A.n Selvyra citha dewi)</li>
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
