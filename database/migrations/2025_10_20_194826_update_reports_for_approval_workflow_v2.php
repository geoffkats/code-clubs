<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('reports', 'status')) {
                $table->enum('status', ['pending', 'facilitator_approved', 'admin_approved', 'revision_requested', 'rejected', 'completed'])->default('pending')->after('report_generated_at');
            }
            
            // Add facilitator_id if it doesn't exist
            if (!Schema::hasColumn('reports', 'facilitator_id')) {
                $table->foreignId('facilitator_id')->nullable()->after('club_id')->constrained('users')->nullOnDelete();
            }
            
            // Add admin_id if it doesn't exist
            if (!Schema::hasColumn('reports', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->after('facilitator_id')->constrained('users')->nullOnDelete();
            }
            
            // Add feedback columns if they don't exist
            if (!Schema::hasColumn('reports', 'facilitator_feedback')) {
                $table->text('facilitator_feedback')->nullable();
            }
            
            if (!Schema::hasColumn('reports', 'admin_feedback')) {
                $table->text('admin_feedback')->nullable();
            }
            
            if (!Schema::hasColumn('reports', 'facilitator_approved_at')) {
                $table->timestamp('facilitator_approved_at')->nullable();
            }
            
            if (!Schema::hasColumn('reports', 'admin_approved_at')) {
                $table->timestamp('admin_approved_at')->nullable();
            }
        });

        // Add indexes after columns are created
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'status') && Schema::hasColumn('reports', 'facilitator_id')) {
                $table->index(['status', 'facilitator_id']);
            }
            if (Schema::hasColumn('reports', 'status') && Schema::hasColumn('reports', 'admin_id')) {
                $table->index(['status', 'admin_id']);
            }
            if (Schema::hasColumn('reports', 'facilitator_id')) {
                $table->index('facilitator_id');
            }
            if (Schema::hasColumn('reports', 'admin_id')) {
                $table->index('admin_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('reports', 'facilitator_id')) {
                $table->dropForeign(['facilitator_id']);
            }
            if (Schema::hasColumn('reports', 'admin_id')) {
                $table->dropForeign(['admin_id']);
            }
            
            // Drop indexes
            $table->dropIndex(['status', 'facilitator_id']);
            $table->dropIndex(['status', 'admin_id']);
            $table->dropIndex(['facilitator_id']);
            $table->dropIndex(['admin_id']);
            
            // Drop columns
            $columnsToDrop = [];
            if (Schema::hasColumn('reports', 'status')) $columnsToDrop[] = 'status';
            if (Schema::hasColumn('reports', 'facilitator_id')) $columnsToDrop[] = 'facilitator_id';
            if (Schema::hasColumn('reports', 'admin_id')) $columnsToDrop[] = 'admin_id';
            if (Schema::hasColumn('reports', 'facilitator_feedback')) $columnsToDrop[] = 'facilitator_feedback';
            if (Schema::hasColumn('reports', 'admin_feedback')) $columnsToDrop[] = 'admin_feedback';
            if (Schema::hasColumn('reports', 'facilitator_approved_at')) $columnsToDrop[] = 'facilitator_approved_at';
            if (Schema::hasColumn('reports', 'admin_approved_at')) $columnsToDrop[] = 'admin_approved_at';
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};