<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('club_enrollments', function (Blueprint $table) {
			$table->id();
			$table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
			$table->timestamps();
			$table->unique(['club_id', 'student_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('club_enrollments');
	}
};


