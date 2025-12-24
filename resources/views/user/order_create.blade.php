<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-white leading-tight uppercase tracking-tight italic">
            {{ __('Buat Pesanan Baru') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- SDK Midtrans - Menggunakan URL Sandbox langsung agar stabil di Vercel --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700;800&display=swap');
        .font-space { font-family: 'Space Grotesk', sans-serif; }
        body { background: radial-gradient(circle at top left, #0a0a0c, #050505); color: #f4f4f5; }
        .fixed.z-\[999\] { z-index: 9999 !important; }
        .input-focus:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
        .form-card { border-radius: 2rem; background: rgba(18, 18, 20, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .paket-radio:checked + label { border-color: #6366f1; background: linear-gradient(180deg, rgba(99, 102, 241, 0.1), transparent); box-shadow: 0 10px 25px -10px rgba(99, 102, 241, 0.3); transform: translateY(-2px); }
        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .swal2-popup.jarrz-popup { font-family: 'Space Grotesk', sans-serif !important; border-radius: 2rem !important; padding: 2.5rem !important; background: #121214 !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; }
    </style>

    <div class="py-12 font-space fade-up"
         x-data="{ 
            paket: '{{ old('package_name', 'satuan') }}', 
            qty: {{ old('quantity', 1) }},
            loading: false,
            prices: {
                satuan: 85000,
                vendor: 65000,
                brand: 75000
            },
            get minQty() {
                if (this.paket === 'vendor') return 12;
                if (this.paket === 'brand') return 24;
                return 1;
            },
            get currentPrice() {
                return this.prices[this.paket];
            },
            get totalPrice() {
                return this.qty * this.currentPrice;
            },
            init() {
                this.$watch('paket', value => {
                    if (this.qty < this.minQty) this.qty = this.minQty;
                });
            },
            async submitForm() {
                if (this.qty < this.minQty) {
                    Swal.fire({
                        icon: 'error',
                        title: 'JUMLAH KURANG',
                        text: 'Minimal order untuk paket ini adalah ' + this.minQty + ' pcs',
                        customClass: { popup: 'jarrz-popup' }
                    });
                    return;
                }
                
                this.loading = true;

                try {
                    let formData = new FormData(this.$refs.orderForm);

                    // STEP 1: Request Token ke Server
                    let response = await fetch('{{ route('order.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    let result = await response.json();

                    if (response.ok && result.snap_token) {
                        // STEP 2: Munculkan Midtrans Snap
                        window.snap.pay(result.snap_token, {
                            onSuccess: (res) => { this.finalizeOrder(result, formData); },
                            onPending: (res) => { this.finalizeOrder(result, formData); },
                            onError: (res) => { 
                                Swal.fire('Gagal', 'Pembayaran gagal diproses', 'error');
                                this.loading = false;
                            },
                            onClose: () => {
                                Swal.fire({
                                    title: 'INFO',
                                    text: 'Pembayaran dibatalkan. Pesanan tidak akan diproses.',
                                    icon: 'info',
                                    customClass: { popup: 'jarrz-popup' }
                                });
                                this.loading = false;
                            }
                        });
                    } else {
                        throw new Error(result.message || 'Terjadi kesalahan validasi');
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'GAGAL',
                        text: error.message,
                        customClass: { popup: 'jarrz-popup' }
                    });
                    this.loading = false;
                }
            },

            async finalizeOrder(serverResult, originalFormData) {
                // Di Vercel, kita tidak boleh mengirim ulang objek File mentah karena session upload sudah selesai.
                // Kita buat FormData baru berisi data teks saja.
                let finalData = new FormData();
                
                originalFormData.forEach((value, key) => {
                    if (!(value instanceof File)) {
                        finalData.append(key, value);
                    }
                });

                // Ambil SNAP Token dan Path File yang sudah sukses di-upload dari serverResult
                finalData.append('snap_token', serverResult.snap_token);
                finalData.append('design_file_path', serverResult.design_file); 

                try {
                    // STEP 3: Simpan permanen ke DB
                    let response = await fetch('{{ route('order.finalize') }}', {
                        method: 'POST',
                        body: finalData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    let finalRes = await response.json();
                    if (finalRes.status === 'success') {
                        window.location.href = finalRes.redirect_url;
                    } else {
                        throw new Error(finalRes.message);
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DATABASE ERROR',
                        text: e.message || 'Gagal menyimpan pesanan ke database',
                        customClass: { popup: 'jarrz-popup' }
                    });
                    this.loading = false;
                }
            }
         }">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[9px] font-black tracking-widest uppercase mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Proses Produksi Terintegrasi
                </div>
                <h3 class="text-3xl font-black text-white uppercase tracking-tighter">
                    Lengkapi <span class="text-indigo-500 italic">Spesifikasi</span>
                </h3>
            </div>

            <div class="form-card shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden">
                <div class="h-1.5 w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600"></div>
                
                <div class="p-8 md:p-10">
                    <form x-ref="orderForm" method="POST" enctype="multipart/form-data" class="space-y-8" @submit.prevent="submitForm">
                        @csrf

                        {{-- Hidden inputs --}}
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="total_price" :value="totalPrice">
                        <input type="hidden" name="printing_type" value="Digital Sablon (DTF)">

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-5">Pilih Kategori Paket</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach([
                                    'satuan' => ['desc' => 'Tanpa Min. Order', 'price' => '85rb'], 
                                    'vendor' => ['desc' => 'Min. 12 Pcs', 'price' => '65rb'], 
                                    'brand' => ['desc' => 'Min. 24 Pcs', 'price' => '75rb']
                                ] as $key => $val)
                                <div class="relative">
                                    <input type="radio" name="package_name" id="p_{{ $key }}" value="{{ $key }}" x-model="paket" class="hidden paket-radio">
                                    <label for="p_{{ $key }}" class="flex flex-col p-5 border border-white/5 rounded-2xl cursor-pointer transition-all duration-300 bg-white/[0.02] hover:bg-white/[0.05]">
                                        <span class="text-xs font-black uppercase italic text-white tracking-tight">{{ $key }}</span>
                                        <span class="text-[14px] text-indigo-400 font-black mt-1">Rp {{ $val['price'] }}</span>
                                        <span class="text-[8px] text-zinc-500 font-bold uppercase mt-1">{{ $val['desc'] }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 mb-3">Unggah File Desain</label>
                            <div class="relative group">
                                <input type="file" name="design_file" required
                                       class="block w-full text-xs text-zinc-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 border-2 border-dashed border-zinc-800 rounded-2xl p-6 bg-black/40 transition-all group-hover:border-indigo-500/50">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Ukuran Kaos</label>
                                <select name="size" class="w-full border border-white/10 rounded-xl p-4 text-xs font-bold input-focus transition bg-zinc-900/50 text-white appearance-none">
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                        <option value="{{ $size }}" {{ old('size', 'M') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                    Jumlah (<span class="text-indigo-400" x-text="'Minimal ' + minQty"></span>)
                                </label>
                                <input type="number" name="quantity" x-model.number="qty" :min="minQty"
                                       class="w-full border border-white/10 rounded-xl p-4 text-xs font-black input-focus transition bg-zinc-900/50 text-white" required>
                                <p x-show="qty < minQty" class="text-red-400 text-[9px] font-black uppercase animate-pulse mt-2">⚠ Jumlah tidak sesuai syarat paket</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Catatan Produksi</label>
                            <textarea name="notes" rows="3" class="w-full border border-white/10 rounded-2xl p-4 text-xs font-bold input-focus transition bg-zinc-900/50 text-white placeholder-zinc-700" placeholder="Contoh: Warna Kaos Hitam, Sablon di Depan (A3)...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="p-6 bg-indigo-500/5 rounded-[2rem] border border-indigo-500/10 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">Harga Per Pcs</span>
                                <span class="text-sm font-black text-white italic" x-text="'Rp ' + currentPrice.toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-white/5">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Estimasi Total Tagihan</span>
                                <span class="text-xl font-black text-indigo-500 italic" x-text="'Rp ' + totalPrice.toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-4 px-5 py-3 bg-white/[0.03] rounded-2xl border border-white/5">
                                <div class="w-10 h-10 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 Pelangi 12 21.23a11.955 11.955 0 01-8.618-3.04z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-white">Quality Guard</p>
                                    <p class="text-[8px] font-bold text-zinc-500 uppercase">Premium Finishing</p>
                                </div>
                            </div>

                            <button type="submit"
                                    :disabled="qty < minQty || loading"
                                    :class="qty < minQty || loading ? 'opacity-30 cursor-not-allowed' : 'hover:scale-105 hover:shadow-[0_15px_30px_-10px_rgba(99,102,241,0.5)]'"
                                    class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.25em] transition-all duration-500 flex items-center justify-center gap-3">
                                <span x-show="!loading">KONFIRMASI & BAYAR</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    MENGHUBUNGKAN...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="mt-10 text-center text-zinc-600 text-[9px] font-black uppercase tracking-[0.3em] italic">
                Journa Sablon Digital Ecosystem <span class="mx-2 text-indigo-900">|</span> Precision v2.5
            </p>
        </div>
    </div>
</x-app-layout>