<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\MyHelper;
use Illuminate\Http\Request;
use App\models\User;
use Auth;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Token')->plainTextToken;
            $user->makeHidden('password');
            return MyHelper::success([
                'token' => $token, 
                'user' => $user
            ], 'Login successful', 200);
        }   
        
        return MyHelper::error([], 'Invalid credentials', 401);

        }
    }

