<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight italic uppercase">
                    {{ __('Admin Command Center') }}
                </h2>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mt-1">Dashboard Manajemen Produksi & Antrean</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('modalTambahPesanan').classList.remove('hidden')" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase shadow-[0_0_15px_rgba(79,70,229,0.4)] transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Pesanan Baru
                </button>
                <span class="px-4 py-1 bg-red-500/10 text-red-500 rounded-full text-xs font-black uppercase border border-red-500/20 shadow-[0_0_10px_rgba(239,68,68,0.1)]">
                    Mode Admin
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#050505] min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-96 h-96 bg-indigo-600/10 rounded-full filter blur-[100px] -top-24 -right-32"></div>
            <div class="absolute w-80 h-80 bg-purple-600/10 rounded-full filter blur-[100px] bottom-20 left-0"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl shadow-sm font-bold text-sm flex items-center animate-bounce-short">
                    <div class="bg-emerald-500 p-1 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl shadow-sm font-bold text-sm flex items-center">
                    <div class="bg-red-500 p-1 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 group hover:border-indigo-500/50 transition-all">
                    <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Total Pesanan</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $orders->count() }}</p>
                    <p class="text-[9px] text-indigo-400 mt-1 font-bold uppercase tracking-tighter">Seluruh data masuk</p>
                </div>

                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 border-l-4 border-l-indigo-500 group hover:border-indigo-500/50 transition-all">
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Total Omzet</p>
                    <p class="text-2xl font-black text-white mt-1">Rp{{ number_format($orders->where('status', '!=', 'batal')->sum('total_price'), 0, ',', '.') }}</p>
                    <p class="text-[9px] text-zinc-500 mt-1 font-bold uppercase tracking-tighter">Akumulasi Nilai</p>
                </div>
                
                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 border-l-4 border-l-amber-500 group hover:border-amber-500/50 transition-all">
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Dikerjakan</p>
                    <p class="text-3xl font-black text-amber-500 mt-1">{{ $orders->where('status', 'dikerjakan')->count() }}</p>
                    <p class="text-[9px] text-zinc-500 mt-1 font-bold uppercase tracking-tighter">Tahap Produksi</p>
                </div>

                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 border-l-4 border-l-blue-500 group hover:border-blue-500/50 transition-all">
                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Pengantaran</p>
                    <p class="text-3xl font-black text-blue-500 mt-1">{{ $orders->where('status', 'pengantaran')->count() }}</p>
                    <p class="text-[9px] text-zinc-500 mt-1 font-bold uppercase tracking-tighter">Logistik</p>
                </div>

                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 border-l-4 border-l-emerald-500 group hover:border-emerald-500/50 transition-all">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Selesai</p>
                    <p class="text-3xl font-black text-emerald-500 mt-1">{{ $orders->where('status', 'sampai')->count() }}</p>
                    <p class="text-[9px] text-zinc-500 mt-1 font-bold uppercase tracking-tighter">Transaksi Berhasil</p>
                </div>

                <div class="bg-zinc-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-zinc-800 border-l-4 border-l-red-500 group hover:border-red-500/50 transition-all">
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Dibatalkan</p>
                    <p class="text-3xl font-black text-red-500 mt-1">{{ $orders->where('status', 'batal')->count() }}</p>
                    <p class="text-[9px] text-zinc-500 mt-1 font-bold uppercase tracking-tighter">Void / Cancel</p>
                </div>
            </div>

            <div class="bg-zinc-900/50 backdrop-blur-xl shadow-2xl rounded-2xl overflow-hidden border border-zinc-800 mb-8">
                <div class="p-6 bg-zinc-900/80 border-b border-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-black text-lg text-white uppercase italic tracking-tight">Manajemen Antrean <span class="text-indigo-400">Sablon</span></h3>
                    <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Data Terbaru: {{ date('d/m/Y') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-zinc-950 text-zinc-500 text-[11px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">Pelanggan</th>
                                <th class="px-6 py-4 text-left">Visual</th>
                                <th class="px-6 py-4 text-left">Produk & Print</th>
                                <th class="px-6 py-4 text-left">Size & Qty</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800">
                            @forelse($orders as $order)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-black mr-3 uppercase">
                                            {{ substr($order->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white capitalize">{{ $order->user->name ?? 'Guest' }}</div>
                                            <div class="text-[10px] text-zinc-500 font-black uppercase tracking-tighter">Order ID #{{ $order->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative group">
                                        @if($order->design_file)
                                            @php
                                                $designPath = $order->design_file;
                                                if (Str::startsWith($designPath, ['http://', 'https://'])) {
                                                    $finalUrl = $designPath;
                                                } elseif (Str::startsWith($designPath, 'images/')) {
                                                    $finalUrl = asset($designPath);
                                                } else {
                                                    $finalUrl = asset('storage/' . $designPath);
                                                }
                                            @endphp
                                            <a href="{{ $finalUrl }}" target="_blank">
                                                <img src="{{ $finalUrl }}" 
                                                     class="w-16 h-16 object-cover rounded-lg border border-zinc-700 shadow-lg group-hover:scale-110 transition duration-300 cursor-zoom-in"
                                                     onerror="this.onerror=null; this.src='https://placehold.co/100x100/18181b/4f46e5?text=Logo+Err'">
                                            </a>
                                        @else
                                            <div class="w-16 h-16 bg-zinc-950 rounded-lg flex items-center justify-center text-[9px] text-zinc-700 font-black border-2 border-dashed border-zinc-800 uppercase">No Design</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-white uppercase tracking-wider">{{ $order->category ?? 'Kaos' }} - {{ $order->package_name ?? 'Custom' }}</span>
                                        <div class="flex items-center mt-1">
                                            <span class="px-1.5 py-0.5 bg-indigo-500/10 text-indigo-400 text-[9px] font-bold rounded border border-indigo-500/20 uppercase">{{ $order->printing_type ?? 'DTF' }}</span>
                                            <span class="ml-2 text-[10px] text-zinc-400 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <span class="px-2 py-0.5 bg-zinc-800 text-indigo-400 rounded text-xs font-black">{{ $order->size }}</span>
                                        <span class="ml-1 font-bold text-zinc-300">{{ $order->quantity }} Pcs</span>
                                    </div>
                                    <p class="text-[11px] text-zinc-500 mt-1 italic truncate max-w-[150px]" title="{{ $order->notes }}">{{ $order->notes ?? 'Tanpa catatan' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-zinc-800 text-zinc-400 border border-zinc-700',
                                            'dikerjakan' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                            'selesai_produksi' => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                                            'pengantaran' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                            'sampai' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                            'batal' => 'bg-red-500/10 text-red-500 border border-red-500/20',
                                        ];
                                        $statusKey = $order->status ?? 'pending';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusStyles[$statusKey] ?? $statusStyles['pending'] }}">
                                        {{ str_replace('_', ' ', $statusKey) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap justify-center items-center gap-2">
                                        @if($order->status == 'pending')
                                        <form action="{{ route('admin.order.update', [$order->id, 'dikerjakan']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black transition shadow-lg uppercase tracking-wider">PROSES</button>
                                        </form>
                                        <form action="{{ route('admin.order.update', [$order->id, 'batal']) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 px-3 py-1.5 rounded-lg text-[9px] font-black transition uppercase tracking-wider">BATALKAN</button>
                                        </form>
                                        @endif

                                        @if($order->status == 'dikerjakan')
                                        <form action="{{ route('admin.order.update', [$order->id, 'selesai_produksi']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black transition shadow-lg uppercase tracking-wider">PRODUKSI SELESAI</button>
                                        </form>
                                        @endif

                                        @if($order->status == 'selesai_produksi')
                                        <form action="{{ route('admin.order.update', [$order->id, 'pengantaran']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black transition shadow-lg uppercase tracking-wider">ANTAR PAKET</button>
                                        </form>
                                        @endif

                                        @if($order->status == 'pengantaran')
                                        <form action="{{ route('admin.order.update', [$order->id, 'sampai']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black transition shadow-lg uppercase tracking-wider">SAMPAI TUJUAN</button>
                                        </form>
                                        @endif

                                        @if($order->status == 'sampai')
                                            <span class="text-[10px] font-black text-emerald-500 italic tracking-widest uppercase">✓ Selesai</span>
                                        @endif

                                        @if($order->status == 'batal')
                                            <span class="text-[10px] font-black text-red-500 italic tracking-widest uppercase">✘ Batal</span>
                                        @endif
                                        
                                        <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus permanen data pesanan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-zinc-600 hover:text-red-500 p-1.5 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-zinc-500 font-bold uppercase tracking-widest text-xs">Antrean produksi masih kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambahPesanan" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalTambahPesanan').classList.add('hidden')"></div>
            <div class="relative bg-zinc-900 border border-zinc-800 rounded-3xl max-w-lg w-full p-8 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tight">Input <span class="text-indigo-400">Pesanan Manual</span></h3>
                    <button onclick="document.getElementById('modalTambahPesanan').classList.add('hidden')" class="text-zinc-500 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Pilih Pelanggan</label>
                            <select name="user_id" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled selected>Pilih User...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Kategori Produk</label>
                                <select name="category" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="Kaos">Kaos</option>
                                    <option value="Hoodie">Hoodie</option>
                                    <option value="Polo">Polo</option>
                                    <option value="Totebag">Totebag</option>
                                    <option value="Jersey">Jersey</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Nama Paket</label>
                                <input type="text" name="package_name" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Combed 30s" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Tipe Print</label>
                                <select name="printing_type" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="DTF">DTF (Digital)</option>
                                    <option value="Plastisol">Plastisol (Manual)</option>
                                    <option value="Rubber">Rubber (Manual)</option>
                                    <option value="Polyflex">Polyflex</option>
                                    <option value="Sublime">Sublime</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Ukuran</label>
                                <select name="size" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Jumlah (Pcs)</label>
                                <input type="number" name="quantity" min="1" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="12" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Total Harga (Rp)</label>
                                <input type="number" name="total_price" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="500000" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">File Desain</label>
                            <input type="file" name="design_file" accept="image/*" class="w-full text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 ml-1">Catatan Produksi</label>
                            <textarea name="notes" rows="2" class="w-full bg-zinc-950 border-zinc-800 rounded-xl text-sm text-zinc-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Warna kaos, posisi sablon..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-black py-3 rounded-2xl hover:bg-indigo-500 shadow-[0_0_20px_rgba(79,70,229,0.4)] transition-all uppercase text-sm tracking-widest">
                            Simpan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce-short {
            animation: bounce-short 1.5s ease-in-out infinite;
        }
    </style>
</x-app-layout>