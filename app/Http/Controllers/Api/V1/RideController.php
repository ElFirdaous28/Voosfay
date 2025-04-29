<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\RatingsHelper;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['rides' => Ride::paginate(10)], 200);
    }

    public function offeredRides()
    {
        $rides = Auth::user()->offeredRides;
        return response()->json(['offerd Rides history' => $rides]);
    }

    public function joinedRides()
    {
        $rides = Auth::user()->joinedRides;
        return response()->json(['offerd Rides history' => $rides]);
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
            'ending_time' => ['required', 'date', 'after:now'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'luggage_allowed' => ['required', 'boolean'],
            'pet_allowed' => ['required', 'boolean'],
            'conversation_allowed' => ['required', 'boolean'],
            'music_allowed' => ['required', 'boolean'],
            'food_allowed' => ['required', 'boolean'],
        ]);

        $user = auth()->user();

        $ride = $user->offeredRides()->create([
            'start_location' => $validated['start_location'],
            'ending_location' => $validated['ending_location'],
            'start_time' => $validated['start_time'],
            'ending_time' => $validated['ending_time'],
            'available_seats' => $validated['available_seats'],
            'price' => $validated['price'],
            'luggage_allowed' => $validated['luggage_allowed'],
            'pet_allowed' => $validated['pet_allowed'],
            'conversation_allowed' => $validated['conversation_allowed'],
            'music_allowed' => $validated['music_allowed'],
            'food_allowed' => $validated['food_allowed'],
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
        $ride = Ride::with('user:id,name,picture')->findOrFail($id);
        return response()->json([
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
            'ending_time' => ['required', 'date', 'after:now'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'luggage_allowed' => ['required', 'boolean'],
            'pet_allowed' => ['required', 'boolean'],
            'conversation_allowed' => ['required', 'boolean'],
            'music_allowed' => ['required', 'boolean'],
            'food_allowed' => ['required', 'boolean'],
        ]);
        $ride = Ride::findOrFail($id);
        $ride->update($data);

        return response()->json([
            'message' => 'Ride updated successfully.',
            'ride' => $ride,
        ], 200);
    }

    public function updateStatus(Request $request, Ride $ride)
    {
        $request->validate([
            'status' => ['required', Rule::in(['full', 'in_progress', 'completed', 'cancelled'])],
        ]);

        $ride->update(['status' => $request->status]);

        return response()->json([
            'message' => "Reservation status updated to {$request->status}.",
            'ride' => $ride,
        ]);
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

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'start_location' => ['nullable', 'string'],
            'ending_location' => ['nullable', 'string'],
            'available_seats' => ['nullable', 'integer'],
            'start_time' => ['nullable', 'date'],
            'sort' => ['nullable', 'in:price_asc,price_desc,start_time,avg_rating'],
            'pet_allowed' => ['nullable', 'boolean'],
            'luggage_allowed' => ['nullable', 'boolean'],
            'conversation_allowed' => ['nullable', 'boolean'],
            'music_allowed' => ['nullable', 'boolean'],
            'food_allowed' => ['nullable', 'boolean'],
            'offset' => ['nullable', 'integer', 'min:0'],
        ]);

        $query = Ride::query();

        $this->applyFilters($query, $validatedData);
        $this->applySorting($query, $validatedData);

        $limit = 3; // hardcoded
        $offset = $validatedData['offset'] ?? 0;

        $rides = $query->where('status','available')->limit($limit)->offset($offset)->get();
        $rides->transform(function ($ride) {
            $ride->rating_average = RatingsHelper::userAverageRating($ride->user_id) ?? 0;
            $ride->driver_name = $ride->user->name;
            return $ride;
        });

        return response()->json([
            'rides' => $rides,
            'next_offset' => $offset + $limit
        ], 200);
    }

    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['start_location'])) {
            $query->where('start_location', 'like', $filters['start_location'] . '%');
        }

        if (!empty($filters['ending_location'])) {
            $query->where('ending_location', 'like', $filters['ending_location'] . '%');
        }

        if (!empty($filters['start_time'])) {
            $date = Carbon::parse($filters['start_time'])->toDateString();
            $query->whereDate('start_time', $date);
        }
        if (!empty($filters['available_seats'])) {
            $query->where('available_seats', '>=', $filters['available_seats']);
        }
        $booleanFilters = ['pet_allowed', 'luggage_allowed', 'conversation_allowed', 'music_allowed', 'food_allowed'];

        foreach ($booleanFilters as $filter) {
            if (isset($filters[$filter])) {
                $query->where($filter, (bool) $filters[$filter]);
            }
        }

        return $query;
    }

    protected function applySorting($query, array $data)
    {
        $sortOption = $data['sort'] ?? null;

        if ($sortOption) {
            switch ($sortOption) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'start_time':
                    $query->orderBy('start_time', 'asc');
                    break;
                case 'avg_rating':
                    $query->leftJoin('reviews', function ($join) {
                        $join->on('rides.user_id', '=', 'reviews.reviewed_id');
                    })
                        ->select('rides.*')
                        ->selectRaw('COALESCE(AVG(reviews.rating), 0) as avg_rating')
                        ->groupBy('rides.id')
                        ->orderByDesc('avg_rating');
                    break;
            }
        } else {
            $query->latest('created_at');
        }

        return $query;
    }
}
