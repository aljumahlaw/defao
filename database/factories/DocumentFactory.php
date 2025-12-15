<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['incoming', 'outgoing'];
        $stages = ['draft', 'review1', 'proofread', 'finalapproval'];
        $mimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $extension = $this->faker->randomElement($extensions);
        $mimeType = $mimeTypes[array_search($extension, $extensions)];

        return [
            'title' => $this->faker->randomElement([
                'عقد إيجار تجاري - مكتب الرياض',
                'تقرير ربع سنوي Q4 2024',
                'فاتورة خدمات استشارية',
                'عرض أسعار للموردين',
                'مذكرة تفاهم مشترك',
                'تقرير المراجعة الداخلية',
                'وثيقة تأمين شامل',
                'خطاب ضمان بنكي',
                'محضر اجتماع مجلس الإدارة',
                'شهادة الامتثال القانوني',
            ]),
            'type' => $this->faker->randomElement($types),
            'description' => $this->faker->optional()->sentence(10),
            'file_name' => 'document_' . $this->faker->unique()->numberBetween(1, 1000) . '.' . $extension,
            'file_size' => $this->faker->randomElement(['512 KB', '856 KB', '1.2 MB', '1.8 MB', '2.3 MB', '2.7 MB', '3.1 MB', '3.4 MB', '4.5 MB']),
            'mime_type' => $mimeType,
            's3_path' => 'documents/' . $this->faker->uuid() . '.' . $extension, // ADR-001: Placeholder
            'current_stage' => $this->faker->randomElement($stages),
            'is_archived' => $this->faker->boolean(20), // 20% archived
            'user_id' => 1, // Will be set in seeder
            'assignee_id' => 1, // Will be set in seeder
        ];
    }
}
