<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('assessments', function (Blueprint $table) {
			$table->id();
			$table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
			$table->string('assessment_type');
			$table->string('assessment_name');
			$table->unsignedTinyInteger('assessment_week_number')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('assessments');
	}
};


