<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# how to run

```

## install

```
composer install

npm install
```
cp .env.example .env
php artisan key:generate
## migrate

```
php artisan migrate
```

## seeding

```
php artisan db:seed
```
php artisan storage:link
## running

```
// open 2 terminal
// terminal 1
npm run dev
// terminal 2
php artisan serve
```

-   buka di browser

```
http://127.0.0.1:8000
```

        // Electronics Products
        $electronics = $categories->where('name', 'Electronics')->first();
        $this->createProducts($electronics->id, $adminIds, [
            [
                'name' => 'Smartphone X',
                'description' => 'Latest smartphone with advanced features and high-resolution camera.',
                'price' => 5000000,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Powerful laptop for professional use with high performance and long battery life.',
                'price' => 12000000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Premium sound quality with active noise cancellation.',
                'price' => 1500000,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track your fitness and stay connected with smart notifications.',
                'price' => 2500000,
                'stock_quantity' => 45
            ],
        ]);

        // Clothing Products
        $clothing = $categories->where('name', 'Clothing')->first();
        $this->createProducts($clothing->id, $adminIds, [
            [
                'name' => 'Men\'s Casual Shirt',
                'description' => 'Comfortable cotton shirt for casual wear.',
                'price' => 250000,
                'stock_quantity' => 75
            ],
            [
                'name' => 'Women\'s Dress',
                'description' => 'Elegant dress for special occasions.',
                'price' => 450000,
                'stock_quantity' => 60
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic denim jeans with perfect fit.',
                'price' => 350000,
                'stock_quantity' => 80
            ],
            [
                'name' => 'Sports Shoes',
                'description' => 'Comfortable shoes for sports and casual wear.',
                'price' => 650000,
                'stock_quantity' => 40
            ],
        ]);

        // Home & Kitchen Products
        $homeKitchen = $categories->where('name', 'Home & Kitchen')->first();
        $this->createProducts($homeKitchen->id, $adminIds, [
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker for perfect brew every time.',
                'price' => 800000,
                'stock_quantity' => 35
            ],
            [
                'name' => 'Bedding Set',
                'description' => 'Luxurious cotton bedding set for comfortable sleep.',
                'price' => 1200000,
                'stock_quantity' => 25
            ],
            [
                'name' => 'Kitchen Knife Set',
                'description' => 'Professional chef knife set for precision cutting.',
                'price' => 950000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Living Room Lamp',
                'description' => 'Modern design lamp to enhance your living room decor.',
                'price' => 450000,
                'stock_quantity' => 50
            ],
        ]);

        // Books Products
        $books = $categories->where('name', 'Books')->first();
        $this->createProducts($books->id, $adminIds, [
            [
                'name' => 'Novel: The Journey',
                'description' => 'Bestselling novel about an adventure journey.',
                'price' => 150000,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Cookbook Collection',
                'description' => 'Collection of recipes from around the world.',
                'price' => 280000,
                'stock_quantity' => 65
            ],
            [
                'name' => 'Business Strategy Guide',
                'description' => 'Comprehensive guide for business development and strategy.',
                'price' => 320000,
                'stock_quantity' => 45
            ],
            [
                'name' => 'Programming Fundamentals',
                'description' => 'Learn the basics of programming and coding.',
                'price' => 275000,
                'stock_quantity' => 55
            ],
        ]);

        E-commerce Laravel

        php artisan migrate:fresh --seed
        <option value="admin">Admin</option>