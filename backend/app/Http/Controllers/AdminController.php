<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function débannir($id){
        $user=User::find($id);
        if(!$user){
            return response()->json([
                'message' => "Utilisateur non trouvé"
            ],404);
        }
        $user->is_banned=false;
        $user->save();
        return response()->json([
            "message"=>"user est debnnis"
        ],200);

    }
}
