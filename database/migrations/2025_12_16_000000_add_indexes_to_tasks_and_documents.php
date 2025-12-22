<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // فهرس مركب جديد فقط (status و priority موجودان منفصلان في create_tasks_table)
            $table->index(['status', 'priority']);
        });

        Schema::table('documents', function (Blueprint $table) {
            // فهرس جديد لـ title فقط (user_id و assignee_id موجودان في create_documents_table)
            $table->index('title'); // لـ LIKE search
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_status_priority_index');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('documents_title_index');
        });
    }
};
