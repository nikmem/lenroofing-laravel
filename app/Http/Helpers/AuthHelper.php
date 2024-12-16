<?php

// Carbon
use Ammardev\LaravelWpHashDriver\WordpressHasher;
use App\Models\User;
use Carbon\Carbon;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;

function attemptLogin($request)
{
	$loginCredentials = $request->only('email', 'password');

	if (
		!Auth::attempt(['email' => $loginCredentials['email'], 'password' => $loginCredentials['password']])
	) {
		return response()->json(['message' => Message::INVALID_CREDENTIALS], 401);
	}

	$user = $request->user();
	if ($user->is_deleted)
		return response()->json(['message' => Message::UNAUTHORIZED], 401);

	if ($user->status == 'Suspended')
		return response()->json(['message' => Message::UNAUTHORIZED], 401);

	$tokenResult = $user->createToken('Personal Access Token');

	$token = $tokenResult->token;
	if ($request->remember_me)
		$token->expires_at = null; //Carbon::now()->addWeeks(1);
	$token->save();


	$result = [
		'id' => $user->id,
		'name' => $user->name,
		'email' => $user->email,
		'role' => $user->role,
		'token_type' => 'Bearer',
		'expires_at' => null,
		//Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
		'access_token' => $tokenResult->accessToken,
		'email_verified_at' => $user->email_verified_at,
	];
	return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
}
