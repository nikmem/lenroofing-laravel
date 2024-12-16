<?php

namespace App\Constants;

class Rule
{
	// Rules According to API's
	private static $rules = [
		'LOGIN' => [
			'email' => 'required|string',
			'password' => 'required|string',
			'remember_me' => 'boolean'
		],
		'SIGNUP' => [
			'name' => 'required|string',
			'email' => 'required|string',
			'password' => 'required|string'
		],
		'ADD_DEVICE_TOKEN' => [
			'value' => 'required',
		],
		'CHANGE_PASSWORD' => [
			'old_password' => 'required',
			'new_password' => 'required',
		],
		'FORGOT_PASSWORD' => [
			'email' => 'required',
		],
		'RESET_PASSWORD' => [
			'email' => 'required',
			'password' => 'required',
		],
		'UPDATE_PROFILE' => [
			'email' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
		],
	];

	public static function get($api)
	{
		return self::$rules[$api];
	}
}