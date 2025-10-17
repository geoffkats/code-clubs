<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('report_access_codes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
			$table->string('access_code_hash');
			$table->string('access_code_plain_preview')->nullable();
			$table->timestamp('access_code_expires_at')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('report_access_codes');
	}
};


