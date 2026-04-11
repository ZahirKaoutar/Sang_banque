<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    

}
