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
        Schema::table('reports', function (Blueprint $table) {
            // Add the missing scratch_project_ids column
            if (!Schema::hasColumn('reports', 'scratch_project_ids')) {
                $table->json('scratch_project_ids')->nullable()->after('persistence_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'scratch_project_ids')) {
                $table->dropColumn('scratch_project_ids');
            }
        });
    }
};