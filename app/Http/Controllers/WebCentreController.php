<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebCentreController extends Controller
{
    public function stock()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        $stocks = BloodStock::where('center_id', $centre->id)
            ->orderBy('blood_group')
            ->get();

        $totalUnits = $stocks->sum('quantity_units');

        return view('centre.stock', compact('stocks', 'totalUnits'));
    }

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
    public function validateRequest(BloodRequest $bloodRequest)
    {
        $centre = auth()->user()->centre;

        if (!$centre || $bloodRequest->center_id != $centre->id) {
            return response()->json(['error' => 'Accès refusé'], 403);
        }

        try {
            $result = DB::transaction(function () use ($bloodRequest, $centre) {
                $stock = BloodStock::where('center_id', $centre->id)
                    ->where('blood_group', $bloodRequest->blood_group)
                    ->first();

                $quantiteStock    = $stock ? (int) $stock->quantity_units : 0;
                $quantiteDemandee = (int) $bloodRequest->quantity_needed;

                if ($quantiteStock >= $quantiteDemandee) {
                    $stock->decrement('quantity_units', $quantiteDemandee);
                    $bloodRequest->update(['status' => 'Fulfilled', 'quantity_fulfilled' => $quantiteDemandee]);
                    return ['status' => 'Fulfilled', 'message' => 'Demande satisfaite. Stock mis à jour.'];
                }

                if ($quantiteStock > 0) {
                    $stock->update(['quantity_units' => 0]);
                    $bloodRequest->update(['status' => 'partial', 'quantity_fulfilled' => $quantiteStock]);
                    $this->alerterDonneurs($bloodRequest, $centre, $quantiteDemandee - $quantiteStock);
                    return ['status' => 'partial', 'message' => "{$quantiteStock} unités envoyées. Alertes lancées pour le reste."];
                }

                $this->alerterDonneurs($bloodRequest, $centre, $quantiteDemandee);
                $bloodRequest->update(['status' => 'pending']);
                return ['status' => 'pending', 'message' => 'Aucun stock. Alertes envoyées aux donneurs compatibles.'];
            });

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeStock(Request $request)
    {
        $centre = auth()->user()->centre;

        if (!$centre) abort(403);

        $request->validate([
            'blood_group'    => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_units' => 'required|integer|min:1',
            'expiry_date'    => 'required|date|after:today',
        ]);

        BloodStock::create([
            'center_id'      => $centre->id,
            'blood_group'    => $request->blood_group,
            'quantity_units' => $request->quantity_units,
            'expiry_date'    => $request->expiry_date,
        ]);

        return back()->with('success', 'Stock ajouté avec succès');
    }

    public function deleteStock(BloodStock $stock)
    {
        $centre = auth()->user()->centre;

        if (!$centre || $stock->center_id !== $centre->id) abort(403);

        $stock->delete();

        return back()->with('success', 'Stock supprimé');
    }

    private function alerterDonneurs(BloodRequest $bloodRequest, $centre, $quantite)
    {
        $donors = User::where('role', 'Donor')
            ->where('blood_group', $bloodRequest->blood_group)
            ->where('city', $centre->city)
            ->where('status_availabality', 1)
            ->where('is_banned', 0)
            ->get();

        foreach ($donors as $donor) {
            Notification::create([
                'user_id'            => $donor->id,
                'center_id'          => $centre->id,
                'blood_group_needed' => $bloodRequest->blood_group,
                'message'            => 'Urgence : ' . $bloodRequest->quantity_needed . ' unites de sang ' . $bloodRequest->blood_group . ' necessaires au ' . $centre->name . '. Priorite : ' . $bloodRequest->priority,
                'sent_at'            => now(),
            ]);
        }
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
        $responses = Notification::where('center_id', $centre->id)
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
