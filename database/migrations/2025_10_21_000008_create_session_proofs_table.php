<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('club_sessions')->cascadeOnDelete();
            $table->string('proof_url');
            $table->string('mime_type')->nullable();
            $table->enum('proof_type', ['photo', 'video']);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->enum('processing_status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_proofs');
    }
};
