<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function auth(Request  $request) {
        return response()->json(User::auth($request->all()));
    }

    public function reg(Request $request) {
        return response()->json(User::reg($request->all()));
    }

    public function getRequests(Request $request) {
        $data = $request->all();
        if (key_exists('status', $data)) {
            return response()->json([
                'user_requests' => [
                    'status' => 'success',
                    'data' => UserRequest::getByUserStatusFilter($request->user()->id, $data['status']),
                ]
            ]);
        }
        return response()->json([
            'user_requests' => [
                'status' => 'success',
                'data' => UserRequest::getByUser($request->user()->id),
            ]
        ]);
    }
}
