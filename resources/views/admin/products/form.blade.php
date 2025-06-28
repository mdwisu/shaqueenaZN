@extends('layouts.main')

@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">My Products</a>
                    <a href="{{ route('admin.products.create') }}"
                        class="list-group-item list-group-item-action {{ !isset($product) ? 'active' : '' }}">Add New
                        Product</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
                        method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @if (isset($product))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories ?? [] as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="cost_price" class="block text-sm font-medium text-gray-700">Cost
                                        Price</label>
                                    <input type="number" name="cost_price" id="cost_price"
                                        value="{{ old('cost_price', $product->cost_price ?? '') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" name="price" id="price"
                                        value="{{ old('price', $product->price ?? '') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <small class="text-muted" id="priceNote">Harga yang akan ditampilkan ke customer</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Penentuan Harga</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pricing_mode" id="pricing_manual"
                                    value="manual"
                                    {{ old('pricing_mode', $product->pricing_mode ?? 'manual') == 'manual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pricing_manual">Manual</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pricing_mode" id="pricing_auto"
                                    value="auto"
                                    {{ old('pricing_mode', $product->pricing_mode ?? '') == 'auto' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pricing_auto">Otomatis (Markup)</label>
                            </div>
                        </div>
                        <div class="mb-4" id="markup_percent_group"
                            style="display: {{ old('pricing_mode', $product->pricing_mode ?? 'manual') == 'auto' ? 'block' : 'none' }};">
                            <label for="markup_percent" class="block text-sm font-medium text-gray-700">Markup (%)</label>
                            <input type="number" step="0.01" name="markup_percent" id="markup_percent"
                                value="{{ old('markup_percent', $product->markup_percent ?? '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <small class="text-muted">Contoh: 50 = 50% markup dari cost price</small>
                        </div>

                        <div class="mb-4" id="calculated_price_group" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Harga yang Dihitung Otomatis:</strong>
                                <span id="calculated_price">Rp 0</span>
                                <br>
                                <small>Cost Price + Markup = Harga Jual</small>
                            </div>
                        </div>

                        <div class="mb-4 border rounded p-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                    value="1"
                                    {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Produk</label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="featured_start" class="form-label">Featured Start</label>
                                    <input type="date" class="form-control" name="featured_start" id="featured_start"
                                        value="{{ old('featured_start', $product->featured_start ?? '2024-09-01') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="featured_end" class="form-label">Featured End</label>
                                    <input type="date" class="form-control" name="featured_end" id="featured_end"
                                        value="{{ old('featured_end', $product->featured_end ?? '2025-02-28') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 border rounded p-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diskon Produk</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-select" name="discount_type" id="discount_type">
                                        <option value="">Pilih Tipe Diskon</option>
                                        <option value="percent"
                                            {{ old('discount_type', $product->discount_type ?? '') == 'percent' ? 'selected' : '' }}>
                                            Persen (%)</option>
                                        <option value="nominal"
                                            {{ old('discount_type', $product->discount_type ?? '') == 'nominal' ? 'selected' : '' }}>
                                            Nominal (Rp)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" class="form-control" name="discount_value"
                                        id="discount_value" placeholder="Nilai Diskon"
                                        value="{{ old('discount_value', $product->discount_value ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="discount_start" id="discount_start"
                                        value="{{ old('discount_start', $product->discount_start ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="discount_end" id="discount_end"
                                        value="{{ old('discount_end', $product->discount_end ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock
                                Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity"
                                value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                id="image" name="image" {{ isset($product) ? '' : 'required' }}>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if (isset($product) && $product->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" width="100"
                                        alt="{{ $product->name }}">
                                    <p class="text-muted small">Current image. Upload a new one to replace it.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status" name="status"
                                    value="1" {{ old('status', $product->status ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active (Product will be visible to customers)
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Add Product' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pricingModeRadios = document.querySelectorAll('input[name="pricing_mode"]');
            const markupGroup = document.getElementById('markup_percent_group');
            const calculatedPriceGroup = document.getElementById('calculated_price_group');
            const costPriceInput = document.getElementById('cost_price');
            const markupInput = document.getElementById('markup_percent');
            const priceInput = document.getElementById('price');
            const calculatedPriceSpan = document.getElementById('calculated_price');
            const priceNote = document.getElementById('priceNote');

            // Function to calculate price
            function calculatePrice() {
                const costPrice = parseFloat(costPriceInput.value) || 0;
                const markup = parseFloat(markupInput.value) || 0;

                if (costPrice > 0 && markup > 0) {
                    const calculatedPrice = costPrice + (costPrice * markup / 100);
                    calculatedPriceSpan.textContent = 'Rp ' + calculatedPrice.toLocaleString('id-ID');
                    return calculatedPrice;
                }
                return 0;
            }

            // Function to update price input based on mode
            function updatePriceInput() {
                const selectedMode = document.querySelector('input[name="pricing_mode"]:checked').value;

                if (selectedMode === 'auto') {
                    const calculatedPrice = calculatePrice();
                    if (calculatedPrice > 0) {
                        priceInput.value = calculatedPrice;
                        priceInput.readOnly = true;
                        priceNote.textContent = 'Harga dihitung otomatis dari cost price + markup';
                        priceNote.className = 'text-info';
                    }
                } else {
                    priceInput.readOnly = false;
                    priceNote.textContent = 'Harga yang akan ditampilkan ke customer';
                    priceNote.className = 'text-muted';
                }
            }

            // Handle pricing mode change
            pricingModeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'auto') {
                        markupGroup.style.display = 'block';
                        calculatedPriceGroup.style.display = 'block';
                        updatePriceInput();
                    } else {
                        markupGroup.style.display = 'none';
                        calculatedPriceGroup.style.display = 'none';
                        priceInput.readOnly = false;
                        priceNote.textContent = 'Harga yang akan ditampilkan ke customer';
                        priceNote.className = 'text-muted';
                    }
                });
            });

            // Handle cost price and markup changes
            costPriceInput.addEventListener('input', function() {
                if (document.querySelector('input[name="pricing_mode"]:checked').value === 'auto') {
                    calculatePrice();
                    updatePriceInput();
                }
            });

            markupInput.addEventListener('input', function() {
                if (document.querySelector('input[name="pricing_mode"]:checked').value === 'auto') {
                    calculatePrice();
                    updatePriceInput();
                }
            });

            // Form validation
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const selectedMode = document.querySelector('input[name="pricing_mode"]:checked').value;

                if (selectedMode === 'auto') {
                    const costPrice = parseFloat(costPriceInput.value) || 0;
                    const markup = parseFloat(markupInput.value) || 0;

                    if (costPrice <= 0) {
                        e.preventDefault();
                        alert('Cost Price harus diisi dan lebih dari 0 untuk mode otomatis');
                        costPriceInput.focus();
                        return false;
                    }

                    if (markup <= 0) {
                        e.preventDefault();
                        alert('Markup harus diisi dan lebih dari 0 untuk mode otomatis');
                        markupInput.focus();
                        return false;
                    }
                }
            });

            // Initialize on page load
            if (document.querySelector('input[name="pricing_mode"]:checked').value === 'auto') {
                markupGroup.style.display = 'block';
                calculatedPriceGroup.style.display = 'block';
                updatePriceInput();
            }
        });
    </script>
@endsection
