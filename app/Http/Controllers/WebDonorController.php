<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Donation;
use Illuminate\Http\Request;

class WebDonorController extends Controller
{
    /**
     * Show donor's donations history page
     */
    public function myDonations()
    {
        $user = auth()->user();
        $donations = $user->donations()->with('centre')->orderBy('donation_date', 'desc')->get();

        // Calculate eligibility (90 days between donations)
        $lastDonation = $user->donations()->latest('donation_date')->first();
        $daysUntilEligible = 0;

        if ($lastDonation) {
            $daysPassed = (int) abs($lastDonation->donation_date->diffInDays(now()));
            $daysUntilEligible = max(0, 90 - $daysPassed);
        }

        $totalDonations = $donations->count();
        $acceptedDonations = $donations->filter(fn($d) => $d->test_result === 'accepted')->count();
        $lifesSaved = $acceptedDonations * 3;

        return view('donor.mes-dons', compact(
            'user',
            'donations',
            'totalDonations',
            'acceptedDonations',
            'lifesSaved',
            'daysUntilEligible'
        ));
    }

    /**
     * Show donor notifications (blood alerts)
     */
    public function notifications()
    {
        $user = auth()->user();

        // Get notifications assigned to this specific donor
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donor.notifications', compact('notifications'));
    }

    /**
     * Handle donor response to notification (accept/refuse)
     */
    public function respondNotification(Notification $notification, Request $request)
    {
        $user = auth()->user();

        // Authorization: Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'response' => 'required|in:accepter,refuser'
        ]);

        // Update notification with donor response
        $notification->update([
            'donor_response' => $validated['response'],
            'responded_at' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Réponse enregistrée',
                'response' => $validated['response']
            ]);
        }

        return back()->with('success', 'Votre réponse a été enregistrée');
    }
}
