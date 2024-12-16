<?php

namespace App\Models;

use App\Scopes\OrderByDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
	use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'role'
	];


	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];


	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	/**
	 * Find a user by email.
	 */
	public static function findUserByEmail($email)
	{
		return self::where('email', $email)->first();
	}


	protected static function booted()
	{
		static::addGlobalScope(new OrderByDescScope);
		// static::addGlobalScope(new NotDeletedScope);
	}
}
