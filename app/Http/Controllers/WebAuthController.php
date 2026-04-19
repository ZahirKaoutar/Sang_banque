<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class WebAuthController extends Controller
{
    /**
     * Show login form
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login submission (session-based)
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'general' => 'Email ou mot de passe incorrect'
            ]);
        }

        $user = Auth::user();

        if ($user->is_banned === true) {
            Auth::logout();
            return back()->withErrors([
                'general' => 'Votre compte est banni. Contactez l\'administrateur.'
            ]);
        }

        $request->session()->regenerate();

        if ($user->role === 'Donor' && $user->blood_group && $user->blood_group !== 'Unknown') {
            $pendingRequests = \App\Models\BloodRequest::with('centre')
                ->whereIn('status', ['pending', 'partial'])
                ->where('blood_group', $user->blood_group)
                ->whereHas('centre', function($q) use ($user) {
                    $q->where('city', $user->city);
                })
                ->get();

            foreach($pendingRequests as $req) {
                if (!$req->centre) continue;
                $existing = \App\Models\Notification::where('user_id', $user->id)
                    ->where('center_id', $req->center_id)
                    ->where('blood_group_needed', $req->blood_group)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->first();
                
                if (!$existing) {
                    \App\Models\Notification::create([
                        'user_id'            => $user->id,
                        'center_id'          => $req->center_id,
                        'blood_group_needed' => $req->blood_group,
                        'message'            => 'Urgence : ' . $req->quantity_needed . ' unites de sang ' . $req->blood_group . ' necessaires au ' . $req->centre->name . '. Priorite : ' . $req->priority,
                        'sent_at'            => now(),
                    ]);
                }
            }
        }

        // Redirect based on role
        switch ($user->role) {
            case 'Admin':
                return redirect('/admin/centres');
            case 'AgentCentre':
                return redirect('/centre/demandes');
            case 'AgentHopital':
                return redirect('/hopital/demandes');
            case 'Donor':
                return redirect('/profile/' . $user->id);
            default:
                return redirect('/home');
        }
    }

    /**
     * Show register form
     */
    public function registerForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration submission (session-based + auto-login)
     */
    public function register(RegisterRequest $request)
    {
        $role = User::count() === 0 ? 'Admin' : 'Donor';

        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $data['role'] = $role;

        if ($role === 'Donor') {
            $data['blood_group'] = $request->blood_group ?? 'Unknown';
            $data['status_availabality'] = true;
            $data['is_verified'] = false;
        } else {
            $data['is_verified'] = null;
            $data['blood_group'] = null;
            $data['status_availabality'] = true;
        }

        $user = User::create($data);

        // Auto-login after registration
        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'Donor' && $user->blood_group && $user->blood_group !== 'Unknown') {
            $pendingRequests = \App\Models\BloodRequest::with('centre')
                ->whereIn('status', ['pending', 'partial'])
                ->where('blood_group', $user->blood_group)
                ->whereHas('centre', function($q) use ($user) {
                    $q->where('city', $user->city);
                })
                ->get();

            foreach($pendingRequests as $req) {
                if (!$req->centre) continue;
                $existing = \App\Models\Notification::where('user_id', $user->id)
                    ->where('center_id', $req->center_id)
                    ->where('blood_group_needed', $req->blood_group)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->first();
                
                if (!$existing) {
                    \App\Models\Notification::create([
                        'user_id'            => $user->id,
                        'center_id'          => $req->center_id,
                        'blood_group_needed' => $req->blood_group,
                        'message'            => 'Urgence : ' . $req->quantity_needed . ' unites de sang ' . $req->blood_group . ' necessaires au ' . $req->centre->name . '. Priorite : ' . $req->priority,
                        'sent_at'            => now(),
                    ]);
                }
            }
        }

        // Redirect based on role
        switch ($user->role) {
            case 'Admin':
                return redirect('/admin/centres');
            case 'AgentCentre':
                return redirect('/centre/demandes');
            case 'AgentHopital':
                return redirect('/hopital/demandes');
            case 'Donor':
                return redirect('/profile/' . $user->id);
            default:
                return redirect('/home');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/home');
    }
}
