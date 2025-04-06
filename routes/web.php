<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('welcome');
// });
// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes (Auth Required)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard Redirector
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Products Management
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Admin Categories Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Admin Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});

// Seller Routes
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Seller Products Management
    Route::get('/products', [SellerProductController::class, 'index'])->name('products');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

    // Seller Orders Management
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.status');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Customer Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
});

// Checkout Routes (Customer only)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Payment Proof Routes
// Customer can upload payment proof
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/orders/{order}/payment', [PaymentProofController::class, 'create'])->name('payment.create');
    Route::post('/orders/{order}/payment', [PaymentProofController::class, 'store'])->name('payment.store');
});

// Admin/Seller can verify payment proof
Route::middleware(['auth', 'role:admin,seller'])->group(function () {
    Route::get('/payments', [PaymentProofController::class, 'index'])->name('payment.index');
    Route::get('/payments/{paymentProof}', [PaymentProofController::class, 'show'])->name('payment.show');
    Route::post('/payments/{paymentProof}/verify', [PaymentProofController::class, 'verify'])->name('payment.verify');
});

require __DIR__ . '/auth.php';
