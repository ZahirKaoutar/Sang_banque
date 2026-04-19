<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\BloodRequest;
use App\Http\Requests\StoreBloodRequest;
use Illuminate\Http\Request;

class WebHopitalController extends Controller
{
    /**
     * Show blood requests form and past requests
     */
    public function demandes()
    {
        $hopital = auth()->user()->hopital;

        if (!$hopital) {
            abort(403, 'Vous n\'êtes pas associé à un hôpital');
        }

        // Filter centers to only show those in the same city as the hospital
        $centres = Centre::where('city', $hopital->city)->get();
        
        $pastRequests = $hopital->bloodRequests()->with('centre')->orderBy('created_at', 'desc')->get();

        return view('hopital.demandes', compact('centres', 'pastRequests'));
    }

    /**
     * Store new blood request (form submission)
     */
    public function store(StoreBloodRequest $request)
    {
        $hopital = auth()->user()->hopital;

        if (!$hopital) {
            abort(403, 'Vous n\'êtes pas associé à un hôpital');
        }

        $data = $request->validated();
        
        // Security check: ensure the requested center is in the same city
        $center = \App\Models\Centre::findOrFail($data['center_id']);
        if ($center->city !== $hopital->city) {
            return back()->with('error', 'Vous ne pouvez faire une demande qu\'aux centres de votre ville.');
        }

        $data['hopital_id'] = $hopital->id;
        $data['status'] = 'pending';
        $data['quantity_fulfilled'] = 0;

        $bloodRequest = BloodRequest::create($data);

        return redirect()->route('hopital.demandes')->with('success', 'Demande de sang envoyée');
    }

    /**
     * Show notifications for the hospital
     */
    public function notifications()
    {
        $hopital = auth()->user()->hopital;

        if (!$hopital) {
            abort(403, 'Vous n\'êtes pas associé à un hôpital');
        }

        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hopital.notifications', compact('notifications'));
    }
}
