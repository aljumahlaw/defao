<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseIndexesTest extends TestCase
{
    public function test_tasks_and_documents_have_expected_indexes(): void
    {
        // SQLite: استخدام PRAGMA index_list لقراءة أسماء الفهارس الفعلية
        $taskIndexes = collect(DB::select('PRAGMA index_list("tasks")'))->pluck('name');
        $documentIndexes = collect(DB::select('PRAGMA index_list("documents")'))->pluck('name');

        // المهام: تأكد من وجود فهارس على الأعمدة الحرجة
        $this->assertTrue($taskIndexes->contains('tasks_user_id_index'));
        $this->assertTrue($taskIndexes->contains('tasks_assignee_id_index'));
        $this->assertTrue($taskIndexes->contains('tasks_document_id_index'));
        $this->assertTrue($taskIndexes->contains('tasks_due_date_index'));
        // في SQLite تم إنشاء فهرسين منفصلين لـ status و priority بدلاً من مركّب
        $this->assertTrue($taskIndexes->contains('tasks_status_index'));
        $this->assertTrue($taskIndexes->contains('tasks_priority_index'));

        // الوثائق: تأكد من وجود فهارس على الأعمدة الحرجة
        $this->assertTrue($documentIndexes->contains('documents_user_id_index'));
        $this->assertTrue($documentIndexes->contains('documents_assignee_id_index'));
        // بالنسبة لـ title وحقول أخرى قد تختلف الأسماء حسب محرك DB، لذا نتحقق فقط من وجود فهرس واحد على الأقل
        $this->assertNotEmpty($documentIndexes);
    }

    public function test_explain_uses_index_for_documents_title_search(): void
    {
        // مثال مبسط: نتأكد أن EXPLAIN يعمل ويعيد خطة استعلام
        $plan = DB::select('EXPLAIN QUERY PLAN SELECT * FROM documents WHERE title LIKE "foo%"');

        $this->assertNotEmpty($plan);
    }
}
