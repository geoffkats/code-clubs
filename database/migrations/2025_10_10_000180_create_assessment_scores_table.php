<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('assessment_scores', function (Blueprint $table) {
			$table->id();
			$table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
			$table->decimal('score_value', 8, 2)->nullable();
			$table->decimal('score_max_value', 8, 2)->default(100);
			$table->timestamps();
			$table->unique(['assessment_id', 'student_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('assessment_scores');
	}
};


