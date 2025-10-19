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
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->string('question_type'); // multiple_choice, practical_project, image_question, text_question
            $table->text('question_text');
            $table->json('question_options')->nullable(); // For multiple choice questions
            $table->text('correct_answer')->nullable(); // For multiple choice and text questions
            $table->text('project_instructions')->nullable(); // For practical projects
            $table->json('project_requirements')->nullable(); // For practical projects
            $table->string('project_output_format')->nullable(); // For practical projects (e.g., 'scratch_project', 'python_file', 'html_file')
            $table->text('image_description')->nullable(); // For image-based questions
            $table->unsignedInteger('points')->default(1);
            $table->unsignedInteger('order')->default(0); // For ordering questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
    }
};