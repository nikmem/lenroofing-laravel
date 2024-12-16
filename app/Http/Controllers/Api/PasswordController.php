<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\User;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;
use App\Constants\General;

// Libraries
use Hash;

// Mail
use Mail;

// Mailable
use App\Mail\ForgotPassword as ForgotPasswordMail;

class PasswordController extends Controller
{
	/**
	 * Forgot Password
	 *
	 * @param  [string] email
	 * @return [string] message
	 * @return [object] result
	 */
	public function forgot(Request $request)
	{

		$user = User::findUserByEmail($request->email);
		if (empty($user))
			return makeResponse(ResponseCode::NOT_SUCCESS, Message::USER_NOT_EXISTED);

		$email = $user->email;

		try {
			$token = \Hash::make($email);
			$url = url('/reset-password', [$token, $email]);
			$body = [
				"url" => $url,
				"email" => $email,
				"name" => $user->first_name . ' ' . $user->last_name
			];

			Mail::to($email)->send(new ForgotPasswordMail($body));
		} catch (\Exception $e) {
			return makeResponse(ResponseCode::ERROR, $e->getMessage());
		}

		return makeResponse(ResponseCode::SUCCESS, Message::FORGOT_SUCCESS, $user);
	}

	/**
	 * Reset Password
	 *
	 * @param  [string] email
	 * @param  [string] password
	 * @return [string] message
	 * @return [object] result
	 */
	public function reset(Request $request)
	{
		$validator = validateData($request, 'RESET_PASSWORD');
		if ($validator['status'])
			return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);
		\DB::beginTransaction();
		try {
			$user = User::where('email', $request->email)->first();
			if (empty($user))
				return makeResponse(ResponseCode::NOT_SUCCESS, Message::USER_NOT_EXISTED);

			$user->password = bcrypt($request->password);
			$user->save();

			\DB::commit();
		} catch (\Exception $e) {
			\DB::rollBack();
			return makeResponse(ResponseCode::ERROR, $e->getMessage());

		}

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL);
	}


	/**
	 * Change Password
	 *
	 * @param  [string] old_password
	 * @param  [string] new_password
	 * @return [string] message
	 * @return [object] result
	 */
	public function change(Request $request)
	{
		$auth_user = $request->user();

		$validator = validateData($request, 'CHANGE_PASSWORD');
		if ($validator['status'])
			return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);

		\DB::beginTransaction();
		try {
			if (!Hash::check($request->old_password, $auth_user->password))
				return makeResponse(ResponseCode::NOT_SUCCESS, Message::INVALID_OLD_PASSWORD);

			$user = User::where('id', $auth_user->id)
				->update([
					'password' => bcrypt($request->new_password),
				]);
			\DB::commit();
		} catch (\Exception $e) {
			\DB::rollBack();
			return makeResponse(ResponseCode::ERROR, $e->getMessage());

		}
		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $user);
	}
}