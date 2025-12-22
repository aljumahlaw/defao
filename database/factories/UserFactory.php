<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicNames = [
            'محمد',
            'رنيم',
            'د.أنس',
            'العنود',
            'د.عبدالرحمن',
            'مالك',
            'عبدالرحمن خالد',
            'ريم محمد',
            'فيصل',
            'لينا سعد',
        ];

        return [
            'name' => $this->faker->randomElement($arabicNames),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'avatar' => null,
            'phone' => $this->faker->optional()->phoneNumber(),
            'department' => $this->faker->optional()->randomElement(['الإدارة', 'المحاسبة', 'الموارد البشرية', 'التقنية', 'المبيعات']),
            'position' => $this->faker->optional()->randomElement(['مدير', 'موظف', 'مشرف', 'محاسب', 'مطور']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
