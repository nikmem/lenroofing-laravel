<?php


/**
 * Make json object.
 *
 * @param  [integer] code
 * @param  [string] message
 * @param  [boolean] status
 * @param  [string] errors (optional)
 * @param  [object] data
 * @return [string] message
 */
function makeResponse($code, $message, $result=null, $errors=null)
{
    return response()->json([
        'server_maintenance' => env('SERVER_MAINTENANCE'),
        'app_update' => env('APP_UPDATE'),
        'message' => $message,
        'errors' => $errors,
        'result' => $result,
    ], $code);
}