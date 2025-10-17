<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('reports', function (Blueprint $table) {
			$table->id();
			$table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
			$table->string('report_name');
			$table->text('report_summary_text')->nullable();
			$table->decimal('report_overall_score', 8, 2)->nullable();
			$table->timestamp('report_generated_at')->nullable();
			$table->timestamps();
			$table->unique(['club_id', 'student_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('reports');
	}
};


