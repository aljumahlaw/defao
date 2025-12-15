<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['incoming', 'outgoing'])->default('incoming');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('s3_path')->nullable(); // ADR-001: S3 path placeholder
            $table->enum('current_stage', ['draft', 'review1', 'proofread', 'finalapproval'])->default('draft');
            $table->boolean('is_archived')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // creator
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('type');
            $table->index('current_stage');
            $table->index('is_archived');
            $table->index('user_id');
            $table->index('assignee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
