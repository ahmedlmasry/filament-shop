<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'logo' => $this->logo ? Storage::disk('public')->url($this->logo) : null,
            'status' => $this->status,
        ];
    }
}
