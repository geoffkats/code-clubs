<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the visibility enum to include all options
        DB::statement("ALTER TABLE lesson_notes MODIFY COLUMN visibility ENUM('all', 'teachers_only', 'students_only', 'private') DEFAULT 'all'");
        
        // Also update attachment_type enum to include all the new types
        DB::statement("ALTER TABLE lesson_notes MODIFY COLUMN attachment_type ENUM('video', 'document', 'image', 'link', 'audio', 'code', 'quiz', 'assignment', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE lesson_notes MODIFY COLUMN visibility ENUM('all', 'teachers_only') DEFAULT 'all'");
        DB::statement("ALTER TABLE lesson_notes MODIFY COLUMN attachment_type ENUM('pdf', 'video', 'link', 'image', 'document', 'other') DEFAULT 'other'");
    }
};