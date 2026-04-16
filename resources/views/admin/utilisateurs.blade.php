@extends('layouts.app')

@section('title', 'Admin Utilisateurs - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ search: '' }">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Utilisateurs</h1>
            <p class="text-gray-600">Gestion de tous les utilisateurs</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input x-model="search" type="text" placeholder="Rechercher par nom, email ou téléphone..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid focus:border-transparent" />
        </div>

        <!-- Users Table -->
        @if($users->isEmpty())
            @include('components.empty-state', ['icon' => '👥', 'title' => 'Aucun utilisateur', 'subtitle' => 'Aucun utilisateur n\'a encore été créé'])
        @else
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
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
                            <tr x-show="matchesSearch('{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}')" class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-dark">{{ $user->name }}</td>
                                <td class="py-4 px-4 text-sm">{{ $user->email }}</td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm">{{ $user->phone }}</td>
                                <td class="py-4 px-4 text-sm">{{ $user->city ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if($user->is_banned)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-deep">
                                            Banni
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-900">
                                            Actif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 space-x-2">
                                    @if($user->is_banned)
                                        <form method="POST" action="{{ route('admin.unban-user', $user->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-bold">
                                                Débannir
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.ban-user', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr?');" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-deep text-white rounded-lg hover:bg-red-900 transition text-sm font-bold">
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminUsers', () => ({
                search: '',
                matchesSearch(name, email, phone) {
                    const query = this.search.toLowerCase();
                    return name.toLowerCase().includes(query) ||
                        email.toLowerCase().includes(query) ||
                        phone.includes(query);
                }
            }));
        });
    </script>
</div>
@endsection
