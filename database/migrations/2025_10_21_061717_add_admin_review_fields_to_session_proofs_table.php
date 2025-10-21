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
        Schema::table('session_proofs', function (Blueprint $table) {
            // Add admin review fields
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending')->after('processing_status');
            $table->text('description')->nullable()->after('proof_type'); // Teacher's description of the proof
            $table->text('admin_comments')->nullable()->after('status'); // Admin's review comments
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->after('admin_comments'); // Admin who reviewed
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by'); // When it was reviewed
            $table->text('rejection_reason')->nullable()->after('reviewed_at'); // Reason for rejection
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_proofs', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'description', 
                'admin_comments',
                'reviewed_by',
                'reviewed_at',
                'rejection_reason'
            ]);
        });
    }
};
