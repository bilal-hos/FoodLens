<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestFlaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        // Return the response to the frontend or further process it
        return response()->json($nutritionValues);
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
