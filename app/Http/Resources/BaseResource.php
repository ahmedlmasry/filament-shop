<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BaseResource extends JsonResource
{
    public function toArray(Request $request)
    {
       return [];
    }
    public function getImageUrl($image, $disk)
    {
        return $image ? Storage::disk($disk)->url($image) : null;
    }
    public function getImagesUrl($images, $disk)
    {
        return $images->map(function ($image) use ($disk) {
            return $this->getImageUrl($image->file_name, $disk);
        });
    }
}
