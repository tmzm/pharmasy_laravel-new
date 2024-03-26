<?php

namespace App\Http\Controllers;

trait ApiResponse
{
    public function apiResponse($status = null,$msg = null,$data = null,$token = null,$errors = null)
    {
        $array  = [
            'message' => $msg,
            'data' => $data,
            'token' => $token,
            'errors' => $errors
        ];
        return response()->json($array,$status);
    }
}
