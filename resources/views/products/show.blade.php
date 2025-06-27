@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded"
                            alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('images/no-image.jpg') }}" class="img-fluid rounded" alt="No Image">
                    @endif
                </div>
                <div class="col-md-7">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                        </ol>
                    </nav>

                    <h2>{{ $product->name }}</h2>
                    <p class="text-muted">Category: {{ $product->category->name }}</p>
                    <h3 class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Availability</h5>
                        @if ($product->stock_quantity > 0)
                            <p class="text-success">In Stock ({{ $product->stock_quantity }} available)</p>
                        @else
                            <p class="text-danger">Out of Stock</p>
                        @endif
                    </div>

                    @if ($product->stock_quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="decrement()">-</button>
                                        <input type="number" id="quantity" name="quantity"
                                            class="form-control text-center" value="1" min="1"
                                            max="{{ $product->stock_quantity }}">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="increment()">+</button>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-primary btn-lg mt-4">Add to Cart</button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <div class="d-flex justify-content-start">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">continue shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        function increment() {
            const input = document.getElementById('quantity');
            const max = {{ $product->stock_quantity }};
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decrement() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
@endsection
@endsection
