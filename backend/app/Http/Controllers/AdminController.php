<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
    public function createHopital(Request $request){
        $data=DB::transaction(function () use($request){
             $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'AgentHopital',
                'phone'=>$request->phone,
                'city' => $request->city,
            ]);

           $hopital= Hopital::create([
             'city' => $request->city,
                'user_id' => $user->id,
                'name' => $request->hospital_name,
                'adress' => $request->address,
                'liscence_number' => $request->license_number ,
            ]);
            return ['user' => $user, 'hopital' => $hopital];

        });
        return response()->json([
            'message' => 'Agent hopital créé avec succès!',
            'hopital'=>$data[hopital],
            'user'=>$data[user]
        ]);
    }
}
