@extends('layouts.main')

@section('title', 'Welcome to Our E-commerce Store')

@section('content')
    <!-- Hero Banner -->
    <div class="card bg-dark text-white mb-4">
        <img src="{{ asset('images/banner.jpg') }}" class="card-img" alt="Banner"
            style="height: 400px; object-fit: cover; opacity: 0.6;">
        <div class="card-img-overlay d-flex flex-column justify-content-center text-center">
            <h1 class="card-title">Welcome to Shaqueena Zn Official Store</h1>
            <p class="card-text">Find the best products at the best prices</p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    {{-- <h2 class="mb-4">Featured Categories</h2>
    <div class="row">
        @foreach ($featuredCategories as $category)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->description }}</p>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                            class="btn btn-outline-primary">View Products</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    <!-- Featured Products -->
    <h2 class="mb-4">Featured Products</h2>
    <div class="row">
        @foreach ($featuredProducts as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/no-image.jpg') }}" class="card-img-top" alt="No Image"
                                style="height: 200px; object-fit: cover;">
                        @endif
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="text-decoration-none">{{ $product->name }}</a>
                            @if ($product->is_featured_active)
                                <span class="badge bg-warning text-dark ms-1">Featured</span>
                            @endif
                        </h5>
                        <p class="card-text text-muted">{{ $product->category->name }}</p>
                        @if ($product->is_discount_active)
                            <p class="card-text mb-0"><span class="text-danger fw-bold">Rp
                                    {{ number_format($product->final_price, 0, ',', '.') }}</span> <span
                                    class="text-muted text-decoration-line-through">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span></p>
                        @else
                            <p class="card-text fw-bold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</p>
                        @endif
                        @if ($product->stock_quantity > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Latest Products -->
    <h2 class="mb-4">Latest Products</h2>
    <div class="row">
        @foreach ($latestProducts as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/no-image.jpg') }}" class="card-img-top" alt="No Image"
                                style="height: 200px; object-fit: cover;">
                        @endif
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="text-decoration-none">{{ $product->name }}</a>
                        </h5>
                        <p class="card-text text-muted">{{ $product->category->name }}</p>
                        @if ($product->is_discount_active)
                            <p class="card-text mb-0"><span class="text-danger fw-bold">Rp
                                    {{ number_format($product->final_price, 0, ',', '.') }}</span> <span
                                    class="text-muted text-decoration-line-through">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span></p>
                        @else
                            <p class="card-text fw-bold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</p>
                        @endif
                        @if ($product->stock_quantity > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">View All Products</a>
    </div>
@endsection
