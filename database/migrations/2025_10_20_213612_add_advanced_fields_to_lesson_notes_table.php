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
        Schema::table('lesson_notes', function (Blueprint $table) {
            $table->string('tags')->nullable()->after('visibility');
            $table->string('link_title')->nullable()->after('tags');
            $table->text('video_url')->nullable()->after('link_title');
            $table->text('external_url')->nullable()->after('video_url');
            $table->text('code_url')->nullable()->after('external_url');
            $table->string('code_branch')->nullable()->after('code_url');
            $table->text('audio_url')->nullable()->after('code_branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_notes', function (Blueprint $table) {
            $table->dropColumn([
                'tags',
                'link_title',
                'video_url',
                'external_url',
                'code_url',
                'code_branch',
                'audio_url'
            ]);
        });
    }
};
