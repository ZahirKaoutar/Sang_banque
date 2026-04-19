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
        $user = User::with(['centre', 'hopital'])->find($id);

        if (!$user) {
            abort(404, 'Utilisateur non trouvé');
        }

        $donations = $user->donations()->with('centre')->orderBy('donation_date', 'desc')->get();
        $totalDonations = $donations->count();
        $acceptedDonations = $donations->filter(fn($d) => $d->test_result === 'accepted')->count();
        $lifesSaved = $acceptedDonations * 3;

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

    /**
     * Show edit profile form
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'  => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'city'  => 'required|string|max:255',
        ];

        // Password is optional
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city'  => $validated['city'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('profile', $user->id)->with('success', 'Profil mis à jour avec succès');
    }
}
