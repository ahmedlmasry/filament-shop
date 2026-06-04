<?php

namespace App\DTOs;

use App\Models\User;

final class AuthResult
{
    public function __construct(
        public User $user,
        public string $token
    ) {
    }
}