<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('estimate_items', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('estimate_id');
			$table->enum('type', ['text', 'audio'])->default('text');
			$table->text('content');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('estimate_items');
	}
};
