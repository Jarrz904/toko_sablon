<nav x-data="{ open: false }" class="bg-[#09090b] border-b border-zinc-800 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center">
                {{-- LOGO --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                     <img src="{{ asset('images/Logo.jpg') }}" 
                        class="w-10 h-10 object-contain brightness-110">

                    <div class="leading-tight">
                        <div class="text-lg font-extrabold text-white">
                            Journa Sablon
                        </div>
                        <div class="text-[11px] text-zinc-500">
                            Digital Printing
                        </div>
                    </div>
                </a>

                {{-- DESKTOP MENU --}}
                <div class="hidden sm:flex sm:ms-10 space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-zinc-400 hover:text-white transition">
                        Beranda
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role !== 'admin')
                            {{-- PERBAIKAN: Mengubah order.create menjadi orders.create --}}
                            <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')" class="text-zinc-400 hover:text-white transition">
                                Buat Pesanan
                            </x-nav-link>

                            <x-nav-link :href="route('user.status')" :active="request()->routeIs('user.status')" class="text-zinc-400 hover:text-white transition">
                                Status Pesanan
                            </x-nav-link>

                            <x-nav-link :href="route('user.history')" :active="request()->routeIs('user.history')" class="text-zinc-400 hover:text-white transition">
                                Riwayat
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" class="text-zinc-400 hover:text-white transition">
                                Kelola User
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-3 px-4 py-2 rounded-full bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 
                                       text-sm font-medium text-zinc-300 transition">
                                <span>{{ Auth::user()->name }}</span>

                                @if(Auth::user()->role === 'admin')
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full 
                                                 bg-red-500/10 text-red-400 uppercase">
                                        Admin
                                    </span>
                                @endif

                                <svg class="w-4 h-4 text-zinc-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-zinc-900 border border-zinc-800">
                                <x-dropdown-link :href="route('profile.edit')" class="text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
                                    Profile
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full 
                                   hover:bg-indigo-700 transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            {{-- MOBILE BUTTON --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-zinc-500 hover:text-white hover:bg-zinc-800 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-[#09090b] border-t border-zinc-800">
        <div class="pt-3 pb-2 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-zinc-400">Dashboard</x-responsive-nav-link>

            @auth
                @if(Auth::user()->role !== 'admin')
                    {{-- PERBAIKAN: Mengubah order.create menjadi orders.create --}}
                    <x-responsive-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')" class="text-zinc-400">
                        Buat Pesanan
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('user.status')" :active="request()->routeIs('user.status')" class="text-zinc-400">
                        Status Pesanan
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('user.history')" :active="request()->routeIs('user.history')" class="text-zinc-400">
                        Riwayat
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" class="text-zinc-400">
                        Kelola User
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-2 border-t border-zinc-800 px-4">
                <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-zinc-500">{{ Auth::user()->email }}</div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-zinc-400 hover:text-white transition">
                        Profile
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-zinc-400 hover:text-white transition">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-2 border-t border-zinc-800 px-4 space-y-1">
                <x-responsive-nav-link :href="route('login')" class="text-zinc-400">Login</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" class="text-zinc-400">Register</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>