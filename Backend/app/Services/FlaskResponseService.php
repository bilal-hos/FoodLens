<?php


namespace App\Services;
use App\Models\Plate;
use GuzzleHttp\Client;
use App\Http\Requests\StorePlateRequest;
class  FlaskResponseService

{
    

    public function store(StorePlateRequest $request)
    {
         // Validate the request
        $request->validate([
            'image' => 'required|image'
        ]);

        // Prepare the image file for sending
        $imagePath = $request->file('image')->getPathname();
        $imageName = $request->file('image')->getClientOriginalName();
        //return response()->json($imageName);
        // Send the image to the Flask API
        $client = new Client();
        $response = $client->post('http://localhost:5000/predict', [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen($imagePath, 'r'),
                    'filename' => $imageName,
                ]
            ]
        ]);

        // Get the response from the Flask API
        $nutritionValues = json_decode($response->getBody(), true);
        return $nutritionValues;
        // Return the response to the frontend or further process it
        //return response()->json($nutritionValues);
    }

}