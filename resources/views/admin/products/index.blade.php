@extends('layouts.main')

@section('title', 'My Products')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action active">My
                        Products</a>
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action">Add New
                        Product</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Products</h4>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-light">Add New Product</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Final Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products ?? [] as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" width="50"
                                                    height="50" alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ asset('images/no-image.jpg') }}" width="50" height="50"
                                                    alt="No Image">
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}
                                            @if ($product->is_featured_active)
                                                <span class="badge bg-warning text-dark ms-1">Featured</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>
                                            @if ($product->is_discount_active)
                                                <span class="text-danger fw-bold">Rp
                                                    {{ number_format($product->final_price, 0, ',', '.') }}</span><br>
                                                <span class="text-muted text-decoration-line-through">Rp
                                                    {{ number_format($product->price, 0, ',', '.') }}</span>
                                            @else
                                                Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>{{ $product->final_price }}</td>
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>
                                            @if ($product->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links() ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
