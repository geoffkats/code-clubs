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
        Schema::create('session_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignId('facilitator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('club_id')->constrained('clubs')->onDelete('cascade');
            
            // Feedback content
            $table->text('content')->nullable(); // General feedback text
            $table->json('suggestions')->nullable(); // Structured suggestions
            $table->enum('feedback_type', ['positive', 'constructive', 'critical', 'mixed'])->default('constructive');
            
            // Rating system (1-5 stars for each aspect)
            $table->tinyInteger('content_delivery_rating')->nullable(); // 1-5
            $table->tinyInteger('student_engagement_rating')->nullable(); // 1-5
            $table->tinyInteger('session_management_rating')->nullable(); // 1-5
            $table->tinyInteger('preparation_rating')->nullable(); // 1-5
            $table->tinyInteger('overall_rating')->nullable(); // 1-5
            
            // Feedback status
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'actioned'])->default('draft');
            
            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['session_id', 'facilitator_id']);
            $table->index(['teacher_id', 'status']);
            $table->index(['club_id', 'created_at']);
            $table->index('overall_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_feedbacks');
    }
};