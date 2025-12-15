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
        Schema::create('document_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('action_type', ['created', 'uploaded', 'approved', 'rejected', 'forwarded', 'commented', 'archived'])->default('created');
            $table->text('comment')->nullable();
            $table->json('metadata')->nullable(); // For additional data
            $table->timestamps();
            
            // Indexes
            $table->index('document_id');
            $table->index('user_id');
            $table->index('action_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_activities');
    }
};
