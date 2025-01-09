<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\MyHelper;
class UserController extends Controller
{

    public function __construct(public UserService $userService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $users = $this->userService->getAllUsers();
        // Return the users as a JSON response
        return response()->json([
            'status' => True,
            'data' => $users,
            
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        
        $validated_data = $request->validated();
        $id = $validatedData['id'] ?? null;
        $user = User::find($id);
        $isUpdate = $user ? true : false;
        
        $this->userService->createOrUserUpdateUser($validated_data);

        if ($isUpdate) {
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
            
            ], 200);
        }
        else 
        {
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
            
            ], 201);
        }

    
    }

    /**
     * Display the specified resource.
     */
    public function show()
    { 
        $user = Auth::user();
        $user->makeHidden('password');
        return response()->json([
            'status' => 'success',
            'message' => 'User found',
            'user' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {

    $user = Auth::user();
    $validatedData = $request->validated();
    

    if (isset($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    }
    $user->update($validatedData);
    $user->makeHidden('password');
    return response()->json([
        'status' => 'success',
        'message' => 'User updated successfully',
        'user' => $user,
    ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = Auth::user();
        $id = $user->id;
        return $this->userService->DeleteUserById($id);
    }
}
