<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create session attendance table linking sessions to students
        Schema::create('session_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete(); // References students table
            $table->timestamp('attended_at')->useCurrent();
            $table->text('notes')->nullable();
            
            $table->unique(['club_session_id', 'student_id']);
            $table->index('club_session_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_attendance');
    }
};
