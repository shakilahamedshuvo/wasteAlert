<?php

namespace App\Http\Controllers\team;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complain;

class ComplainController extends Controller
{
    public function showAssignedComplaints()
    {
       $team = auth()->user();
       $complaints = Complain::where('team_id', $team->id)
           ->with('user')
           ->orderBy('created_at', 'desc')
           ->get();
       return view('Team.complain.assigned', compact('complaints'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,in-progress,completed'
        ]);

        $complaint = Complain::findOrFail($id);
        
        // Verify the complaint is assigned to this team member
        if ($complaint->team_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $complaint->status = $request->status;
        $complaint->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $complaint->status
        ]);
    }
}
