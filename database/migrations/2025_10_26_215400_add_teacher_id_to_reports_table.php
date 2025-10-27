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
            // Add teacher_id column if it doesn't exist
            if (!Schema::hasColumn('reports', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('student_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('reports', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
            }
            
            // Drop the column
            if (Schema::hasColumn('reports', 'teacher_id')) {
                $table->dropColumn('teacher_id');
            }
        });
    }
};
