<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['Reservations' => Reservation::paginate(10)], 200);
    }

    public function rideReservations(string $ride)
    {
        $reservations = Reservation::where('ride_id', $ride)->get();
        return response()->json([
            'reservations' => $reservations,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ride_id' => ['required', 'integer', 'exists:rides,id'],
        ]);

        $reservation = Reservation::firstOrCreate([
            'user_id' => auth()->id(),
            'ride_id' => $request->ride_id,
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        return response()->json([
            'reservation' => $reservation,
        ], 200);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', Rule::in(['cancelled', 'confirmed', 'rejected', 'completed'])],
        ]);

        $reservation->update(['status' => $request->status]);

        return response()->json([
            'message' => "Reservation status updated to {$request->status}.",
            'reservation' => $reservation,
        ]);
    }
}
