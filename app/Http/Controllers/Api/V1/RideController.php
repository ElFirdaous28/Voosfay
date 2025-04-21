<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['rides' => Ride::paginate(10)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_location' => ['required', 'string', 'max:255'],
            'ending_location' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date', 'after:now'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'luggage_allowed' => ['required', 'boolean'],
            'pet_allowed' => ['required', 'boolean'],
            'conversation_allowed' => ['required', 'boolean'],
            'music_allowed' => ['required', 'boolean'],
        ]);

        $user = auth()->user();

        $ride = $user->offeredRides()->create([
            'start_location' => $validated['start_location'],
            'ending_location' => $validated['ending_location'],
            'start_time' => $validated['start_time'],
            'available_seats' => $validated['available_seats'],
            'price' => $validated['price'],
            'luggage_allowed' => $validated['luggage_allowed'],
            'pet_allowed' => $validated['pet_allowed'],
            'conversation_allowed' => $validated['conversation_allowed'],
            'music_allowed' => $validated['music_allowed'],
            'status' => 'available', // default status
        ]);

        return response()->json([
            'message' => 'Ride created successfully.',
            'ride' => $ride,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ride = Ride::findOrFail($id);
        return response()->json([
            'message' => 'Ride updated successfully.',
            'ride' => $ride,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'start_location' => ['required', 'string', 'max:255'],
            'ending_location' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date', 'after:now'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'luggage_allowed' => ['required', 'boolean'],
            'pet_allowed' => ['required', 'boolean'],
            'conversation_allowed' => ['required', 'boolean'],
            'music_allowed' => ['required', 'boolean'],
        ]);
        $ride = Ride::findOrFail($id);
        $ride->update($data);

        return response()->json([
            'message' => 'Ride updated successfully.',
            'ride' => $ride,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ride = Ride::findOrFail($id);
        $ride->delete();
        return response()->noContent();
    }
}
