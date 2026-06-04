<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;


class BaseController extends Controller
{
    public function apiResponse($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];
        if ($data) {
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }
    public function errorResponse($status, $message, $errors = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];
        if ($errors) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $status);
    }
}
