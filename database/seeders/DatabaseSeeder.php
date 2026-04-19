<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. ADMIN
        User::create([
            'name' => 'Admin HemoLife',
            'email' => 'admin@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000000',
            'role' => 'Admin',
            'city' => 'Casablanca',
        ]);

        // ============================================
        // VILLE 1 : CASABLANCA
        // ============================================

        // Agent Centre Casa
        $agentCentreCasa = User::create([
            'name' => 'Agent Centre Casa',
            'email' => 'centrecasa@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000001',
            'role' => 'AgentCentre',
            'city' => 'Casablanca',
        ]);
        \App\Models\Centre::create([
            'user_id' => $agentCentreCasa->id,
            'name' => 'Centre de Transfusion Casablanca',
            'liscence_number' => 'CTC-1111',
            'adress' => 'Boulevard Zerktouni, Casablanca',
            'city' => 'Casablanca',
        ]);

        // Agent Hôpital Casa
        $agentHopitalCasa = User::create([
            'name' => 'Agent Hôpital Casa',
            'email' => 'hopitalcasa@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000002',
            'role' => 'AgentHopital',
            'city' => 'Casablanca',
        ]);
        \App\Models\Hopital::create([
            'user_id' => $agentHopitalCasa->id,
            'name' => 'CHU Ibn Rochd Casablanca',
            'liscence_number' => 'HOP-1111',
            'adress' => 'Quartier des Hôpitaux, Casablanca',
            'city' => 'Casablanca',
        ]);

        // Donneur Casa
        User::create([
            'name' => 'Amine Casa',
            'email' => 'amine@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000003',
            'role' => 'Donor',
            'city' => 'Casablanca',
            'blood_group' => 'O-',
            'status_availabality' => 1,
            'is_banned' => 0,
        ]);

        // ============================================
        // VILLE 2 : SAFI
        // ============================================

        // Agent Centre Safi
        $agentCentreSafi = User::create([
            'name' => 'Agent Centre Safi',
            'email' => 'centresafi@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000004',
            'role' => 'AgentCentre',
            'city' => 'Safi',
        ]);
        \App\Models\Centre::create([
            'user_id' => $agentCentreSafi->id,
            'name' => 'Centre Régional Safi',
            'liscence_number' => 'CRS-2222',
            'adress' => 'Hôpital Mohamed V, Safi',
            'city' => 'Safi',
        ]);

        // Agent Hôpital Safi
        $agentHopitalSafi = User::create([
            'name' => 'Agent Hôpital Safi',
            'email' => 'hopitalsafi@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000005',
            'role' => 'AgentHopital',
            'city' => 'Safi',
        ]);
        \App\Models\Hopital::create([
            'user_id' => $agentHopitalSafi->id,
            'name' => 'Hôpital Provincial Safi',
            'liscence_number' => 'HOP-2222',
            'adress' => 'Avenue Hassan II, Safi',
            'city' => 'Safi',
        ]);

        // Donneur Safi
        User::create([
            'name' => 'Houssam Safi',
            'email' => 'houssam@hemolife.ma',
            'password' => bcrypt('password'),
            'phone' => '0600000006',
            'role' => 'Donor',
            'city' => 'Safi',
            'blood_group' => 'O-',
            'status_availabality' => 1,
            'is_banned' => 0,
        ]);
    }
}
