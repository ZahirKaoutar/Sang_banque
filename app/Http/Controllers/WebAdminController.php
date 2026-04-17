<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Centre;
use App\Models\Hopital;
use App\Http\Requests\StoreCentreRequest;
use App\Http\Requests\StoreHopitalRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebAdminController extends Controller
{
    public function editCentre(Centre $centre)
    {
        $centre->load('user');
        return view('admin.edit-centre', compact('centre'));
    }

    public function updateCentre(Request $request, Centre $centre)
    {
        $request->validate([
            'name'           => 'required|string|min:3',
            'email'          => 'required|email|unique:users,email,' . $centre->user_id,
            'phone'          => 'required|unique:users,phone,' . $centre->user_id,
            'center_name'    => 'required|string|min:3',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'license_number' => 'required|string|unique:centers,liscence_number,' . $centre->id,
        ]);

        DB::transaction(function () use ($request, $centre) {
            $centre->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city'  => $request->city,
            ]);
            $centre->update([
                'name'            => $request->center_name,
                'adress'          => $request->address,
                'city'            => $request->city,
                'liscence_number' => $request->license_number,
            ]);
        });

        return redirect()->route('admin.show-centre', $centre->id)->with('success', 'Centre modifié avec succès');
    }

    public function editHopital(Hopital $hopital)
    {
        $hopital->load('user');
        return view('admin.edit-hopital', compact('hopital'));
    }

    public function updateHopital(Request $request, Hopital $hopital)
    {
        $request->validate([
            'name'           => 'required|string|min:3',
            'email'          => 'required|email|unique:users,email,' . $hopital->user_id,
            'phone'          => 'required|unique:users,phone,' . $hopital->user_id,
            'hospital_name'  => 'required|string|min:3',
            'adress'         => 'required|string',
            'city'           => 'required|string',
            'license_number' => 'required|string|unique:hopitals,liscence_number,' . $hopital->id,
        ]);

        DB::transaction(function () use ($request, $hopital) {
            $hopital->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city'  => $request->city,
            ]);
            $hopital->update([
                'name'            => $request->hospital_name,
                'adress'          => $request->adress,
                'city'            => $request->city,
                'liscence_number' => $request->license_number,
            ]);
        });

        return redirect()->route('admin.show-hopital', $hopital->id)->with('success', 'Hôpital modifié avec succès');
    }

    public function showCentre(Centre $centre)
    {
        $centre->load('user');
        return view('admin.show-centre', compact('centre'));
    }

    public function showHopital(Hopital $hopital)
    {
        $hopital->load('user');
        return view('admin.show-hopital', compact('hopital'));
    }

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

    try {
        DB::transaction(function () use ($data) {
            // 1. Création de l'utilisateur Agent
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'AgentCentre',
                'city' => $data['city'], // Correction : ajout de la ville
                'status_availabality' => true,
                'is_verified' => null,
            ]);

            // 2. Création du centre lié à cet utilisateur
            Centre::create([
                'name' => $data['center_name'],
                'adress' => $data['address'],
                'city' => $data['city'],
                'liscence_number' => $data['license_number'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('admin.centres')->with('success', 'Centre et agent créés avec succès');

    } catch (\Exception $e) {
        // En cas d'erreur, on revient en arrière avec un message
        return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
    }
}

public function storeHopital(StoreHopitalRequest $request)
{
    $data = $request->validated();

    try {
        DB::transaction(function () use ($data) {
            // 1. Création de l'utilisateur Agent
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'AgentHopital',
                'city' => $data['city'],
                'status_availabality' => true,
                'is_verified' => null,
            ]);

            // 2. Création de l'hôpital lié
            Hopital::create([
                'name' => $data['hospital_name'],
                'adress' => $data['adress'],
                'city' => $data['city'],
                'liscence_number' => $data['license_number'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('admin.hopitaux')->with('success', 'Hôpital et agent créés avec succès');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
    }
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
   
    /**
     * Delete centre - AJAX
     */
    public function deleteCentre(Centre $centre)
    {
        $centre->user()->delete();
        $centre->delete();
        return back()->with('success', 'Centre supprimé');
    }

    public function deleteHopital(Hopital $hopital)
    {
        $hopital->user()->delete();
        $hopital->delete();
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
