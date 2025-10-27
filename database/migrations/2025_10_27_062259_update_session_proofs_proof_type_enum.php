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
        // Update the proof_type ENUM to include 'document'
        DB::statement("ALTER TABLE session_proofs MODIFY COLUMN proof_type ENUM('photo', 'video', 'document') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the proof_type ENUM back to original values
        DB::statement("ALTER TABLE session_proofs MODIFY COLUMN proof_type ENUM('photo', 'video') NOT NULL");
    }
};
