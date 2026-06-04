<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    public $translatable = ['name', 'desc', 'small_desc'];
    public $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'desc',
        'small_desc',
        'price',
        'discount',
        'has_discount',
        'status',
        'quantity',
        'available_for',
        'has_variants',
        'manage_stock',
        'available_in_stock',
        'stock'
    ];
    // relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productPreviews()
    {
        return $this->hasMany(ProductPreview::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function firstImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->oldestOfMany();
    }

    // Accessories
    public function getAvailableInStockAttribute()
    {
        return $this->quantity > 0;
    }
    public function getPriceAfterDiscountAttribute()
    {
        return $this->price - $this->discount;
    }
    // local scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeHasDiscount($query)
    {
        return $query->where('has_discount', 1);
    }
    public function scopeWithDetails($query)
    {
        return $query->with('images', 'brand', 'category','variants','firstImage');
    }
    public function scopePriceRange($query, $min_price, $max_price)
    {
        return $query->where(function ($q) use ($min_price, $max_price) {
            $q->where(function ($q) use ($min_price, $max_price) {
                $q->where('has_discount', 1)
                    ->whereRaw('(price - discount) BETWEEN ? AND ?', [$min_price, $max_price]);
            })
                ->orWhere(function ($q) use ($min_price, $max_price) {
                    $q->where('has_discount', 0)
                        ->whereBetween('price', [$min_price, $max_price]);
                })
                ->orWhereRelation('variants', fn($q) => $q->whereBetween('price', [$min_price, $max_price]));
        });
    }
}
