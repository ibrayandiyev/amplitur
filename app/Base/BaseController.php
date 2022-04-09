<?php

namespace App\Base;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    public $repository      = null;

    /**
     * return success response.
     *
     * @param   String     $result    [$result description]
     * @param   String     $message  [$message description]
     *
     * @return  \Illuminate\Http\Response               [return description]
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
     * @param   String     $error   [$error description]
     * @param   Array     $errorMessages  [$errorMessages description]
     * @param   Integer   $code     [$code description]
     *
     * @return  \Illuminate\Http\Response               [return description]
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
