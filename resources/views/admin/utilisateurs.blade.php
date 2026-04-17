@extends('layouts.app')

@section('title', 'Admin Utilisateurs - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{
    search: '',
    matchesSearch(name, email, phone) {
        const q = this.search.toLowerCase();
        return name.toLowerCase().includes(q) ||
               email.toLowerCase().includes(q) ||
               phone.includes(q);
    }
}">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-dark">Utilisateurs</h1>
                <p class="text-gray-600 mt-2">Gestion de tous les utilisateurs ({{ $users->count() }})</p>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input x-model="search" type="text" placeholder="Rechercher par nom, email ou téléphone..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid" />
        </div>

        @if($users->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center text-gray-400">
                <p class="text-5xl mb-4">👥</p>
                <p class="font-bold text-lg">Aucun utilisateur</p>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Nom</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Email</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Rôle</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Téléphone</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Ville</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">État</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr x-show="matchesSearch('{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone }}')"
                                class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-dark">{{ $user->name }}</td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="py-4 px-4">
                                    @php
                                        $roleColors = [
                                            'Admin'       => 'bg-purple-100 text-purple-800',
                                            'AgentCentre' => 'bg-blue-100 text-blue-800',
                                            'AgentHopital'=> 'bg-indigo-100 text-indigo-800',
                                            'Donor'       => 'bg-green-100 text-green-800',
                                        ];
                                        $color = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $user->phone ?? '-' }}</td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $user->city ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if($user->is_banned)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-deep">Banni</span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Actif</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($user->is_banned)
                                        <form method="POST" action="{{ route('admin.unban-user', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs font-bold">
                                                Débannir
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.ban-user', $user->id) }}" class="inline"
                                            onsubmit="return confirm('Bannir {{ addslashes($user->name) }} ?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-deep text-white rounded-lg hover:bg-red-900 transition text-xs font-bold">
                                                Bannir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
