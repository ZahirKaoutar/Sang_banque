<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Centre;
use App\Models\Hopital;
use App\Http\Requests\StoreCentreRequest;
use App\Http\Requests\StoreHopitalRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class WebAdminController extends Controller
{
    /**
     * Show all centres
     */
    public function centres()
    {
        $centres = Centre::with('user')->get();
        $totalCentres = $centres->count();
        $stats = [
            'active' => $centres->count(), // You can add more logic here based on your data
        ];

        return view('admin.centres', compact('centres', 'totalCentres', 'stats'));
    }

    /**
     * Show all hospitals
     */
    public function hopitaux()
    {
        $hopitaux = Hopital::with('user')->get();
        $totalHopitaux = $hopitaux->count();
        $stats = [
            'active' => $hopitaux->count(),
        ];

        return view('admin.hopitaux', compact('hopitaux', 'totalHopitaux', 'stats'));
    }

    /**
     * Show all users
     */
    public function utilisateurs()
    {
        $users = User::all();

        return view('admin.utilisateurs', compact('users'));
    }

    /**
     * Show create centre form
     */
    public function createCentreForm()
    {
        return view('admin.create-centre');
    }

    /**
     * Store new centre and agent user
     */
    public function storeCentre(StoreCentreRequest $request)
    {
        $data = $request->validated();

        // Create user (agent)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'AgentCentre',
            'status_availabality' => true,
            'is_verified' => null,
        ]);

        // Create centre
        Centre::create([
            'name' => $data['center_name'],
            'adress' => $data['address'],
            'city' => $data['city'],
            'liscence_number' => $data['license_number'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.centres')->with('success', 'Centre créé avec succès');
    }

    /**
     * Show create hospital form
     */
    public function createHopitalForm()
    {
        return view('admin.create-hopital');
    }

    /**
     * Store new hospital and agent user
     */
    public function storeHopital(StoreHopitalRequest $request)
    {
        $data = $request->validated();

        // Create user (agent)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'AgentHopital',
            'status_availabality' => true,
            'is_verified' => null,
        ]);

        // Create hospital
        Hopital::create([
            'name' => $data['hospital_name'],
            'adress' => $data['adress'],
            'city' => $data['city'],
            'liscence_number' => $data['license_number'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.hopitaux')->with('success', 'Hôpital créé avec succès');
    }

    /**
     * Delete centre - AJAX
     */
    public function deleteCentre(Centre $centre)
    {
        // Delete associated user (cascade)
        $centre->user()->delete();
        $centre->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Centre supprimé']);
        }

        return back()->with('success', 'Centre supprimé');
    }

    /**
     * Delete hospital - AJAX
     */
    public function deleteHopital(Hopital $hopital)
    {
        // Delete associated user (cascade)
        $hopital->user()->delete();
        $hopital->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Hôpital supprimé']);
        }

        return back()->with('success', 'Hôpital supprimé');
    }

    /**
     * Ban user - AJAX
     */
    public function banUser(User $user)
    {
        $user->update(['is_banned' => true]);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Utilisateur banni']);
        }

        return back()->with('success', 'Utilisateur banni');
    }

    /**
     * Unban user - AJAX
     */
    public function unbanUser(User $user)
    {
        $user->update(['is_banned' => false]);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Utilisateur débanni']);
        }

        return back()->with('success', 'Utilisateur débanni');
    }
}
