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
    public function bannir($id){
        $user=User::find($id);
        if(!$user){
            return response()->json([
                'message' => "Utilisateur non trouvé"
            ],404);
        }
        $user->is_banned=true;
        $user->save();
        return response()->json([
            "message"=>"user est banni"
        ],200);

    }
    public function listHopitals(){
        $hopitaux=Hopital::with('user')->where(role,'AgentHopital')->get();
        return response()->json([
            'message'=>"bien afficher",
            'data'=>$hopitaux
        ]);

    }
    public function listCenters(){
        $centre=Centre::with('user')->where(role,'AgentCentre')->get();
        return response()->json([
            'message'=>"bien afficher",
            'data'=>$centre
        ]);
    }
}
