<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('attachment_url')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('attachment_type', ['pdf', 'video', 'link', 'image', 'document', 'other'])->default('other');
            $table->enum('visibility', ['all', 'teachers_only'])->default('all');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['club_id', 'visibility']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_notes');
    }
};
