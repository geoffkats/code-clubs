<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->foreignId('facilitator_id')->nullable()->after('school_id')->constrained('users')->nullOnDelete();
            
            $table->index('facilitator_id');
            $table->index(['school_id', 'facilitator_id']);
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropForeign(['facilitator_id']);
            $table->dropIndex(['facilitator_id']);
            $table->dropIndex(['school_id', 'facilitator_id']);
            $table->dropColumn('facilitator_id');
        });
    }
};
