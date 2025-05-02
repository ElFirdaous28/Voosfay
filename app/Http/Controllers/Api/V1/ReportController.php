<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Notifications\SendWarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser']);
    
        if ($request->status) {
            $query->where('status', $request->status);
        }
    
        $reports = $query->latest()->get();
    
        return response()->json(['reports' => $reports]);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reported_user_id' => ['required', 'exists:users,id'],
            'reason' => ['required', 'string'],
        ]);

        $report = Report::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $validatedData['reported_user_id'],
            'reason' => $validatedData['reason'],
        ]);

        return response()->json($report, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::with([
            'reporter',
            'reportedUser' => function ($query) {
                $query->withCount('reportsAgainst');
            }
        ])->findOrFail($id);

        return response()->json($report);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'in:pending,resolved'],
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $validatedData['status']]);

        return response()->json($report);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

    public function sendWarning($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new SendWarning());

        return response()->json([
            'message' => 'Warning notification has been sent to the user.'
        ], 200);
    }
}
