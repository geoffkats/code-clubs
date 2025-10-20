<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users');
            $table->date('session_date');
            $table->time('session_time')->nullable();
            $table->text('session_notes')->nullable();
            $table->timestamps();
            
            // Allow multiple sessions per day with different times
            $table->unique(['club_id', 'session_date', 'session_time']);
            $table->index(['teacher_id', 'session_date']);
            $table->index('club_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_sessions');
    }
};
