<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Centre;
use App\Models\Donation;
use Illuminate\Http\Request;

class WebPageController extends Controller
{
    /**
     * Show home page
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Show user profile page
     */
    public function profile($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404, 'Utilisateur non trouvé');
        }

        $donations = $user->donations()->with('centre')->orderBy('donation_date', 'desc')->get();
        $totalDonations = $donations->count();

        // Calculate stats
        $acceptedDonations = $donations->filter(fn($d) => $d->test_result === 'accepted')->count();
        $lifesSaved = $acceptedDonations * 3; // Estimate 3 lives per donation

        return view('donor.profile', compact('user', 'donations', 'totalDonations', 'lifesSaved'));
    }

    /**
     * Show all centres (for donors)
     */
    public function donorCentres()
    {
        $centres = Centre::with('user')->get();

        return view('donor.centres', compact('centres'));
    }
}
