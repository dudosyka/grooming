<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function auth(Request  $request) {
        return response()->json(User::auth($request->all()));
    }

    public function reg(Request $request) {
        return response()->json(User::reg($request->all()));
    }
}
