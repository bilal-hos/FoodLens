<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use App\models\User;

class RegisterController extends Controller
{

    

    public function register(StoreUserRequest $request)
    {

    
        $validatedData = $request->validated();

        
        
        // Merge the hashed password into the validated data
    $validatedData['password'] = Hash::make($validatedData['password']);

       // Create the user using the merged data
    $validatedData['needed_calories'] = null;
    $user = User::create($validatedData);
    $user->makeHidden('password');
    
      // Generate a token for the user
    $token = $user->createToken('API TOKEN')->plainTextToken;
    
    return response()->json([
        'status' => 'success',
        'message' => 'User registered successfully',
        'user' => $user,
        'access_token' => $token,
        'token_type' => 'Bearer',
    ], 201);
    }
}
