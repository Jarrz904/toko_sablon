<x-guest-layout>
    {{-- Container Luar: Dark Nexus Theme --}}
    <div class="min-h-screen flex items-center justify-center bg-[#050505] relative overflow-hidden px-4 py-12 font-['Space_Grotesk']">
        
        {{-- Animated Background Glow --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-96 h-96 bg-purple-600/20 rounded-full filter blur-[100px] -top-24 -right-32 animate-pulse"></div>
            <div class="absolute w-80 h-80 bg-indigo-600/20 rounded-full filter blur-[100px] bottom-20 left-0"></div>
        </div>

        {{-- Glassmorphism Card --}}
        <div class="relative z-10 w-full max-w-md p-8 sm:p-10 bg-zinc-900/50 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10">
            
            {{-- Logo Wrapper --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-zinc-950 shadow-2xl flex items-center justify-center p-2 border border-zinc-800">
                        <img src="{{ asset('images/Logo.jpg') }}" 
                         alt="Logo" class="w-full h-full object-contain brightness-125">
                </div>
            </div>

            {{-- Title Section --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white tracking-tight italic uppercase">
                    Buat Akun <span class="text-indigo-400">Baru</span>
                </h2>
                <p class="text-zinc-500 text-[10px] font-bold mt-2 uppercase tracking-widest">Bergabung dengan Nexus Printing Kami</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700 outline-none"
                           placeholder="Nama Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700 outline-none"
                           placeholder="email@contoh.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700 outline-none"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700 outline-none"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
                </div>

                {{-- Register Button --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-xs transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)] active:scale-[0.98]">
                        Daftar Akun
                    </button>
                </div>

                {{-- Login Link --}}
                <div class="text-center mt-6">
                    <p class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 ml-1 transition-colors">Masuk</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>