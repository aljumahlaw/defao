<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'in_progress', 'completed', 'overdue'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        return [
            'title' => $this->faker->randomElement([
                'مراجعة عقد التوريد',
                'اعتماد الميزانية السنوية',
                'مراجعة تقرير المبيعات',
                'تحديث سياسة الموارد البشرية',
                'إعداد تقرير الأداء الشهري',
                'مراجعة طلبات الإجازات',
                'تحديث قاعدة البيانات',
                'مراجعة العقود المنتهية',
                'إعداد خطة التدريب السنوية',
                'متابعة شكاوى العملاء',
            ]),
            'description' => $this->faker->optional()->sentence(15),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
            'document_id' => null, // Will be set in seeder
            'user_id' => 1, // Will be set in seeder
            'assignee_id' => 1, // Will be set in seeder
        ];
    }
}
