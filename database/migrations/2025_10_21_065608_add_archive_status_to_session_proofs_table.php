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
            $table->boolean('is_archived')->default(false)->after('rejection_reason');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->foreignId('archived_by')->nullable()->constrained('users')->after('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_proofs', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at', 'archived_by']);
        });
    }
};
