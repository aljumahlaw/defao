<?php

namespace Database\Factories;

use App\Models\DocumentActivity;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentActivity>
 */
class DocumentActivityFactory extends Factory
{
    protected $model = DocumentActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actionTypes = ['created', 'uploaded', 'approved', 'rejected', 'forwarded', 'commented', 'archived'];

        return [
            'document_id' => 1, // Will be set in seeder
            'user_id' => 1, // Will be set in seeder
            'action_type' => $this->faker->randomElement($actionTypes),
            'comment' => $this->faker->optional(40)->randomElement([
                'يرجى مراجعة البنود المالية',
                'تمت الموافقة على جميع البنود',
                'يحتاج إلى تعديلات بسيطة',
                'ممتاز، جاهز للموافقة النهائية',
                'يرجى إضافة المزيد من التفاصيل',
            ]),
            'metadata' => null,
        ];
    }
}
