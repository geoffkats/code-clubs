<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('sessions_schedule', function (Blueprint $table) {
			$table->id();
			$table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
			$table->unsignedTinyInteger('session_week_number');
			$table->date('session_date')->nullable();
			$table->timestamps();
			$table->unique(['club_id', 'session_week_number']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('sessions_schedule');
	}
};


