<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'cost_price',
        'pricing_mode',
        'markup_percent',
        'is_featured',
        'featured_start',
        'featured_end',
        'discount_type',
        'discount_value',
        'discount_start',
        'discount_end',
        'stock_quantity',
        'image',
        'status'
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

    // Harga akhir produk (otomatis/diskon)
    public function getFinalPriceAttribute()
    {
        $price = $this->price;
        // Jika pricing_mode auto, hitung harga dari cost_price + markup
        if ($this->pricing_mode === 'auto' && $this->cost_price !== null && $this->markup_percent !== null) {
            $price = $this->cost_price + ($this->cost_price * $this->markup_percent / 100);
        }
        // Jika diskon aktif, hitung harga diskon
        if ($this->discount_type && $this->discount_value && $this->isDiscountActive) {
            if ($this->discount_type === 'percent') {
                $price = $price - ($price * $this->discount_value / 100);
            } elseif ($this->discount_type === 'nominal') {
                $price = max(0, $price - $this->discount_value);
            }
        }
        return $price;
    }

    // Cek apakah produk sedang featured (periode aktif)
    public function getIsFeaturedActiveAttribute()
    {
        if (!$this->is_featured) return false;
        $now = now();
        return ($this->featured_start && $this->featured_end && $now->between($this->featured_start, $this->featured_end));
    }

    // Cek apakah diskon sedang aktif (periode aktif)
    public function getIsDiscountActiveAttribute()
    {
        $now = now();
        return ($this->discount_start && $this->discount_end && $now->between($this->discount_start, $this->discount_end));
    }
}
