<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('clubs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
			$table->string('club_name');
			$table->string('club_level')->nullable();
			$table->unsignedTinyInteger('club_duration_weeks')->default(8);
			$table->date('club_start_date')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('clubs');
	}
};


