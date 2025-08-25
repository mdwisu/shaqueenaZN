<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ProductController as OwnerProductController;
use App\Http\Controllers\Owner\CategoryController as OwnerCategoryController;
use App\Http\Controllers\Owner\UserController as OwnerUserController;
use App\Http\Controllers\Owner\OrderController as OwnerOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NotificationController;

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
    
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

// Dashboard Redirector
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
});

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Admin Products Management
    Route::get('/products', [OwnerProductController::class, 'index'])->name('products');
    Route::get('/products/create', [OwnerProductController::class, 'create'])->name('products.create');
    Route::post('/products', [OwnerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [OwnerProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [OwnerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [OwnerProductController::class, 'destroy'])->name('products.destroy');

    // Admin Categories Management
    Route::get('/categories', [OwnerCategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [OwnerCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [OwnerCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [OwnerCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [OwnerCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [OwnerCategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Users Management
    Route::get('/users', [OwnerUserController::class, 'index'])->name('users');
    Route::get('/users/create', [OwnerUserController::class, 'create'])->name('users.create');
    Route::post('/users', [OwnerUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [OwnerUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [OwnerUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [OwnerUserController::class, 'destroy'])->name('users.destroy');

    // Admin Orders Management
    Route::get('/orders', [OwnerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OwnerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OwnerOrderController::class, 'updateStatus'])->name('orders.status');
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

    // Admin Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::put('/orders/{order}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payment');
    Route::put('/orders/{order}/shipping', [AdminOrderController::class, 'confirmShipping'])->name('orders.shipping');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Payment Proof
    Route::get('/payments', [PaymentProofController::class, 'index'])->name('payments.index');
    Route::get('/payments/{paymentProof}', [PaymentProofController::class, 'show'])->name('payments.show');
    Route::post('/payments/{paymentProof}/verify', [PaymentProofController::class, 'verify'])->name('payments.verify');
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

require __DIR__ . '/auth.php';
