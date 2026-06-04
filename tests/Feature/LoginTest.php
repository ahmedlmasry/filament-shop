<?php

use App\Models\City;
use App\Models\User;

it('logs in a verified user with valid credentials', function () {
    $city = City::factory()->create();
    $user = User::factory()->create([
        'email' => 'a@b.com',
        'password' => 'password123',
        'mobile' => '123456789',
        'city_id' => $city->id,
        'email_verified_at' => now(),
    ]);

    $response = $this->postJson('/api/v1/user/login', [
        'email' => 'a@b.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['status', 'message', 'data' => ['user', 'token']]);
});