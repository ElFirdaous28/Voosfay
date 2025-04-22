<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stop;
use Illuminate\Http\Request;

class StopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'stops' => Stop::with('ride')->paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ride_id' => ['required', 'exists:rides,id'],
            'place_name' => ['required', 'string'],
            'time' => ['required', 'date'],
        ]);

        $stop = Stop::create($validated);

        return response()->json([
            'message' => 'Stop created successfully',
            'stop' => $stop,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stop = Stop::findOrFail($id);

        return response()->json([
            'stop' => $stop,
        ]);
    }

    // ride stops
    public function rideStops($rideId)
{
    $stops = Stop::where('ride_id', $rideId)->orderBy('time')->get();

    return response()->json([
        'ride_id' => $rideId,
        'stops' => $stops,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'place_name' => ['required', 'string'],
            'time' => ['required', 'date'],
        ]);

        $stop = Stop::findOrFail($id);
        $stop->update($validated);

        return response()->json([
            'message' => 'Stop updated successfully',
            'stop' => $stop,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stop = Stop::findOrFail($id);
        $stop->delete();

        return response()->json([
            'message' => 'Stop deleted successfully',
        ]);
    }
}
