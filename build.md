# Panduan Membuat E-commerce dengan Laravel (Login, Register, Multi-Role)

## 1. Persiapan Awal

### Instalasi Laravel

```bash
# Install Laravel melalui Composer
composer create-project laravel/laravel ecommerce

# Masuk ke direktori proyek
cd ecommerce
```

### Setup Database

Buka file `.env` dan sesuaikan konfigurasi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
```

## 2. Membuat Sistem Multi-Role

### Membuat Migrasi untuk Tabel Users

Laravel sudah menyediakan migrasi untuk users, tapi kita perlu menambahkan kolom role:

```bash
php artisan make:migration add_role_to_users_table --table=users
```

Buka file migrasi yang baru dibuat di `database/migrations` dan edit:

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'seller', 'customer'])->default('customer')->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
```

### Update Model User

Buka `app/Models/User.php` dan tambahkan kolom role ke fillable:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
];
```

### Menjalankan Migrasi

```bash
php artisan migrate
```

## 3. Membuat Sistem Autentikasi

Laravel memiliki fitur autentikasi bawaan yang bisa digunakan untuk login dan register:

```bash
# Menginstall Laravel Breeze (paket autentikasi sederhana dengan Blade)
composer require laravel/breeze --dev

# Install Breeze dengan Blade (frontend sederhana)
php artisan breeze:install blade

# Install npm dependencies dan build assets
npm install
npm run dev
```

## 4. Kustomisasi Form Register untuk Multi-Role

### Modifikasi Form Register

Buka file `resources/views/auth/register.blade.php` dan tambahkan field untuk role:

```html
<!-- Role Selection -->
<div class="mt-4">
    <x-input-label for="role" :value="__('Register As')" />
    <select
        id="role"
        name="role"
        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="customer">Customer</option>
        <option value="seller">Seller</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>
```

### Update Controller Register

Buka file `app/Http/Controllers/Auth/RegisteredUserController.php` dan modifikasi method `store()`:

```php
// Tambahkan 'role' ke validasi
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'role' => ['required', 'string', 'in:customer,seller'], // tambahkan validasi role
]);

// Tambahkan 'role' saat membuat user baru
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role, // tambahkan role
]);
```

## 5. Membuat Middleware untuk Multi-Role

### Buat Middleware CheckRole

```bash
php artisan make:middleware CheckRole
```

Buka file `app/Http/Middleware/CheckRole.php` dan edit:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

### Daftarkan Middleware

Buka file `app/Http/Kernel.php` dan tambahkan ke array `$routeMiddleware`:

```php
protected $middlewareAliases = [
    // middleware lainnya...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

## 6. Membuat Halaman Dashboard untuk Setiap Role

### Buat Controller untuk Dashboard

```bash
php artisan make:controller DashboardController
```

Buka file `app/Http/Controllers/DashboardController.php` dan edit:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    public function sellerDashboard()
    {
        return view('dashboard.seller');
    }

    public function customerDashboard()
    {
        return view('dashboard.customer');
    }
}
```

### Buat View untuk Dashboard

Buat direktori dan file untuk setiap dashboard:

```bash
mkdir -p resources/views/dashboard
touch resources/views/dashboard/admin.blade.php
touch resources/views/dashboard/seller.blade.php
touch resources/views/dashboard/customer.blade.php
```

Contoh isi untuk `resources/views/dashboard/admin.blade.php`:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">{{ __("Welcome Admin!") }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
```

### Update Routes

Buka file `routes/web.php` dan tambahkan routes untuk dashboard:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        // Tambahkan rute admin lainnya di sini
    });

    // Seller routes
    Route::middleware(['role:seller'])->group(function () {
        Route::get('/seller/dashboard', [DashboardController::class, 'sellerDashboard'])->name('seller.dashboard');
        // Tambahkan rute seller lainnya di sini
    });

    // Customer routes
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer/dashboard', [DashboardController::class, 'customerDashboard'])->name('customer.dashboard');
        // Tambahkan rute customer lainnya di sini
    });
});
```

## 7. Jalankan Server

```bash
php artisan serve
```

## 8. Membuat Model dan Migrasi untuk Sistem E-commerce

### A. Membuat Model dan Migrasi untuk Kategori Produk

```bash
php artisan make:model Category -m
```

Edit file migrasi untuk Categories:

```php
public function up()
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
```

Edit model Category (`app/Models/Category.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

### B. Membuat Model dan Migrasi untuk Produk

```bash
php artisan make:model Product -m
```

Edit file migrasi untuk Products:

```php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description');
        $table->decimal('price', 10, 2);
        $table->integer('stock_quantity');
        $table->string('image')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
}
```

Edit model Product (`app/Models/Product.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'description',
        'price', 'stock_quantity', 'image', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

### C. Membuat Model dan Migrasi untuk Keranjang Belanja

```bash
php artisan make:model Cart -m
```

Edit file migrasi untuk Carts:

```php
public function up()
{
    Schema::create('carts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}
```

```bash
php artisan make:model CartItem -m
```

Edit file migrasi untuk CartItems:

```php
public function up()
{
    Schema::create('cart_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cart_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->timestamps();
    });
}
```

Edit model Cart (`app/Models/Cart.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }
}
```

Edit model CartItem (`app/Models/CartItem.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }
}
```

### D. Membuat Model dan Migrasi untuk Order dan OrderItems

```bash
php artisan make:model Order -m
```

Edit file migrasi untuk Orders:

```php
public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_number')->unique();
        $table->enum('status', ['pending', 'processing', 'completed', 'declined'])->default('pending');
        $table->decimal('total_amount', 10, 2);
        $table->string('payment_method')->nullable();
        $table->string('shipping_address');
        $table->string('shipping_city');
        $table->string('shipping_state');
        $table->string('shipping_zipcode');
        $table->string('shipping_phone');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}
```

```bash
php artisan make:model OrderItem -m
```

Edit file migrasi untuk OrderItems:

```php
public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->decimal('price', 10, 2);
        $table->timestamps();
    });
}
```

Edit model Order (`app/Models/Order.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'status', 'total_amount',
        'payment_method', 'shipping_address', 'shipping_city',
        'shipping_state', 'shipping_zipcode', 'shipping_phone', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

Edit model OrderItem (`app/Models/OrderItem.php`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
```
