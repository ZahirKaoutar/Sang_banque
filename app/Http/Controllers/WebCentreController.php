<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebCentreController extends Controller
{
    public function stock()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        $stocks = BloodStock::where('center_id', $centre->id)
            ->orderBy('blood_group')
            ->get();

        $totalUnits = $stocks->sum('quantity_units');

        return view('centre.stock', compact('stocks', 'totalUnits'));
    }

    /**
     * Show blood requests for this centre
     */
    public function demandes()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        $allRequests = $centre->bloodRequests()->with('hopital')->orderBy('created_at', 'desc')->get();

        return view('centre.demandes', compact('allRequests'));
    }

    /**
     * Validate a blood request - AJAX
     */
    public function validateRequest($id)
    {
        try {
            $centre = auth()->user()->centre;

            if (!$centre) {
                return response()->json(['error' => 'Aucun centre associé'], 403);
            }

            $bloodRequest = BloodRequest::with('hopital')->find($id);

            if (!$bloodRequest) {
                return response()->json(['error' => 'Demande non trouvée'], 404);
            }

            if ($bloodRequest->center_id != $centre->id) {
                return response()->json(['error' => 'Accès refusé'], 403);
            }

            $result = DB::transaction(function () use ($bloodRequest, $centre) {
                $stock = BloodStock::where('center_id', $centre->id)
                    ->where('blood_group', $bloodRequest->blood_group)
                    ->first();

                $quantiteStock    = $stock ? (int) $stock->quantity_units : 0;
                $quantiteDemandee = (int) $bloodRequest->quantity_needed;
                $hoursPassed = now()->diffInHours($bloodRequest->created_at);

                $canValidate = false;
                if ($quantiteStock >= $quantiteDemandee) {
                    $canValidate = true;
                } else if ($bloodRequest->priority === 'Urgent' && $hoursPassed >= 10) {
                    $canValidate = true;
                }

                if (!$canValidate) {
                    return ['status' => 'error', 'error' => 'La demande ne peut pas être validée. Soit le stock est insuffisant, soit le délai de 10h pour une demande urgente n\'est pas atteint.'];
                }

                $sentUnits = min($quantiteDemandee, $quantiteStock);
                
                if ($stock && $sentUnits > 0) {
                    $stock->decrement('quantity_units', $sentUnits);
                }

                $status = ($sentUnits >= $quantiteDemandee) ? 'Fulfilled' : 'partial';
                $bloodRequest->update(['status' => $status, 'quantity_fulfilled' => $sentUnits]);

                // Notify hospital
                Notification::create([
                    'user_id'            => $bloodRequest->hopital->user_id,
                    'center_id'          => $centre->id,
                    'blood_group_needed' => $bloodRequest->blood_group,
                    'message'            => "Votre demande de {$quantiteDemandee} unités de {$bloodRequest->blood_group} a été validée. {$sentUnits} unités vous ont été envoyées.",
                    'sent_at'            => now(),
                ]);

                return ['status' => $status, 'message' => "Demande validée. {$sentUnits} unités envoyées."];
            });

            return response()->json($result, 200);

        } catch (\Exception $e) {
            Log::error('validateRequest error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notifyDonors($id)
    {
        try {
            $centre = auth()->user()->centre;
            $bloodRequest = BloodRequest::with('hopital')->findOrFail($id);

            if ($bloodRequest->center_id != $centre->id) {
                return response()->json(['error' => 'Accès refusé'], 403);
            }

            $stock = BloodStock::where('center_id', $centre->id)
                ->where('blood_group', $bloodRequest->blood_group)
                ->sum('quantity_units');

            $quantiteDemandee = (int) $bloodRequest->quantity_needed;
            $missing = $quantiteDemandee - $stock;
            if ($missing <= 0) $missing = $quantiteDemandee;

            $this->alerterDonneurs($bloodRequest, $centre, $missing);

            return response()->json(['message' => 'Notifications envoyées aux donneurs et/ou hôpital avec succès.']);
        } catch (\Exception $e) {
            Log::error('notifyDonors error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeStock(Request $request)
    {
        $centre = auth()->user()->centre;

        if (!$centre) abort(403);

        $request->validate([
            'blood_group'    => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_units' => 'required|integer|min:1',
            'expiry_date'    => 'required|date|after:today',
        ]);

        BloodStock::create([
            'center_id'      => $centre->id,
            'blood_group'    => $request->blood_group,
            'quantity_units' => $request->quantity_units,
            'expiry_date'    => $request->expiry_date,
        ]);

        return back()->with('success', 'Stock ajouté avec succès');
    }

    public function deleteStock(BloodStock $stock)
    {
        $centre = auth()->user()->centre;

        if (!$centre || $stock->center_id !== $centre->id) abort(403);

        $stock->delete();

        return back()->with('success', 'Stock supprimé');
    }

    private function alerterDonneurs(BloodRequest $bloodRequest, $centre, $quantite)
    {
        $donors = User::where('role', 'Donor')
            ->where('blood_group', $bloodRequest->blood_group)
            ->where('city', $centre->city)
            ->where('status_availabality', 1)
            ->where('is_banned', 0)
            ->whereDoesntHave('donations', function ($query) {
                $query->where('donation_date', '>=', now()->subDays(90));
            })
            ->get();

        if ($donors->isEmpty()) {
            // Check if we already alerted the hospital today to avoid spam
            $recentHospitalAlert = Notification::where('user_id', $bloodRequest->hopital->user_id)
                ->where('center_id', $centre->id)
                ->where('blood_group_needed', $bloodRequest->blood_group)
                ->where('created_at', '>=', now()->subHours(24))
                ->first();

            if (!$recentHospitalAlert) {
                Notification::create([
                    'user_id'            => $bloodRequest->hopital->user_id,
                    'center_id'          => $centre->id,
                    'blood_group_needed' => $bloodRequest->blood_group,
                    'message'            => "Alerte : Aucun donneur disponible dans la ville ({$centre->city}) pour le groupe sanguin {$bloodRequest->blood_group} pour satisfaire votre demande de {$quantite} unités.",
                    'sent_at'            => now(),
                ]);
            }
            return;
        }

        foreach ($donors as $donor) {
            // Check if this donor already received a notification for this blood group from this center today
            $recentDonorAlert = Notification::where('user_id', $donor->id)
                ->where('center_id', $centre->id)
                ->where('blood_group_needed', $bloodRequest->blood_group)
                ->where('created_at', '>=', now()->subHours(24))
                ->first();

            if (!$recentDonorAlert) {
                Notification::create([
                    'user_id'            => $donor->id,
                    'center_id'          => $centre->id,
                    'blood_group_needed' => $bloodRequest->blood_group,
                    'message'            => 'Urgence : ' . $bloodRequest->quantity_needed . ' unites de sang ' . $bloodRequest->blood_group . ' necessaires au ' . $centre->name . '. Priorite : ' . $bloodRequest->priority,
                    'sent_at'            => now(),
                ]);
            }
        }
    }

    /**
     * Return stock details for the validation modal
     */
    public function getRequestDetails($id)
    {
        try {
            $centre = auth()->user()->centre;

            if (!$centre) {
                return response()->json(['error' => 'Aucun centre associé à votre compte'], 403);
            }

            $bloodRequest = BloodRequest::find($id);

            if (!$bloodRequest) {
                return response()->json(['error' => 'Demande non trouvée'], 404);
            }

            if ($bloodRequest->center_id != $centre->id) {
                return response()->json(['error' => 'Cette demande n\'appartient pas à votre centre'], 403);
            }

            $stock = BloodStock::where('center_id', $centre->id)
                ->where('blood_group', $bloodRequest->blood_group)
                ->sum('quantity_units');

            return response()->json([
                'stock' => (int) $stock,
                'blood_group' => $bloodRequest->blood_group,
                'quantity_needed' => $bloodRequest->quantity_needed,
                'priority' => $bloodRequest->priority,
                'hours_passed' => now()->diffInHours($bloodRequest->created_at),
                'status' => $bloodRequest->status
            ], 200);
        } catch (\Exception $e) {
            Log::error('getRequestDetails error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Record donation after medical test when donor shows up at the centre
     */
    public function storeDonation(Request $request)
    {
        $centre = auth()->user()->centre;
        if (!$centre) abort(403);

        $validated = $request->validate([
            'notification_id'      => 'required|exists:notifications,id',
            'donation_date'        => 'required|date|before_or_equal:today',
            'test_result'          => 'required|in:accepted,rejected',
            'observed_blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'medical_notes'        => 'nullable|string|max:1000',
        ]);

        $notification = Notification::where('id', $validated['notification_id'])
            ->where('center_id', $centre->id)
            ->where('donor_response', 'accepter')
            ->firstOrFail();

        DB::transaction(function () use ($notification, $centre, $validated) {
            // 1. Toujours créer l'enregistrement du don (accepté ou refusé)
            Donation::create([
                'user_id'              => $notification->user_id,
                'center_id'            => $centre->id,
                'donation_date'        => $validated['donation_date'],
                'test_result'          => $validated['test_result'],
                'observed_blood_group' => $validated['observed_blood_group'],
                'medical_notes'        => $validated['medical_notes'] ?? null,
            ]);

            // 2. Incrémenter le stock SEULEMENT si le test est accepté
            if ($validated['test_result'] === 'accepted') {
                $stock = BloodStock::firstOrCreate(
                    [
                        'center_id'  => $centre->id,
                        'blood_group' => $validated['observed_blood_group'],
                    ],
                    ['quantity_units' => 0, 'expiry_date' => now()->addDays(42)->format('Y-m-d')]
                );
                $stock->increment('quantity_units');

                // Marquer le donneur comme vérifié et mettre à jour son groupe sanguin
                $donor = User::find($notification->user_id);
                $donor->update([
                    'is_verified' => true,
                    'blood_group' => $validated['observed_blood_group']
                ]);
            }

            // 3. Marquer la notification comme traitée
            $notification->update(['donation_recorded' => true]);
        });

        $msg = $validated['test_result'] === 'accepted'
            ? 'Don accepté et stock mis à jour avec succès.'
            : 'Don refusé. Les notes médicales ont été enregistrées.';

        return back()->with(
            $validated['test_result'] === 'accepted' ? 'success' : 'warning',
            $msg
        );
    }

    /**
     * Show donor responses to notifications
     */

    public function notifications()
    {
        $centre = auth()->user()->centre;

        if (!$centre) {
            abort(403, 'Vous n\'êtes pas associé à un centre');
        }

        // Get notifications sent by this centre with donor responses
        $responses = Notification::where('center_id', $centre->id)
            ->whereNotNull('donor_response')
            ->with('donor:id,name,blood_group')
            ->orderBy('responded_at', 'desc')
            ->get();

        // Calculate stats
        $totalResponses = $responses->count();
        $accepted = $responses->where('donor_response', 'accepter')->count();
        $refused = $responses->where('donor_response', 'refuser')->count();
        $acceptanceRate = $totalResponses > 0 ? round(($accepted / $totalResponses) * 100) : 0;

        return view('centre.notifications', compact('responses', 'totalResponses', 'accepted', 'refused', 'acceptanceRate'));
    }
}
