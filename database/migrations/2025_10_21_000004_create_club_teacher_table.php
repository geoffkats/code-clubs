<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create pivot table for teachers assigned to clubs
        Schema::create('club_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete(); // References users table
            $table->timestamps();
            
            $table->unique(['club_id', 'teacher_id']);
            $table->index(['teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_teacher');
    }
};
