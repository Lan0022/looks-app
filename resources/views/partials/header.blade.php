<header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-800 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{ 'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                        <path :class="{ 'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="text-2xl font-bold font-heading text-gray-800">
                <a href="{{ route('home') }}">LOOKS</a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8 font-semibold uppercase text-sm">
                <a href="#" class="text-gray-700 hover:text-accent">Men</a>
                <a href="#" class="text-gray-700 hover:text-accent">Women</a>
                <a href="#" class="text-gray-700 hover:text-accent">New Arrivals</a>
                <a href="#" class="text-red-500 hover:text-red-700">Sale</a>
            </div>

            <!-- Header Icons -->
            <div class="flex items-center space-x-5">
                <a href="{{ route('products') }}" class="text-gray-700 hover:text-accent">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </a>
                @auth
                    <!-- Jika sudah login, link ke profil -->
                    <a href="{{ route('profile') }}" class="text-gray-700 hover:text-accent">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                    @elseguest
                    <!-- Jika belum, link ke register -->
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-accent">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                @endauth
                <a href="#" class="text-gray-700 hover:text-accent relative">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span
                        class="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
                @auth
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-accent flex items-center focus:outline-none"
                            title="Logout">
                            <svg class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-8V7a2 2 0 114 0v1" />
                            </svg>
                            {{-- <span class="hidden sm:inline-block text-sm">Logout</span> --}}
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="lg:hidden mt-4 space-y-4 uppercase text-sm">
            <a href="#" class="block text-gray-700 hover:text-accent">Men</a>
            <a href="#" class="block text-gray-700 hover:text-accent">Women</a>
            <a href="#" class="block text-gray-700 hover:text-accent">New Arrivals</a>
            <a href="#" class="block text-red-500 hover:text-red-700">Sale</a>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-accent font-semibold">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>
</header>
