<?php

namespace Database\Factories;

use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationSetting>
 */
class NotificationSettingFactory extends Factory
{
    protected $model = NotificationSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'email_notifications' => $this->faker->boolean(80), // 80% enabled
            'instant_notifications' => $this->faker->boolean(70), // 70% enabled
            'sms_notifications' => $this->faker->boolean(20), // 20% enabled
        ];
    }
}
