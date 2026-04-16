<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Http\Requests\StoreBlood;

class HopitalController extends Controller
{
public function createrequest(StoreBlood $request) {
    $user = auth()->user();


    $validated = $request->validated();

    $bloodRequest = BloodRequest::create([
        'hopital_id'      => $user->hopital->id,
        'center_id'       => $validated['center_id'],
        'blood_group'     => $validated['blood_group'],
        'quantity_needed' => $validated['quantity_needed'],
        'priority'        => $validated['priority'],
        'status'          => 'Pending' 
    ]);

    return response()->json([
        'message' => 'Demande de sang créée avec succès',
        'data'    => $bloodRequest
    ], 201);
}
}
