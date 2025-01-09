<?php
namespace App\Services;

use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        return User::latest()->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'age', 'weight', 'height', 'gender', 'address', 'needed_calories', 'created_at', 'updated_at')->get();
    }
    public function createOrUpdateUser($validatedData)
    {
        $id = $validatedData['id'] ?? null;
        return User::updateOrCreate(['id' => $id], $validatedData);
    }

    public function ShowUserById($id)
    {
        
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'message' => 'User found',
                'user' => $user
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    public function DeleteUserById($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the user',
            ], 500);
        }
    }

    public function updateUser(User $user,array $data )
    {
        // If password is being updated, hash it
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        // Update the user with the validated data
        $user->update($data);
        // Optionally, hide the password attribute
        $user->makeHidden('password');

        return $user;
    }
    

}