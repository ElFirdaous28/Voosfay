<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Ride;
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
        $pending = Reservation::where('ride_id', $ride)
            ->where('status', 'pending')
            ->get();

        $accepted = Reservation::where('ride_id', $ride)
            ->where('status', 'confirmed')
            ->get();

        return response()->json([
            'pending' => $pending,
            'accepted' => $accepted,
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

        $ride = Ride::find($request->ride_id);

        if ($ride->status !== 'available') {
            return response()->json([
                'message' => 'You cannot reserve a seat on this ride. It is not available.',
            ], 403);
        }

        $currentReservations = $ride->reservations()->where('status', 'confirmed')->count();

        if ($ride->available_seats <= $currentReservations) {
            return response()->json([
                'message' => 'No available seats for this ride.',
            ], 400);
        }

        if ($ride->available_seats - $currentReservations === 1) {
            $ride->update(['status' => 'full']);
        }

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
