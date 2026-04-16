<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HemoLife')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Tailwind Colors Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-deep': '#7B0D0D',
                        'red-mid': '#C0392B',
                        'red-light': '#E74C3C',
                        'red-pale': '#FDECEA',
                        'dark': '#1A0505',
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .bg-red-gradient {
            background: linear-gradient(135deg, #FDECEA 0%, white 100%);
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap');
    </style>

    @yield('styles')
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#C0392B" stroke-width="2">
                        <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0 0 16 0c0-3.5-4-7-8-12z" />
                    </svg>
                    <a href="{{ route('home') }}" class="font-display text-2xl font-bold text-dark">HemoLife</a>
                </div>

                <!-- Navigation Links -->
                @auth
                    <div class="hidden md:flex items-center gap-6">
                        @if(auth()->user()->role === 'Admin')
                            <a href="{{ route('admin.centres') }}" class="text-gray-600 hover:text-red-mid transition">Centres</a>
                            <a href="{{ route('admin.hopitaux') }}" class="text-gray-600 hover:text-red-mid transition">Hôpitaux</a>
                            <a href="{{ route('admin.utilisateurs') }}" class="text-gray-600 hover:text-red-mid transition">Utilisateurs</a>

                        @elseif(auth()->user()->role === 'AgentCentre')
                            <a href="{{ route('centre.demandes') }}" class="text-gray-600 hover:text-red-mid transition">Demandes</a>
                            <a href="{{ route('centre.notifications') }}" class="text-gray-600 hover:text-red-mid transition">Réponses</a>

                        @elseif(auth()->user()->role === 'AgentHopital')
                            <a href="{{ route('hopital.demandes') }}" class="text-gray-600 hover:text-red-mid transition">Demandes de sang</a>

                        @elseif(auth()->user()->role === 'Donor')
                            <a href="{{ route('mes-dons') }}" class="text-gray-600 hover:text-red-mid transition">Mes dons</a>
                            <a href="{{ route('donor.notifications') }}" class="text-gray-600 hover:text-red-mid transition">Notifications</a>
                            <a href="{{ route('centres') }}" class="text-gray-600 hover:text-red-mid transition">Centres</a>
                        @endif

                        <a href="{{ route('profile', auth()->id()) }}" class="text-gray-600 hover:text-red-mid transition">Profil</a>
                    </div>

                    <!-- Mobile Menu (Alpine.js) -->
                    <div class="md:hidden flex items-center gap-4">
                        <button x-data="{ open: false }" @click="open = !open" class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Logout Dropdown -->
                    <div class="flex items-center gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-gray-600 hover:text-red-mid">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
                                <p class="px-4 py-3 text-sm text-gray-600 border-b border-gray-100">{{ auth()->user()->name }}</p>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-mid hover:bg-red-pale transition">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-red-mid transition">Connexion</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-red-mid text-white rounded-lg hover:bg-red-deep transition">Inscription</a>
                    </div>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-64px)]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 bg-dark text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">HemoLife</h4>
                    <p class="text-sm text-gray-400">Plateforme de gestion des dons de sang</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">À propos</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Qui sommes-nous</a></li>
                        <li><a href="#" class="hover:text-white transition">Notre mission</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Email: contact@hemolife.ma</li>
                        <li>Téléphone: +212 XXX XXX XXX</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Ressources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li>
                        <li><a href="#" class="hover:text-white transition">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2026 HemoLife. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
