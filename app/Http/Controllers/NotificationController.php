<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

   public function DonorNotifications()
{
    $user = auth()->user();


    $notifications = Notification::where('user_id', $user->id)
        ->with('centre') // Charge les détails du centre (nom, ville, etc.)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($notifications);
}

public function getCenterNotifications()
{
    $user = auth()->user();

    if (!$user->centre) {
        return response()->json(['message' => 'Accès non autorisé'], 403);
    }

    $notifications = Notification::where('center_id', $user->centre->id)
        
        ->with([
            'donor:id,name,blood_group'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($notifications);
}
public function respondDonor(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepter,refuser',
        ]);

        $notification = Notification::where('user_id', auth()->id())
                                    ->findOrFail($id);

        $notification->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Réponse '" . $request->status . "' enregistrée.",
            'data'    => $notification
        ]);
    }
}

