<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->user),
            'token' => $this->token,
            // 'token_type' => 'Bearer',
            // 'expires_at' => $this->expiresAt->format('Y-m-d\TH:i:s.uP'),
        ];
    }
}
