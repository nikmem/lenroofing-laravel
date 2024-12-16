<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

// Models
use App\Models\User;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;


class AuthController extends Controller
{


	/**
	 * Login user and create token
	 *
	 * @param  [string] username
	 * @param  [string] password
	 * @param  [boolean] remember_me
	 * @return [string] message
	 * @return [object] result
	 */
	public function login(Request $request)
	{
		$validator = validateData($request, 'LOGIN');
		if ($validator['status'])
			return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);

		$credentials = $request->only('email', 'password');
		if (
			!\Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])
		) {
			return response()->json(['message' => Message::INVALID_CREDENTIALS], 401);
		}

		$user = $request->user();
		// if (!$user->email_verified_at) {
		// 	return response()->json(['message' => Message::EMAIL_NOT_VERIFIED], 401);
		// }

		if ($user->is_deleted)
			return response()->json(['message' => Message::UNAUTHORIZED], 401);

		if ($user->status == 'Suspended')
			return response()->json(['message' => Message::UNAUTHORIZED], 401);

		$tokenResult = $user->createToken('Personal Access Token');
		$token = $tokenResult->token;
		// if ($request->remember_me)
		// 	$token->expires_at = Carbon::now()->addWeeks(1);
		// $token->save();

		$result = [
			'id' => $user->id,
			'name' => $user->name,
			'email' => $user->email,
			'role' => $user->role,
			'token_type' => 'Bearer',
			'access_token' => $tokenResult->accessToken,
		];

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);


	}

	/**
	 * Logout user (Revoke the token)
	 *
	 * @return [string] message
	 */
	public function logout(Request $request)
	{
		$authUser = $request->user();
		$request->user()->token()->revoke();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL);
	}

	/**
	 * Get the authenticated User
	 *
	 * @return [string] message
	 * @return [object] result
	 */
	public function user(Request $request)
	{
		$user = User::find($request->user()->id);
		$result = [
			"info" => $user,
		];
		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
	}
}