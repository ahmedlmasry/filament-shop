<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasTranslations;
    protected $fillable = ['name', 'logo', 'status'];
    public $translatable = ['name'];
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    protected static function booted()
    {
        static::deleting(function ($brand) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
        });
        static::updating(function ($brand) {
            if ($brand->isDirty('logo')) {
                $oldLogo = $brand->getOriginal('logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }
        });
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
