<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('attendance_records', function (Blueprint $table) {
			$table->id();
			$table->foreignId('session_id')->constrained('sessions_schedule')->cascadeOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
			$table->string('attendance_status');
			$table->string('attendance_notes')->nullable();
			$table->timestamps();
			$table->unique(['session_id', 'student_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('attendance_records');
	}
};


