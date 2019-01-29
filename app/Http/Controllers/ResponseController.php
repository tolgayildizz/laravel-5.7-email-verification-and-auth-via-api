<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    //
    public function sendResponse($data  = NULL, $message = NULL, $code = 200)
    {
        $response = $data;

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404) // , $errorMessages = []
    {
        $response = [
            // 'success' => false,
            'message' => $error
        ];

        return response()->json($response, $code);
    }
}
