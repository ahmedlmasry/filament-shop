<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Override;

class Slider extends Model
{
    protected $fillable = ['file_name', 'note'];

    public function ScopeActive($query)
    {
        return $query->where('status', 1);
    }
}
