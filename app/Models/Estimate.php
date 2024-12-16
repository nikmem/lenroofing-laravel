<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name',
		'address',
		'phone',
		'email',
		'type',
		'cost',
		'status', // In Progress, Draft, Completed
		'last_reviewed',
		'is_archived',
		'is_urgent',
		'items'
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'items' => 'array',
		];
	}
}
