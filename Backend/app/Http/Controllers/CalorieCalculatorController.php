<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalorieCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function calc(Request $request)
    {
        $user = Auth::user();
        $activity_level = $request['activity_level'];
    
        
        if ($user->gender == 'male') {
            $bmr = 88.362 + (13.397 * $user->weight) + (4.799 * $user->height) - (5.677 * $user->age);
        } else {
            $bmr = 447.593 + (9.247 * $user->weight) + (3.098 * $user->height) - (4.330 * $user->age);
        }

         // Adjust BMR based on activity level
        switch ($activity_level) {
            case 'sedentary':
                $tdee = round($bmr * 1.2) ;
                break;
            case 'lightly_active':
                $tdee =round ($bmr * 1.375);
                break;
            case 'moderately_active':
                $tdee = round ($bmr * 1.55);
                break;
            case 'very_active':
                $tdee = round ( $bmr * 1.725);
                break;
            case 'super_active':
                $tdee =round($bmr * 1.9);
                break;
            default:
                return response()->json(['error' => 'Invalid activity level'], 400);
        }
        $updated_needed_calories = ['needed_calories' => $tdee];

        $user->update($updated_needed_calories);

        return response()->json(['TDEE' => $tdee], 200);

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
