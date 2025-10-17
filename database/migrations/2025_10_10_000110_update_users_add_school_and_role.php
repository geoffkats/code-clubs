<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->nullOnDelete();
			$table->string('user_name')->nullable()->after('name');
			$table->string('user_role')->default('trainer')->after('password');
		});
	}

	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropConstrainedForeignId('school_id');
			$table->dropColumn(['user_name', 'user_role']);
		});
	}
};


