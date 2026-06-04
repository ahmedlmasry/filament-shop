<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $status
 * @property int|null $parent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Category extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'parent'
    ];
    public $translatable = ['name'];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function getStatusTranslated()
    {
        if (Config::get('app.locale') == 'ar') {
            return $this->status == 1 ? 'مفعل' : 'غير مفعل';
        } else {
            return $this->status == 1 ? 'Active' : 'Inactive';
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
}
