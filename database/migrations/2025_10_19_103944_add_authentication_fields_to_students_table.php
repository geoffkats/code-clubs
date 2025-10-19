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
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->unique()->nullable()->after('student_last_name');
            $table->string('password')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->rememberToken()->after('email_verified_at');
            $table->string('student_id_number')->unique()->nullable()->after('remember_token');
            $table->string('profile_image')->nullable()->after('student_id_number');
            $table->json('preferences')->nullable()->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'password', 
                'email_verified_at',
                'remember_token',
                'student_id_number',
                'profile_image',
                'preferences'
            ]);
        });
    }
};