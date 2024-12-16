<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Models
use App\Models\User;
use App\Constants\Role;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$user = User::create([
			'name' => 'admin',
			'email' => 'admin@admin.com',
			'password' => \Hash::make('Hello123$'),
			'role' => Role::ADMIN,
			'email_verified_at' => now()
		]);
	}
}
