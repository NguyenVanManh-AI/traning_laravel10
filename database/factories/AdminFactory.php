<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('123456'), // mật khẩu mặc định
            'name' => $this->faker->name,
            'avatar' => $this->faker->imageUrl(200, 200, 'people'), // URL ảnh giả
            'role' => $this->faker->randomElement(['manager', 'superadmin', 'admin']),
            'token_verify_email' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ];
    }
}
