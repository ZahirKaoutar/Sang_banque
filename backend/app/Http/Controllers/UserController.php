<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
   public function  showProfile($id){
        $user=User::findOrfile($id);
        if(!user){
            return response()->json([
                "message"=>"ce user ne trouve pas"
            ]);
        }
        return response()->json($user);



    }
     public function updateProfile($id){
        $user=User::findOrfile($id);
        if(!user){
            return response()->json([
                "message"=>"ce user ne trouve pas"
            ]);
        }
        $data=request()->validate([
            'name'=>'string',
            'email'=>'email|unique:users,email,'.$user->id,
            'password'=>'string',
            'blood_group'=>'string'
        ]);
        if(isset($data['password'])){
            $data['password']=Hash::make($data['password']);
        }
        $user->update($data);
     }
}
