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

        $centres = Centre::all();
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
        $data['hopital_id'] = $hopital->id;
        $data['status'] = 'pending';
        $data['quantity_fulfilled'] = 0;

        $bloodRequest = BloodRequest::create($data);

        return redirect()->route('hopital.demandes')->with('success', 'Demande de sang envoyée');
    }
}
