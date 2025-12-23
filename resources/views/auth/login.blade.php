<x-guest-layout>
    {{-- Container Luar: Dark Nexus Theme --}}
    <div class="min-h-screen flex items-center justify-center bg-[#050505] relative overflow-hidden px-4 font-['Space_Grotesk']">
        
        {{-- Efek Cahaya Neon di Latar Belakang --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-[500px] h-[500px] bg-indigo-600/20 rounded-full filter blur-[120px] -top-48 -left-24 animate-pulse"></div>
            <div class="absolute w-[400px] h-[400px] bg-blue-600/10 rounded-full filter blur-[100px] bottom-10 right-0"></div>
        </div>

        {{-- Card Glassmorphism --}}
        <div class="relative z-10 w-full max-w-md p-8 sm:p-10 bg-zinc-900/50 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10">
            
            {{-- Logo dengan Glow --}}
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-zinc-950 shadow-2xl flex items-center justify-center p-2 border border-zinc-800 group hover:border-indigo-500 transition-colors duration-500">
                <img src="{{ asset('images/Logo.jpg') }}" 
                         alt="Logo" class="w-full h-full object-contain brightness-110">
                </div>
            </div>

            {{-- Judul & Subtitle --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-white tracking-tight italic uppercase">
                    Selamat <span class="text-indigo-400">Datang</span>
                </h2>
                <p class="text-zinc-500 text-sm font-medium mt-2 uppercase tracking-tighter">Masuk ke Nexus Jarrz Sablon</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-[11px] font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Email Client</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-black rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700"
                           placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
                </div>

                {{-- Input Password --}}
                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="block text-[11px] font-bold text-indigo-400 uppercase tracking-widest">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-black text-zinc-500 hover:text-indigo-400 uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                                Lupa?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required 
                           class="w-full bg-zinc-950/50 border border-zinc-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-black rounded-2xl p-4 text-sm font-medium transition-all shadow-inner placeholder-zinc-700"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center ml-1">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-zinc-800 bg-zinc-950 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-zinc-900 cursor-pointer">
                    <span class="ml-2 text-[10px] font-bold text-zinc-500 uppercase tracking-widest cursor-pointer">Ingat Saya</span>
                </div>

                {{-- Button Login --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-xs transition-all shadow-[0_0_20px_rgba(79,70,229,0.4)] active:scale-[0.98]">
                        Masuk Sekarang
                    </button>
                </div>

                {{-- Footer Link --}}
                <div class="text-center mt-6">
                    <p class="text-[11px] font-bold text-zinc-600 uppercase tracking-widest">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 ml-1 transition-colors">Daftar</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>