<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('facilitator_id')->nullable()->after('school_id')->constrained('users')->nullOnDelete();
            
            // Indexes for performance
            $table->index('user_role');
            $table->index('facilitator_id');
            $table->index(['user_role', 'facilitator_id']);
            $table->index('school_id');
        });

        // Update user_role enum to include new roles
        // Note: This is handled via raw SQL as Laravel doesn't support enum modification directly
        DB::statement("ALTER TABLE users MODIFY COLUMN user_role ENUM('admin', 'super_admin', 'trainer', 'facilitator', 'teacher', 'student') DEFAULT 'trainer'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['facilitator_id']);
            $table->dropIndex(['user_role']);
            $table->dropIndex(['facilitator_id']);
            $table->dropIndex(['user_role', 'facilitator_id']);
            $table->dropIndex(['school_id']);
            $table->dropColumn('facilitator_id');
        });

        // Revert user_role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN user_role ENUM('admin', 'super_admin', 'trainer') DEFAULT 'trainer'");
    }
};
