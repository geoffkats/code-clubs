<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('students', function (Blueprint $table) {
			$table->id();
			$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
			$table->string('student_first_name');
			$table->string('student_last_name');
			$table->string('student_grade_level')->nullable();
			$table->string('student_parent_name')->nullable();
			$table->string('student_parent_email')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('students');
	}
};


