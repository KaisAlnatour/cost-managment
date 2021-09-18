<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function sendSuccess($success, $successMessages = [], $code = 200)
    {$response = [
        'success' => true,
        'message' => $success,
    ];


        if(!empty($successMessages)){
            $response['data'] = $successMessages;
        }


        return response()->json($response, $code);}
}
