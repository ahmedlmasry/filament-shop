<?php

if (!function_exists('apiResponse')) {
    function apiResponse($status, $message, $data = null)
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

}
if (!function_exists('errorResponse')) {
    function errorResponse($status, $message, $errors = null)
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
