<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    public $translatable = ['name', 'desc', 'small_desc'];
    public $guarded = [];
    public $casts = [
        'has_variant' => 'boolean',
    ];
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
    
//     public function orderItems()
//     {
//         return $this->hasMany(OrderItem::class);
//     }
}
