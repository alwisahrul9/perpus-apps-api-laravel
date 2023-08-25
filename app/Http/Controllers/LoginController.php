<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'Validation' => 'Validasi gagal!'
            ]);
        }

        $data = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'remember_token' => Str::random(60),
                ]);
        
        return response()->json([
            'message' => 'Data tersimpan!',
            'data' => $data
        ]);
    }

    public function login(Request $request) {

        $validation = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($validation)) {

            return response()->json([
                'message' => true,
            ]);
            
        } else {
            return response()->json([
                'message' => false,
            ]);
            
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        // $request->session()->invalidate();
     
        // $request->session()->regenerateToken();

        return response()->json([
            'status' => true
        ]);
    }
}
