<?php

namespace App\Http\Controllers;

use App\Models\Plate;
use App\Models\FoodItem;
use App\Models\SubTotal;
use App\Http\Requests\StorePlateRequest;
use App\Http\Requests\UpdatePlateRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\FlaskResponseService;

class PlateController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(public FlaskResponseService $flask_service)
    {
    }

    public function index()
    {
        $user = Auth::user();
        // Get all plates for the user
        $plates = $user->subtotals;
        
        // Initialize totals
        $totalCalories = 0;
        $totalCarb = 0;
        $totalFat = 0;
        $totalProtein = 0;

        // Loop through each plate and sum the totals
        foreach ($plates as $plate) {
            $totalCalories += $plate->sub_calories;
            $totalCarb += $plate->sub_carb;
            $totalFat += $plate->sub_fat;
            $totalProtein += $plate->sub_protein;
        }

        $remaining_calories = $user->needed_calories - $totalCalories;
            
        

        // Prepare the response
        $response = [
            'sub_calories' => $totalCalories,
            'sub_carb' => $totalCarb,
            'sub_fat' => $totalFat,
            'sub_protine' => $totalProtein,
            'remaining_calories' => $remaining_calories,

        ];

        // Return response as JSON
        return response()->json([
            'message' => 'Totals calculated successfully',
            'data' => $response,
        ]);

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
    public function store(StorePlateRequest $request)
    {
        $user = Auth::user();
        // Get the image from the request
        $image = $request->file('image');
        $mass = (int)($request->input('mass'));
    
        $tot_cal = $mass * intval($request['total_calories']) /100 ;
        $tot_fat = $mass * intval($request['total_fat']) /100 ;
        $tot_carbs =  $mass * intval($request['total_carb']) /100 ;
        $tot_protein =  $mass * intval($request['total_protein'])/ 100 ;
        $category = $request['category'];
        // Save the image file name 
        $filename = $image->store('images');
        //return response->json();
        $plate_data = [
            'total_calories' => (string)($tot_cal),
            'total_carb' => (string) ($tot_carbs),
            'total_fat' =>  (string) ($tot_fat),
            'total_protine' => (string) ($tot_protein),
            'name' => $category,
            'total_mass' => $mass,
            'image' => $filename,
            'user_id' => $user->id,
            ];
            $plate = Plate::create($plate_data);
            
            $sub_data = [
                'sub_calories' => $plate->total_calories,
                'sub_carb' =>$plate->total_carb,
                'sub_fat' => $plate->total_fat, 
                'sub_protein' => $plate->total_protine,
                'user_id' => $user->id,
                ];
            
                
                $sub = SubTotal::create($sub_data);
                

        


        
        $response = [
            'message' => 'Created successfully',
            'data' => $plate,
            'category' => $plate->name,
        ];
        
        // Return response
        return response()->json($response);
    }

    

    /**
     * Display the specified resource.
     */
    public function show(StorePlateRequest $request)
    {
        $user = Auth::user();
        // Get the image from the request
        $image = $request->file('image');
        // Save the image file name 
        $filename = $image->store('images');
        $mass = (int)($request->input('mass'));   

        $responseFlask = $this->flask_service->store($request);
        
        
        $tot_cal =  (int)($responseFlask['calorie'] * $mass);
        $tot_fat =  (int)($responseFlask['fat'] * $mass);
        $tot_protein =  (int) ($responseFlask['protein'] * $mass);
        $tot_carbs =  (int) ($responseFlask['carbs'] * $mass);
        $ingrnew = array_map('strval', $responseFlask['ingredients']);
        $plate_data = [
            'total_calories' => $tot_cal,
            'total_carb' => $tot_carbs,
            'total_fat' => $tot_fat,
            'total_protine' => $tot_protein,
            'total_mass' => 100,
            'image' => $filename,
            'user_id' => $user->id,
            'category'=> $responseFlask['category'],
            'ingredients' => $ingrnew,
        
            



            ];
            
        return response()->json($plate_data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plate $plate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlateRequest $request, Plate $plate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plate $plate)
    {
        //
    }

    public function history(StorePlateRequest $request)
    {
        $user = Auth::user();
    $plates = $user->plates;

    // Make user_id hidden and format created_at
    $plates->each(function ($plate) {
        $plate->makeHidden('user_id');
        $plate->created_at = $plate->created_at->format('Y-m-d H:i:s'); // Adjust format as needed
    });

    return response()->json($plates);
    }

    public function manual_store (StorePlateRequest $request )
    {
        $user = Auth::user();
        $mass = (int)($request->input('mass'));   
        $foodItemId = request('id'); 

        
            $foodItem = FoodItem::findOrFail($foodItemId);
        
        
        

        $tot_cal = (int) ($foodItem->cal_per_g * $mass);
        $tot_carb = (int) ($foodItem->carb_per_g * $mass);
        $tot_fat = (int) ($foodItem->fat_per_g * $mass);
        $tot_protein = (int) ($foodItem->protein_per_g * $mass);
        
        $plate_data = [
            'total_calories' => $tot_cal,
            'total_carb' => $tot_carb,
            'total_fat' => $tot_fat, 
            'total_protine' => $tot_protein,
            'total_mass' => $mass,
            'user_id' => $user->id,
            'name' => $foodItem->food_name,
            ];

            $plate = Plate::create($plate_data);
            
            $sub_data = [
                'sub_calories' => $plate->total_calories,
                'sub_carb' =>$plate->total_carb,
                'sub_fat' => $plate->total_fat, 
                'sub_protein' => $plate->total_protine,
                'user_id' => $user->id,
                ];
            
                
                $sub = SubTotal::create($sub_data);
                $plate->makeHidden('user_id');
                
        $response = [
            'message' => 'Created successfully',
            'food_item' => $foodItem->food_name,
            'data' => $plate,
        ];
        
        // Return response
        return response()->json($response);
    

    }
}
