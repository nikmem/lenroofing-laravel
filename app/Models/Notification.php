<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = [
		'estimate_id',
		'text',
	];

	public function estimate()
	{
		return $this->belongTo(Estimate::class);
	}

	static public function store($text, $estimate_id)
	{
		return self::create([
			'text' => $text,
			'estimate_id' => $estimate_id,
		]);
	}
}
