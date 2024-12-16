<?php


namespace App\Http\Helpers;


class Helper
{
    // TODO: This method constructs and sends a JSON response indicating a server error.
    // TODO: It takes an error message, an optional array of additional error messages, and an HTTP status code.
    // TODO: It returns a JSON response with the provided error message(s) and status code.
    public static function sendSeverError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['payload'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
