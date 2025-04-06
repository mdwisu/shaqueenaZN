@extends('layouts.main')

@section('title', 'Products')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Our Products</h2>
        </div>
        <div class="col-md-6">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <select name="category" class="form-select me-2">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search products..." name="search"
                        value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
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
                        <p class="card-text fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
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
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No products found</h4>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $products->appends(request()->query())->links() }}
    </div>
@endsection
