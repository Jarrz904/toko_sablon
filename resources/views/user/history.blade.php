<x-app-layout>
    {{-- CSS Tambahan untuk memastikan latar belakang body ikut gelap --}}
    <x-slot name="header">
        <style>
            body { background-color: #09090b !important; }
        </style>
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="font-black text-2xl text-white uppercase tracking-tighter italic leading-none">
                {{ __('Riwayat Pesanan Selesai') }}
            </h2>
            <p class="text-[10px] font-bold text-zinc-400 mt-1.5 uppercase tracking-[0.2em]">
                Arsip transaksi yang telah berhasil diproses
            </p>
        </div>
    </x-slot>

    {{-- Container utama dengan min-h-screen agar warna gelap memenuhi layar --}}
    <div class="py-12 bg-[#09090b] min-h-screen text-zinc-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            
            {{-- Tabel Container --}}
            <div class="bg-zinc-900 shadow-2xl rounded-[2rem] border border-zinc-800/50 overflow-hidden">
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            {{-- Header Tabel dengan latar sedikit lebih terang --}}
                            <tr class="bg-zinc-800/80 border-b border-zinc-700/50">
                                <th class="px-8 py-5 text-left text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                                    Waktu Transaksi
                                </th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                                    Detail Item
                                </th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                                    Status
                                </th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                                    Opsi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-800/50">
                            @forelse($completedOrders as $order)
                            <tr class="hover:bg-white/[0.03] transition-colors group">
                                {{-- Waktu --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-xs font-bold text-zinc-100">
                                        {{ $order->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] font-medium text-zinc-500 uppercase mt-0.5">
                                        {{ $order->created_at->format('H:i') }} WIB
                                    </div>
                                </td>

                                {{-- Detail --}}
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-xs font-black text-white uppercase italic tracking-tight">
                                        {{ $order->size }} — {{ $order->quantity }} PCS
                                    </div>
                                    <div class="text-[10px] font-bold text-indigo-400 mt-0.5 tracking-wider">
                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>

                                {{-- Status Selesai --}}
                                <td class="px-8 py-6 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[9px] font-black uppercase tracking-wider border border-emerald-500/20 shadow-sm">
                                        <span class="w-1 h-1 bg-emerald-400 rounded-full animate-pulse"></span>
                                        Selesai
                                    </span>
                                </td>

                                {{-- Tombol Re-Order --}}
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <a href="{{ route('orders.create', ['reorder_id' => $order->id]) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-zinc-800 border border-zinc-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 hover:border-indigo-600 transition-all active:scale-95 shadow-lg shadow-black/50">
                                        Re-Order
                                    </a>
                                </td>
                            </tr>
                            @empty
                            {{-- State Jika Kosong --}}
                            <tr>
                                <td colspan="4" class="py-32 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">
                                            Tidak ada riwayat
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Footer Note --}}
            <div class="mt-8 text-center">
                <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.4em]">
                    Terimakasih sudah memesan di toko sablon kami
                </p>
            </div>
        </div>
    </div>
</x-app-layout>