<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   public function showProfile($id)
{

    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }
$lastDonation = $user->donations()->latest('donation_date')->first();

   return response()->json([
    'id'              => $user->id,
    'name'            => $user->name,
    'blood_group'     => $user->blood_group,
    'city'            => $user->city,
    'donations_count' => $user->donations->count(),
    'donations_date'  => $lastDonation?->donation_date,
    'donations'       => $user->donations, // ← Ajoutez cette ligne
]);
}
    public function updateProfile(UpdateProfileRequest $request, $id)
{

    $user = User::findOrFail($id);


    $data = $request->validated();


    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    $user->update($data);

    return response()->json([
        "message" => "Profil mis à jour avec succès",
        "user"    => $user
    ]);
}
}
