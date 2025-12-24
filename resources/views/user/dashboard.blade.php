<x-app-layout>
<div x-data="{ 
        // --- State Galeri/Modal ---
        openModal: false, 
        currentSlides: [], 
        currentIndex: 0,
        activeTitle: '',
        activePrice: 0, 
        qty: 1,
        selectedSize: 'M',
        notes: '',
        loading: false,

        // --- Fungsi Galeri ---
        openGallery(slides, title, price) {
            this.currentSlides = slides;
            this.activeTitle = title;
            this.activePrice = price;
            this.qty = 1; 
            this.currentIndex = 0;
            this.selectedSize = 'M';
            this.notes = '';
            this.openModal = true;
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.currentSlides.length;
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.currentSlides.length) % this.currentSlides.length;
        },
        setIndex(index) {
            this.currentIndex = index;
        },

        // --- Logika Perhitungan ---
        get totalPrice() {
            return this.qty * this.activePrice;
        },

        // --- Alur Pembayaran Terintegrasi (Store -> Midtrans -> Finalize) ---
        async checkout() {
            this.loading = true;
            
            // Siapkan data untuk tahap 1 (Request Token)
            let formData = new FormData();
            
            // PERBAIKAN: package_name mengambil dari activeTitle (Nama Desain di Katalog)
            // Ini agar validasi 'required' di Controller terpenuhi sesuai input user
            formData.append('package_name', this.activeTitle); 
            formData.append('quantity', this.qty);
            formData.append('size', this.selectedSize);
            formData.append('notes', this.notes);
            formData.append('total_price', this.totalPrice);
            
            // Logika File: Cek apakah ada file yang diupload manual
            const fileInput = document.querySelector('input[name=&quot;design_file&quot;]');
            if (fileInput && fileInput.files[0]) {
                formData.append('design_file', fileInput.files[0]);
            } else {
                // Jika tidak ada upload, kirim URL gambar katalog yang sedang aktif
                formData.append('catalog_image', this.currentSlides[this.currentIndex]);
            }

            try {
                // STEP 1: Request Token ke OrderController@store
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
                    // STEP 2: Panggil Midtrans Snap
                    window.snap.pay(result.snap_token, {
                        onSuccess: (res) => { this.finalizeDatabase(result, formData); },
                        onPending: (res) => { this.finalizeDatabase(result, formData); },
                        onError: (res) => { 
                            Swal.fire('Gagal', 'Pembayaran gagal', 'error');
                            this.loading = false;
                        },
                        onClose: () => {
                            // Kembalikan tombol jika user menutup popup tanpa bayar
                            this.loading = false;
                        }
                    });
                } else {
                    // Jika Laravel mengirim error validasi (422) atau error lainnya
                    let errorMsg = result.message;
                    if (result.errors) {
                        // Gabungkan semua pesan error jika ada banyak (misal: size required, dll)
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    throw new Error(errorMsg || 'Gagal terhubung ke server pembayaran');
                }
            } catch (error) {
                Swal.fire('Gagal Membuat Pesanan', error.message, 'error');
                this.loading = false;
            }
        },

        async finalizeDatabase(serverResult, originalData) {
            // STEP 3: Simpan permanen ke DB lewat OrderController@finalize
            originalData.append('snap_token', serverResult.snap_token);
            
            // Gunakan path file yang sudah diproses oleh server di tahap Store tadi
            if (serverResult.design_file) {
                originalData.append('design_file_path', serverResult.design_file);
            }

            try {
                let response = await fetch('{{ route('order.finalize') }}', {
                    method: 'POST',
                    body: originalData,
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
                Swal.fire('Error', 'Pembayaran berhasil, tapi gagal mencatat pesanan. Silakan hubungi admin.', 'warning');
                this.loading = false;
            }
        }
    }" 
    class="bg-[#050505] font-['Space_Grotesk'] antialiased">
        <div class="relative bg-black h-[550px] flex items-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80"
                    class="w-full h-full object-cover opacity-40 scale-105 animate-pulse-slow">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
            </div>

            <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-indigo-600/20 blur-[100px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[5%] w-80 h-80 bg-emerald-500/10 blur-[120px] rounded-full"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-bold tracking-widest uppercase mb-6">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Workshop Sablon Terbaik di Tegal
                </div>
                <h1 class="text-5xl font-extrabold mb-4 leading-tight tracking-tighter">
                    Cetak Kaos Impianmu <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Kualitas
                        Premium.</span>
                </h1>
                <p class="text-xl mb-8 text-gray-400 max-w-2xl leading-relaxed">
                    Journa Sablon melayani jasa sablon manual dan digital dengan hasil presisi,
                    warna tajam, dan bahan kaos terbaik.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('order.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-500 px-8 py-3 rounded-xl font-bold text-lg transition-all shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] hover:-translate-y-1">
                        Mulai Pesan
                    </a>
                    <a href="#pricelist"
                        class="bg-white/5 hover:bg-white/10 backdrop-blur-md px-8 py-3 rounded-xl font-bold text-lg border border-white/10 transition-all">
                        Lihat Harga
                    </a>
                </div>
            </div>
        </div>

        <div id="pricelist"
            class="relative bg-[#050505] py-24 overflow-hidden bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] bg-fixed">
            <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
                <div class="text-center mb-20">
                    <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white tracking-widest">
                        Daftar Harga <span class="text-indigo-500">Sablon</span>
                    </h2>
                    <div class="w-16 h-1 bg-indigo-600 mx-auto mt-4 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.8)]">
                    </div>
                    <p class="text-gray-500 mt-6 max-w-xl mx-auto text-sm font-medium">
                        Pilih jenis sablon dan bahan berkualitas tinggi untuk hasil maksimal.
                    </p>
                </div>

                <div class="max-w-7xl mx-auto px-6 w-full relative z-10 mb-32">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-indigo-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto4.jpg') }}', '{{ asset('images/foto5.jpg') }}']; activeTitle = 'Plastisol Raster'; activePrice = 75000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto4.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Plastisol
                                        Raster</h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Detail titik
                                        presisi tinggi untuk gradasi desain rumit dan fotorealistik.</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-indigo-400 font-black text-lg italic">IDR 75.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto4.jpg') }}', '{{ asset('images/foto5.jpg') }}']; activeTitle = 'Plastisol Raster'; activePrice = 75000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-indigo-600/50 hover:bg-indigo-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-blue-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto5.jpg') }}', '{{ asset('images/foto7.jpg') }}']; activeTitle = 'Plastisol Glossy'; activePrice = 85000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto5.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Plastisol
                                        Glossy</h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Hasil akhir
                                        mengkilap memberikan kesan eksklusif, mewah, dan pantulan cahaya elegan.</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-blue-400 font-black text-lg italic">IDR 85.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto5.jpg') }}', '{{ asset('images/foto7.jpg') }}']; activeTitle = 'Plastisol Glossy'; activePrice = 85000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-blue-600/50 hover:bg-blue-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-purple-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto6.jpg') }}', '{{ asset('images/foto4.jpg') }}']; activeTitle = 'Plascharge Ink'; activePrice = 90000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto6.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Plascharge
                                        Ink</h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Warna cerah
                                        dengan tekstur handfeel yang menyatu dengan kain (soft feel).</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-purple-400 font-black text-lg italic">IDR 90.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto6.jpg') }}', '{{ asset('images/foto4.jpg') }}']; activeTitle = 'Plascharge Ink'; activePrice = 90000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-purple-600/50 hover:bg-purple-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-orange-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto7.jpg') }}', '{{ asset('images/foto8.jpg') }}']; activeTitle = 'Puff Print 3D'; activePrice = 95000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto7.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Puff Print
                                        3D</h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Efek timbul
                                        (emboss) ikonik yang memberikan dimensi visual nyata pada kaos.</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-orange-400 font-black text-lg italic">IDR 95.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto7.jpg') }}', '{{ asset('images/foto8.jpg') }}']; activeTitle = 'Puff Print 3D'; activePrice = 95000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-orange-600/50 hover:bg-orange-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-emerald-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto8.jpg') }}', '{{ asset('images/foto9.jpg') }}']; activeTitle = 'Cotton Combed 24s'; activePrice = 65000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto8.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Combed 24s
                                    </h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Bahan tebal
                                        premium, sangat awet, tidak menerawang, dan nyaman dipakai harian.</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-emerald-400 font-black text-lg italic">IDR 65.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto8.jpg') }}', '{{ asset('images/foto9.jpg') }}']; activeTitle = 'Cotton Combed 24s'; activePrice = 65000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-emerald-600/50 hover:bg-emerald-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group bg-zinc-900/40 border border-white/10 rounded-[2.5rem] p-4 transition-all duration-500 hover:border-red-500/50 hover:bg-zinc-900/60 flex flex-col h-[550px]">
                            <div @click="openModal = true; currentSlides = ['{{ asset('images/foto9.jpg') }}', '{{ asset('images/foto4.jpg') }}']; activeTitle = 'Cotton Combed 30s'; activePrice = 60000; currentIndex = 0; qty = 1;"
                                class="relative flex-[1.2] rounded-[2rem] overflow-hidden cursor-zoom-in bg-black">
                                <img src="{{ asset('images/foto9.jpg') }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 justify-between">
                                <div class="space-y-3">
                                    <h4 class="text-white font-black italic uppercase text-xl tracking-tight">Combed 30s
                                    </h4>
                                    <p class="text-zinc-500 text-sm italic leading-relaxed line-clamp-3">Bahan ringan,
                                        adem, dan menyerap keringat dengan sangat baik untuk cuaca panas.</p>
                                </div>
                                <div class="pt-6 border-t border-white/5 flex flex-col gap-4">
                                    <span class="text-red-400 font-black text-lg italic">IDR 60.000</span>
                                    <button
                                        @click="openModal = true; currentSlides = ['{{ asset('images/foto9.jpg') }}', '{{ asset('images/foto4.jpg') }}']; activeTitle = 'Cotton Combed 30s'; activePrice = 60000; currentIndex = 0; qty = 1;"
                                        class="w-full bg-transparent border-2 border-red-600/50 hover:bg-red-600 text-white text-xs font-black uppercase py-4 rounded-2xl transition-all active:scale-95 tracking-widest">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
                    <div class="text-center mb-20">
                        <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white tracking-widest">
                            Daftar Harga <span class="text-indigo-500">Paket</span>
                        </h2>
                        <div
                            class="w-16 h-1 bg-indigo-600 mx-auto mt-4 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.8)]">
                        </div>
                        <p class="text-gray-500 mt-6 max-w-xl mx-auto text-sm font-medium">
                            Pilih paket sesuai kebutuhan komunitas atau brand Anda dengan standar vendor distro.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pb-20">
                        <div
                            class="group bg-white/[0.03] border border-white/5 rounded-[2.5rem] p-8 transition-all hover:bg-white/[0.05] hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10">
                            <h3 class="text-lg font-extrabold uppercase mb-1 text-white">Paket Satuan</h3>
                            <p class="text-indigo-400/60 text-[10px] font-bold tracking-widest mb-6">DTF DIGITAL
                                PRINTING</p>

                            <div class="flex items-end gap-2 mb-8">
                                <span class="text-4xl font-black text-white italic tracking-tighter">Rp 85rb</span>
                                <span class="text-gray-500 text-xs mb-1">/ pcs</span>
                            </div>

                            <ul class="space-y-4 text-xs text-gray-400 mb-10">
                                <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Tanpa Minimal
                                    Order</li>
                                <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Cotton Combed
                                    30s</li>
                                <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Full Color
                                    Bebas Desain</li>
                                <li class="flex items-center gap-3 opacity-50"><span class="text-indigo-500">✔</span>
                                    Estimasi 1–2 Hari</li>
                            </ul>

                            <a href="{{ route('order.create') }}"
                                class="block text-center py-4 bg-white/10 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-600 transition-all border border-white/5">
                                Order Sekarang
                            </a>
                        </div>

                        <div
                            class="relative bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 shadow-2xl shadow-indigo-600/20 transform md:-translate-y-8 border border-indigo-400/30">
                            <div
                                class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-indigo-600 text-[10px] font-black px-5 py-1.5 rounded-full shadow-xl tracking-tighter uppercase">
                                Best Seller
                            </div>

                            <h3 class="text-lg font-extrabold uppercase mb-1 text-white">Paket Vendor</h3>
                            <p class="text-indigo-200/80 text-[10px] font-bold tracking-widest mb-6">MANUAL PLASTISOL
                            </p>

                            <div class="flex items-end gap-2 mb-8">
                                <span class="text-4xl font-black text-white italic tracking-tighter">Rp 65rb</span>
                                <span class="text-indigo-200 text-xs mb-1">/ pcs</span>
                            </div>

                            <ul class="space-y-4 text-xs text-white/90 mb-10">
                                <li class="flex items-center gap-3"><span>⚡</span> Minimal 12 pcs</li>
                                <li class="flex items-center gap-3"><span>⚡</span> Combed 24s / 30s</li>
                                <li class="flex items-center gap-3"><span>⚡</span> Plastisol Awet</li>
                                <li class="flex items-center gap-3"><span>⚡</span> Free Stiker</li>
                            </ul>

                            <a href="{{ route('order.create') }}"
                                class="block text-center py-4 bg-white text-indigo-700 rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition-all shadow-lg">
                                Order Sekarang
                            </a>
                        </div>

                        <div
                            class="group bg-white/[0.03] border border-white/5 rounded-[2.5rem] p-8 transition-all hover:bg-white/[0.05] hover:border-emerald-500/30 hover:shadow-2xl hover:shadow-emerald-500/10">
                            <h3 class="text-lg font-extrabold uppercase mb-1 text-white">Paket Brand</h3>
                            <p class="text-emerald-400/60 text-[10px] font-bold tracking-widest mb-6">FULL CUSTOM
                                PREMIUM</p>

                            <div class="flex items-end gap-2 mb-8">
                                <span class="text-4xl font-black text-white italic tracking-tighter">Rp 75rb</span>
                                <span class="text-gray-500 text-xs mb-1">/ pcs</span>
                            </div>

                            <ul class="space-y-4 text-xs text-gray-400 mb-10">
                                <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Minimal 24
                                    pcs</li>
                                <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Combed 24s
                                    Tebal</li>
                                <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Free Label &
                                    Hangtag</li>
                                <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Packing
                                    Premium</li>
                            </ul>

                            <a href="{{ route('order.create') }}"
                                class="block text-center py-4 bg-white/10 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-emerald-600 transition-all border border-white/5">
                                Order Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="py-24 bg-black relative">
                    <div class="max-w-6xl mx-auto px-6 text-center">
                        <h2 class="text-2xl md:text-3xl font-black mb-16 uppercase italic text-white tracking-widest">
                            Alur <span class="text-gray-500">Pemesanan</span>
                        </h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-sm relative">
                            <div class="group flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                                    📤</div>
                                <span class="font-bold uppercase text-white text-[10px] tracking-widest">1.Upload
                                    Desain</span>
                            </div>
                            <div class="group flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                                    📦</div>
                                <span class="font-bold uppercase text-white text-[10px] tracking-widest">2.Pilih
                                    Paket</span>
                            </div>
                            <div class="group flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                                    🖨️</div>
                                <span
                                    class="font-bold uppercase text-white text-[10px] tracking-widest">3.Produksi</span>
                            </div>
                            <div class="group flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                                    🚚</div>
                                <span
                                    class="font-bold uppercase text-white text-[10px] tracking-widest">4.Pengiriman</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-32 bg-[#050505] text-center text-white relative overflow-hidden border-t border-white/5">
                    <div
                        class="absolute top-[-50%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/10 blur-[150px] rounded-full">
                    </div>
                    <div
                        class="absolute bottom-[-50%] right-[-10%] w-[500px] h-[500px] bg-emerald-500/10 blur-[150px] rounded-full">
                    </div>

                    <div class="relative z-10 px-4">
                        <h2 class="text-4xl md:text-5xl font-black mb-6 uppercase italic tracking-tighter">
                            Siap Cetak <span class="text-indigo-500 text-opacity-80">Sekarang?</span>
                        </h2>
                        <p class="text-gray-500 mb-12 max-w-lg mx-auto text-base font-medium">
                            Konsultasi desain <span class="text-white">GRATIS</span> & fast respon admin kami siap
                            melayani ide
                            kreatifmu.
                        </p>

                        <a href="https://wa.me/c/6289686202603" target="_blank"
                            class="inline-flex items-center gap-4 bg-emerald-600 hover:bg-emerald-500 px-12 py-5 rounded-2xl font-black uppercase tracking-widest text-sm transition-all shadow-[0_15px_30px_-10px_rgba(16,185,129,0.5)] hover:-translate-y-2 active:scale-95">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.438 9.889-9.886.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.98z" />
                            </svg>
                            WhatsApp Admin
                        </a>
                    </div>
                </div>

                <div x-show="openModal" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-black"
                    @keydown.escape.window="openModal = false">

                    <div @click="openModal = false" class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>

                    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data"
                        class="relative w-full max-w-7xl h-[90vh] bg-zinc-950 rounded-[2.5rem] overflow-hidden shadow-[0_0_80px_rgba(0,0,0,1)] border border-white/10 flex flex-col md:flex-row">
                        @csrf

                        <input type="hidden" name="package_name" x-bind:value="activeTitle">
                        <input type="hidden" name="total_price" x-bind:value="qty * activePrice">
                        <input type="hidden" name="source" value="catalog">

                        <input type="hidden" name="catalog_image" x-bind:value="currentSlides[0]">

                        <button type="button" @click="openModal = false"
                            class="absolute top-6 right-6 z-[130] w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-red-600 hover:text-white transition-all duration-300 shadow-2xl group">
                            <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div class="relative flex-[4] bg-black overflow-hidden flex items-center justify-center">
                            <template x-for="(slide, index) in currentSlides" :key="index">
                                <div x-show="currentIndex === index"
                                    x-transition:enter="transition duration-500 ease-in-out"
                                    x-transition:enter-start="opacity-0 scale-105"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute inset-0 flex items-center justify-center p-4 sm:p-12">
                                    <img :src="slide"
                                        class="max-w-full max-h-full object-contain select-none shadow-2xl rounded-2xl">
                                </div>
                            </template>

                            <div
                                class="absolute inset-x-8 top-1/2 -translate-y-1/2 flex justify-between pointer-events-none z-10">
                                <button type="button" @click="prev()"
                                    class="pointer-events-auto w-14 h-14 rounded-full bg-white/10 hover:bg-indigo-600 text-white backdrop-blur-md transition-all border border-white/10 flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button type="button" @click="next()"
                                    class="pointer-events-auto w-14 h-14 rounded-full bg-white/10 hover:bg-indigo-600 text-white backdrop-blur-md transition-all border border-white/10 flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div
                            class="w-full md:w-[450px] p-10 flex flex-col justify-between border-l border-white/5 bg-zinc-950 text-white z-20 overflow-y-auto">
                            <div class="space-y-6">
                                <div>
                                    <span
                                        class="text-indigo-500 text-[10px] font-black uppercase tracking-[0.4em]">Detail
                                        Pilihan</span>
                                    <h3 class="text-4xl font-black italic uppercase text-white leading-tight mt-2"
                                        x-text="activeTitle"></h3>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label
                                            class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest">Jumlah
                                            (Qty)</label>
                                        <input type="number" name="quantity" x-model.number="qty" min="1" required
                                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 outline-none text-white">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest">Ukuran</label>
                                        <select name="size" required
                                            class="w-full bg-zinc-900 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 outline-none text-white appearance-none">
                                            <option value="S">S</option>
                                            <option value="M" selected>M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest">Ganti
                                        Desain (Opsional)</label>
                                    <input type="file" name="design_file"
                                        class="w-full text-xs text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 hover:file:bg-indigo-600 transition-all">
                                    <p class="text-[9px] text-zinc-500 italic">*Biarkan kosong jika ingin desain katalog
                                        asli</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest">Catatan
                                        Tambahan</label>
                                    <textarea name="notes" rows="2"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 outline-none resize-none text-white"
                                        placeholder="Contoh: Kaos Putih, Desain di depan.."></textarea>
                                </div>
                            </div>

                            <div class="pt-8 mt-8 border-t border-white/10 space-y-6">
    <div class="flex flex-col">
        <span class="text-zinc-500 text-[11px] font-black uppercase mb-1 tracking-wider">
            Total Harga
        </span>
        <span id="display-total-price" class="text-white font-black italic text-4xl tracking-tighter"
            x-text="'Rp ' + (qty * activePrice).toLocaleString('id-ID')">
        </span>
    </div>

    <button type="button" id="pay-button"
        class="w-full bg-white text-black py-6 rounded-2xl font-black uppercase text-sm tracking-[0.2em] hover:bg-indigo-600 hover:text-white transition-all transform active:scale-95 shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed">
        Konfirmasi & Bayar Sekarang
    </button>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');

        if (payButton) {
            payButton.addEventListener('click', async function (e) {
                e.preventDefault();

                // Ambil data langsung dari scope Alpine.js
                // Kita gunakan Alpine.evaluate untuk mengambil data terbaru dari state
                const alpineData = Alpine.$data(payButton.closest('[x-data]'));

                // Set loading state
                payButton.disabled = true;
                payButton.innerText = "MENGHUBUNGKAN...";

                const formData = new FormData();

                // 1. Ambil data dari state Alpine agar sinkron dengan UI
                // Perbaikan: Mengambil activeTitle sebagai package_name agar lolos validasi 'required'
                formData.append('package_name', alpineData.activeTitle || 'Satuan'); 
                formData.append('quantity', alpineData.qty || 1);
                formData.append('size', alpineData.selectedSize || 'M');
                formData.append('notes', alpineData.notes || '');
                formData.append('total_price', alpineData.qty * alpineData.activePrice);

                // 2. Logika File Desain (Katalog vs Upload)
                const fileInput = document.querySelector('input[name="design_file"]');
                if (fileInput && fileInput.files[0]) {
                    formData.append('design_file', fileInput.files[0]);
                } else {
                    // Ambil gambar dari slide yang sedang aktif di Alpine
                    const currentImg = alpineData.currentSlides[alpineData.currentIndex];
                    formData.append('catalog_image', currentImg);
                }

                try {
                    // STEP 1: Request Snap Token ke Controller
                    const response = await fetch("{{ route('order.store') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.snap_token) {
                        // STEP 2: Panggil Midtrans Snap
                        window.snap.pay(result.snap_token, {
                            onSuccess: function (res) {
                                // Panggil fungsi finalize di Alpine jika ada, atau redirect
                                finalizeOrder(result, formData);
                            },
                            onPending: function (res) {
                                finalizeOrder(result, formData);
                            },
                            onError: function (res) {
                                alert("Pembayaran gagal!");
                                resetBtn(payButton);
                            },
                            onClose: function () {
                                resetBtn(payButton);
                            }
                        });
                    } else {
                        // Menangani error validasi Laravel
                        const errorMsg = result.message || "Data tidak valid";
                        alert('Gagal: ' + errorMsg);
                        resetBtn(payButton);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi sistem.');
                    resetBtn(payButton);
                }
            });
        }

        // Fungsi Helper untuk mereset tombol
        function resetBtn(btn) {
            btn.disabled = false;
            btn.innerText = "KONFIRMASI & BAYAR SEKARANG";
        }

        // Fungsi untuk mencatat pesanan ke DB setelah Midtrans sukses/pending
        async function finalizeOrder(serverResult, originalData) {
            originalData.append('snap_token', serverResult.snap_token);
            originalData.append('design_file', serverResult.design_file);

            try {
                const response = await fetch("{{ route('order.finalize') }}", {
                    method: 'POST',
                    body: originalData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const finalRes = await response.json();
                if (finalRes.status === 'success') {
                    window.location.href = finalRes.redirect_url;
                }
            } catch (e) {
                alert("Gagal mencatat pesanan ke database.");
            }
        }
    </script>
</div>
                        </div>
                    </form>
                </div>

            </div>

            <style>
                html {
                    scroll-behavior: smooth;
                }

                [x-cloak] {
                    display: none !important;
                }

                @keyframes pulse-slow {

                    0%,
                    100% {
                        transform: scale(1.05);
                    }

                    50% {
                        transform: scale(1.1);
                    }
                }

                .animate-pulse-slow {
                    animation: pulse-slow 10s infinite ease-in-out;
                }

                /* Custom scrollbar */
                ::-webkit-scrollbar {
                    width: 8px;
                }

                ::-webkit-scrollbar-track {
                    background: #050505;
                }

                ::-webkit-scrollbar-thumb {
                    background: #1f1f1f;
                    border-radius: 10px;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: #4f46e5;
                }
            </style>
</x-app-layout>