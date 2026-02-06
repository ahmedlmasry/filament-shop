<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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

    protected $table = 'categories';

    protected $casts = [
        'status' => 'bool',
        'parent' => 'int',
        'name' => 'array',

    ];

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
}
