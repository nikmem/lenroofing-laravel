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
		Schema::create('estimates', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('address');
			$table->string('phone');
			$table->string('email');
			$table->enum('type', ['Individual', 'Org']);
			$table->decimal('cost', 10, 2);
			$table->string('status');
			$table->tinyInteger('is_archived')->default(0);
			$table->tinyInteger('is_urgent')->default(0);
			$table->timestamp('last_reviewed')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('estimates');
	}
};
