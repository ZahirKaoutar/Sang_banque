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
 return response()->json([
        'user'      => $user,
        'donations' => $user->donations,
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
