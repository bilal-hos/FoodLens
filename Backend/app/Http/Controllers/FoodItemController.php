<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Http\Requests\StoreFoodItemRequest;
use App\Http\Requests\UpdateFoodItemRequest;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = FoodItem::all();
        $search = $request->route('search');
        //return response()->json($search);
        $products = FoodItem::where('food_name', 'like', '%'.$search.'%')->get(); 
        return response()->json($products);
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
    public function store(StoreFoodItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodItem $foodItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodItemRequest $request, FoodItem $foodItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodItem $foodItem)
    {
        //
    }
}
