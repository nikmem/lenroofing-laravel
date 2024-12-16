<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// Models
use App\Models\Notification;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;

class NotificationController extends Controller
{
	public function list()
	{

		$estimates = Notification::get();
		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimates);
	}
}
