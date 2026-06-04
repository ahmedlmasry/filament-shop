<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class BrandResource extends BaseResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->getImageUrl($this->logo,'public'),
        ];
    }
}
