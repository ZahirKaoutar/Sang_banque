<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmergencyBloodAlert;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
{
    public function  voirRequest(){
        $user = auth()->user();


    if (!$user || !$user->centre) {
        return response()->json([
            'message' => 'Erreur : Aucun centre associé à cet utilisateur.'
        ], 404);
    }

    $center = $user->centre;


    $requests = BloodRequest::with('hopital')
        ->where('center_id', $center->id)
        ->orderBy('priority', 'desc')
        ->latest()
        ->get();

    return response()->json($requests);

    }



    

    public function validerRequest($requestId)
{
    $user = auth()->user();
    $center = $user->centre;
    $bloodRequest = BloodRequest::findOrFail($requestId);


    $stock = BloodStock::where('center_id', $center->id)
        ->where('blood_group', $bloodRequest->blood_group)
        ->first();

    $quantiteStock = $stock ? $stock->quantity : 0;
    $quantiteDemandee = $bloodRequest->quantity_needed;


    if ($quantiteStock >= $quantiteDemandee) {
        DB::transaction(function () use ($stock, $bloodRequest, $quantiteDemandee) {
            $stock->decrement('quantity', $quantiteDemandee);
            $bloodRequest->update([
                'status' => 'Fulfilled',
                'quantity_fulfilled' => $quantiteDemandee
            ]);
        });

        return response()->json(['message' => 'Demande totalement satisfaite.']);
    }


    if ($quantiteStock <= 0) {
        $this->alerterDonneurs($bloodRequest, $center, $quantiteDemandee);

        $bloodRequest->update(['status' => 'pending']);

        return response()->json(['message' => 'Aucun stock disponible. Alertes envoyées.']);
    }


    if ($quantiteStock > 0 && $quantiteStock < $quantiteDemandee) {
        $manquant = $quantiteDemandee - $quantiteStock;

        DB::transaction(function () use ($stock, $bloodRequest, $quantiteStock, $center, $manquant) {

            $stock->update(['quantity' => 0]);

            $bloodRequest->update([
                'status' => 'partial',
                'quantity_fulfilled' => $quantiteStock
            ]);


            $this->alerterDonneurs($bloodRequest, $center, $manquant);
        });

        return response()->json(['message' => "Stock insuffisant. $quantiteStock poches envoyées, alertes lancées pour le reste."]);
    }
}


private function alerterDonneurs($bloodRequest, $center, $quantite) {
    $donors = User::where('role', 'Donor')
        ->where('blood_group', $bloodRequest->blood_group)
        ->where('city', $center->city)
        ->get();

    foreach ($donors as $donor) {
        $donor->notify(new EmergencyBloodAlert($bloodRequest, $center, $quantite));
    }
}


}
