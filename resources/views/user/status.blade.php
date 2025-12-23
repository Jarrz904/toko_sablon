<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white leading-tight uppercase tracking-tighter italic">
            {{ __('Tracking Status Pesanan') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Memastikan latar belakang body tetap gelap jika layout utama belum diatur */
        body {
            background-color: #09090b !important;
        }

        /* Custom SweetAlert Dark Mode */
        .jarrz-dark-popup {
            background: #18181b !important;
            border: 1px solid #27272a !important;
        }
    </style>

    <div class="py-10 bg-[#09090b] min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            @forelse($activeOrders as $order)
                <div class="bg-zinc-900 rounded-3xl p-6 shadow-2xl border border-zinc-800 mb-6 transition-all hover:border-zinc-700">

                    <div class="flex items-start justify-between mb-6">
                        <div class="flex gap-4">
                            {{-- Image Container --}}
                            <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 rounded-2xl overflow-hidden bg-zinc-800 border border-white/5 relative group">
                                @php
                                    $imagePath = $order->design_file ?? $order->design;
                                    $finalUrl = '';

                                    if ($imagePath) {
                                        if (str_starts_with($imagePath, 'http')) {
                                            // Jika path adalah URL lengkap
                                            $finalUrl = $imagePath;
                                        } elseif (str_contains($imagePath, 'designs/')) {
                                            // Jika path adalah hasil upload user (masuk ke storage/designs)
                                            $finalUrl = asset('storage/' . $imagePath);
                                        } else {
                                            // Jika path merujuk ke folder public/images (Katalog)
                                            // Menghapus 'storage/' jika terbawa secara tidak sengaja di database
                                            $cleanPath = str_replace('storage/', '', $imagePath);
                                            $finalUrl = asset($cleanPath);
                                        }
                                    }
                                @endphp

                                @if($imagePath)
                                    <img src="{{ $finalUrl }}" 
                                         class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" 
                                         alt="Design Pesanan"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Order&background=27272a&color=fff';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-zinc-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <p class="text-[11px] font-bold text-indigo-400 uppercase tracking-widest">
                                    Order ID #{{ $order->id }}
                                </p>
                                <h3 class="text-lg font-black italic uppercase text-white leading-tight mt-1">
                                    {{ $order->size }} – {{ $order->quantity }} PCS
                                </h3>
                                
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                    <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider italic">
                                        Kategori: 
                                        <span class="text-white">
                                            @if(!empty($order->package_name))
                                                {{ $order->package_name }}
                                            @elseif(!empty($order->package))
                                                {{-- Tambahan jika kolom di database menggunakan nama 'package' --}}
                                                {{ $order->package }}
                                            @elseif(!empty($order->catalog_id) || !empty($order->product_id))
                                                Katalog: {{ $order->catalog_name ?? $order->product_name ?? 'Produk Katalog' }}
                                            @else
                                                Satuan ({{ $order->printing_type ?? 'DTF' }})
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <span class="flex items-center gap-1 px-3 py-1.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-[10px] font-black uppercase h-fit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>

                    {{-- Info Detail: Harga & Catatan --}}
                    <div class="grid grid-cols-2 gap-4 mb-6 border-y border-zinc-800/50 py-4">
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <p class="text-xl font-black text-emerald-400 italic">
                                @php
                                    $finalPrice = $order->total_price ?? $order->price ?? 0;
                                @endphp
                                Rp {{ number_format($finalPrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Catatan Pesanan</p>
                            <p class="text-[11px] text-zinc-300 font-medium leading-relaxed italic">
                                "{{ $order->notes ?? 'Tidak ada catatan' }}"
                            </p>
                        </div>
                    </div>

                    @php
                        $progress = [
                            'pending' => 20,
                            'dikerjakan' => 40,
                            'selesai_produksi' => 60,
                            'pengantaran' => 85,
                            'sampai' => 100
                        ];
                        $w = $progress[$order->status] ?? 10;
                    @endphp

                    {{-- Progress Bar --}}
                    <div class="mb-5">
                        <div class="h-[6px] rounded-full bg-zinc-800 overflow-hidden">
                            <div class="h-full bg-indigo-500 transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(99,102,241,0.4)]" 
                                 style="width: {{ $w }}%"></div>
                        </div>
                    </div>

                    {{-- Indikator Status --}}
                    <div class="grid grid-cols-5 gap-1 text-center text-[9px] font-bold uppercase mb-6 tracking-tighter">
                        <div class="{{ $w >= 20 ? 'text-indigo-400' : 'text-zinc-600' }}">⏳<br>Pending</div>
                        <div class="{{ $w >= 40 ? 'text-indigo-400' : 'text-zinc-600' }}">🛠<br>Produksi</div>
                        <div class="{{ $w >= 60 ? 'text-indigo-400' : 'text-zinc-600' }}">📦<br>Selesai</div>
                        <div class="{{ $w >= 85 ? 'text-indigo-400' : 'text-zinc-600' }}">🚚<br>Antar</div>
                        <div class="{{ $w == 100 ? 'text-emerald-400' : 'text-zinc-600' }}">✅<br>Sampai</div>
                    </div>

                    @if($order->status === 'pending')
                    <div class="pt-4 border-t border-zinc-800 flex justify-end">
                        <form id="cancel-form-{{ $order->id }}" action="{{ route('user.order.destroy', $order->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                onclick="confirmCancel('{{ $order->id }}')"
                                class="flex items-center gap-2 px-4 py-2 text-red-400 hover:bg-red-400/10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            @empty
                <div class="text-center py-20 bg-zinc-900 rounded-[2.5rem] border-2 border-dashed border-zinc-800">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-8 4-8-4" />
                            </svg>
                        </div>
                        <p class="text-zinc-500 font-black uppercase text-xs tracking-[0.2em]">
                            Belum ada pesanan aktif
                        </p>
                        <a href="{{ route('order.create') }}" class="mt-2 text-indigo-400 font-black text-[10px] uppercase tracking-widest hover:text-indigo-300 transition-colors">
                            Buat Pesanan Sekarang →
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function confirmCancel(orderId) {
            Swal.fire({
                title: '<span class="uppercase font-black italic tracking-tighter text-white">Batalkan Pesanan?</span>',
                html: '<p class="text-[11px] font-bold uppercase text-zinc-400 tracking-widest px-4">Tindakan ini tidak dapat dibatalkan. Pesanan Anda akan dihapus dari sistem.</p>',
                icon: 'warning',
                iconColor: '#f87171',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#27272a',
                confirmButtonText: 'YA, BATALKAN',
                cancelButtonText: '<span class="text-zinc-400">KEMBALI</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'jarrz-dark-popup rounded-[2rem] border-none shadow-2xl',
                    confirmButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3 border border-zinc-700'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + orderId).submit();
                }
            })
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            title: '<span class="uppercase font-black italic tracking-tighter text-emerald-400">Berhasil</span>',
            html: '<p class="text-[11px] font-bold uppercase text-zinc-400 tracking-widest">{{ session("success") }}</p>',
            icon: 'success',
            iconColor: '#10b981',
            timer: 2500,
            showConfirmButton: false,
            customClass: { 
                popup: 'jarrz-dark-popup rounded-[2rem] shadow-2xl border-none' 
            }
        });
    </script>
    @endif
</x-app-layout>