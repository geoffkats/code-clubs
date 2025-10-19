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
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->text('submission_text')->nullable()->after('score_max_value');
            $table->string('submission_file_path')->nullable()->after('submission_text');
            $table->string('submission_file_name')->nullable()->after('submission_file_path');
            $table->enum('status', ['pending', 'submitted', 'graded'])->default('submitted')->after('submission_file_name');
            $table->text('admin_feedback')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropColumn(['submission_text', 'submission_file_path', 'submission_file_name', 'status', 'admin_feedback']);
        });
    }
};
