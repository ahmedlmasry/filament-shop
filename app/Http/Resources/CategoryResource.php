<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'status' => $this->getStatusTranslated(),
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            // 'parent' => $this->parent,
            // 'children' => CategoryResource::collection($this->children)
            
            
        ];
    }
}
