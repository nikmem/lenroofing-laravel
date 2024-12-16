<?php

namespace App\Constants;

class Message
{
	// General
	const ALREADY_EXIST = "Already exist!";
	const REQUEST_SUCCESSFUL = "Request Successful!";
	const VALIDATION_FAILED = "Validation Failed!";
	const INVALID_CREDENTIALS = "Invalid Credentials!";
	const UNAUTHORIZED = "Unauthorized!";
	const REQUEST_FAILED = "Request Failed!";
	const NOT_FOUND = "Not Found!";
	const UPDATE_SUCCESSFUL = "Record Updated Successfuly!";
	const DELETE_SUCCESSFUL = "Record Delete Successfully";


	// User
	const USER_NOT_EXISTED = "There is no user with this email!";
	const EMAIL_NOT_VERIFIED = "Your email is not veriffied please verify first";

	// Password
	const FORGOT_SUCCESS = "A code is sent to reset password on your email!";
	const INVALID_CODE = "Invalid Code!";
	const FILE_REQUIRED = "File required!";
	const INVALID_OLD_PASSWORD = "Invalid old password!";
	const EMAIL_NOT_ATTACHED = "No email is attached to following account!";
	const RECORD_NOT_FOUND = "Record Not Found!";
	const FILE_SIZE_EXCEEDED = "File size exceeded";
}
