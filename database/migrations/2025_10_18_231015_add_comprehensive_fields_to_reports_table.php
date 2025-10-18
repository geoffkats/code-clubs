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
        Schema::table('reports', function (Blueprint $table) {
            // Student identification
            $table->string('student_initials', 3)->nullable()->after('report_generated_at');
            
            // Coding skills assessment scores (1-10 scale)
            $table->integer('problem_solving_score')->nullable()->after('student_initials');
            $table->integer('creativity_score')->nullable()->after('problem_solving_score');
            $table->integer('collaboration_score')->nullable()->after('creativity_score');
            $table->integer('persistence_score')->nullable()->after('collaboration_score');
            
            // Project and learning details
            $table->json('scratch_project_ids')->nullable()->after('persistence_score');
            $table->string('favorite_concept', 255)->nullable()->after('scratch_project_ids');
            $table->text('challenges_overcome')->nullable()->after('favorite_concept');
            $table->text('special_achievements')->nullable()->after('challenges_overcome');
            $table->text('areas_for_growth')->nullable()->after('special_achievements');
            $table->text('next_steps')->nullable()->after('areas_for_growth');
            $table->text('parent_feedback')->nullable()->after('next_steps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'student_initials',
                'problem_solving_score',
                'creativity_score',
                'collaboration_score',
                'persistence_score',
                'scratch_project_ids',
                'favorite_concept',
                'challenges_overcome',
                'special_achievements',
                'areas_for_growth',
                'next_steps',
                'parent_feedback',
            ]);
        });
    }
};