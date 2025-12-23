<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight italic uppercase">
                    {{ __('Manajemen Pengguna') }}
                </h2>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mt-1">Daftar semua pengguna yang terdaftar di sistem</p>
            </div>
            <span class="px-4 py-1 bg-red-500/10 text-red-500 rounded-full text-xs font-black uppercase border border-red-500/20 shadow-[0_0_10px_rgba(239,68,68,0.1)]">
                Mode Admin
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-[#050505] min-h-screen relative overflow-hidden">
        {{-- Efek Cahaya Latar Belakang --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-96 h-96 bg-indigo-600/10 rounded-full filter blur-[100px] -top-24 -left-32"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-zinc-900/50 backdrop-blur-xl shadow-2xl rounded-2xl overflow-hidden border border-zinc-800">
                <div class="p-6 bg-zinc-900/80 border-b border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-lg text-white uppercase italic tracking-tight">Daftar <span class="text-indigo-400">Pengguna</span></h3>
                    <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Database User</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-zinc-950 text-zinc-500 text-[11px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">Nama</th>
                                <th class="px-6 py-4 text-left">Email</th>
                                <th class="px-6 py-4 text-left">Role</th>
                                <th class="px-6 py-4 text-left">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800">
                            @foreach($users as $user)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-black text-indigo-400 mr-3 border border-zinc-700">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-bold text-white capitalize">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-400 font-medium">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $user->role == 'admin' ? 'bg-red-500/10 text-red-500 border-red-500/20' : 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-zinc-500 font-bold uppercase tracking-tighter">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>