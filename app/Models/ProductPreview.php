<?php

namespace App\Models;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductPreview extends Model
{

    protected $fillable = ['comment','user_id','product_id'];
    // relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
