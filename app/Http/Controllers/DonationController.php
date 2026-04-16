<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonationController extends Controller
{


public function index()
{

    $donations = Donation::with(['user', 'center'])
        ->orderBy('donation_date', 'desc')
        ->paginate(10);

    return response()->json($donations);
}
    public function store(StoreDonationRequest $request)
{

    $threeMonthsAgo = now()->subMonths(3);

    $alreadyDonated = Donation::where('user_id', $request->user_id)
        ->where('test_result', 'accepted')
        ->where('donation_date', '>=', $threeMonthsAgo)
        ->exists();

    if ($alreadyDonated) {

        $donation = Donation::create(array_merge($request->validated(), [
            'test_result' => 'rejected',
            'medical_notes' => 'Refus automatique : Délai de 3 mois non respecté.'
        ]));

        return response()->json([
            'status' => 'error',
            'message' => 'Don refusé. Ce donneur a déjà donné son sang il y a moins de 3 mois.'
        ], 403);
    }


    $donation = Donation::create($request->validated());


    if ($donation->test_result === 'accepted') {


        $user = User::find($donation->user_id);
        if ($user->blood_group !== $donation->observed_blood_group) {
            $user->blood_group = $donation->observed_blood_group;
        }
        $user->is_verified = true;
        $user->save();


        $expiryDate = now()->addDays(42);
        $stock = BloodStock::firstOrCreate(
            [
                'center_id'   => $donation->center_id,
                'blood_group' => $donation->observed_blood_group,
                'expiry_date' => $expiryDate->format('Y-m-d')
            ],
            ['quantity_units' => 0]
        );

        $stock->increment('quantity_units');
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Le don a été enregistré avec succès.'
    ]);
}



public function show($id)
{
    $donation = Donation::with(['user', 'center'])->find($id);

    if (!$donation) {
        return response()->json(['message' => 'Don non trouvé'], 404);
    }

    return response()->json($donation);
}



public function update(Request $request, Donation $donation)
{

    $validated = $request->validate([
        'test_result' => 'sometimes|string|in:accepted,rejected',
        'medical_notes' => 'sometimes|string',
        'donation_date' => 'sometimes|date|before_or_equal:today',
        'observed_blood_group' => 'sometimes|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
    ]);



    $donation->update($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Don mis à jour',
        'data' => $donation
    ]);
}


public function destroy($id)
{
    $donation = Donation::find($id);

    if (!$donation) {
        return response()->json(['message' => 'Don non trouvé'], 404);
    }

    

    $donation->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Le don a été supprimé de l\'historique.'
    ]);
}
}
