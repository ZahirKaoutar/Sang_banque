<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class WebCentreController extends Controller
{
    /**
     * Show blood requests for this centre
     */
    public function demandes()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        $allRequests = $centre->bloodRequests()->with('hopital')->orderBy('created_at', 'desc')->get();

        return view('centre.demandes', compact('allRequests'));
    }

    /**
     * Validate a blood request - AJAX
     */
    public function validateRequest(BloodRequest $request)
    {
        $centre = auth()->user()->centre;

        if (!$centre || $request->centre_id !== $centre->id) {
            return response()->json(['error' => 'Accès refusé'], 403);
        }

        // Update request status based on quantity fulfilled
        if ($request->quantity_fulfilled >= $request->quantity_needed) {
            $status = 'Fulfilled';
        } elseif ($request->quantity_fulfilled > 0) {
            $status = 'partial';
        } else {
            $status = 'pending';
        }

        $request->update(['status' => $status]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Demande validée',
                'status' => $status
            ]);
        }

        return back()->with('success', 'Demande validée');
    }

    /**
     * Show donor responses to notifications
     */
    public function notifications()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        // Get notifications sent by this centre with donor responses
        $responses = Notification::where('centre_id', $centre->id)
            ->whereNotNull('donor_response')
            ->with('donor:id,name,blood_group')
            ->orderBy('responded_at', 'desc')
            ->get();

        // Calculate stats
        $totalResponses = $responses->count();
        $accepted = $responses->where('donor_response', 'accepter')->count();
        $refused = $responses->where('donor_response', 'refuser')->count();
        $acceptanceRate = $totalResponses > 0 ? round(($accepted / $totalResponses) * 100) : 0;

        return view('centre.notifications', compact('responses', 'totalResponses', 'accepted', 'refused', 'acceptanceRate'));
    }
}
