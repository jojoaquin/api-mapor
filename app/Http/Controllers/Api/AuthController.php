<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $data = User::where('username', $request->username)->first();
        if ($data && Hash::check($request->password, $data->password)) {
            return response()->json([
                'message' => 'Login berhasils'
            ], 200);
        }
        return response()->json([
            'message' => 'Login gagal'
        ], 401);
    }
}
