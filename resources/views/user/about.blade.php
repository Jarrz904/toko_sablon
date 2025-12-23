<x-app-layout>

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

    <div id="pricelist" class="relative min-h-screen bg-[#050505] flex items-center py-24 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-indigo-900/10 via-transparent to-transparent">
        </div>

        <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white tracking-widest">
                    Daftar Harga <span class="text-indigo-500">Sablon</span>
                </h2>
                <div class="w-16 h-1 bg-indigo-600 mx-auto mt-4 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.8)]">
                </div>
                <p class="text-gray-500 mt-6 max-w-xl mx-auto text-sm font-medium">
                    Pilih paket sesuai kebutuhan komunitas atau brand Anda dengan standar vendor distro.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

                <div
                    class="group bg-white/[0.03] border border-white/5 rounded-[2.5rem] p-8 transition-all hover:bg-white/[0.05] hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10">
                    <h3 class="text-lg font-extrabold uppercase mb-1 text-white">Paket Satuan</h3>
                    <p class="text-indigo-400/60 text-[10px] font-bold tracking-widest mb-6">DTF DIGITAL PRINTING</p>

                    <div class="flex items-end gap-2 mb-8">
                        <span class="text-4xl font-black text-white italic tracking-tighter">Rp 85rb</span>
                        <span class="text-gray-500 text-xs mb-1">/ pcs</span>
                    </div>

                    <ul class="space-y-4 text-xs text-gray-400 mb-10">
                        <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Tanpa Minimal Order
                        </li>
                        <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Cotton Combed 30s
                        </li>
                        <li class="flex items-center gap-3"><span class="text-indigo-500">✔</span> Full Color Bebas
                            Desain</li>
                        <li class="flex items-center gap-3 opacity-50"><span class="text-indigo-500">✔</span> Estimasi
                            1–2 Hari</li>
                    </ul>

                    <a href="{{ route('order.create') }}"
                        class="block text-center py-4 bg-white/10 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-600 transition-all border border-white/5">
                        Order Sekarang
                    </a>
                </div>

                <div
                    class="relative bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 shadow-2xl shadow-indigo-600/20 transform md:-translate-y-8 border border-indigo-400/30">
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-indigo-600 text-[10px] font-black px-5 py-1.5 rounded-full shadow-xl tracking-tighter">
                        BEST SELLER
                    </div>

                    <h3 class="text-lg font-extrabold uppercase mb-1 text-white">Paket Vendor</h3>
                    <p class="text-indigo-200/80 text-[10px] font-bold tracking-widest mb-6">MANUAL PLASTISOL</p>

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
                    <p class="text-emerald-400/60 text-[10px] font-bold tracking-widest mb-6">FULL CUSTOM PREMIUM</p>

                    <div class="flex items-end gap-2 mb-8">
                        <span class="text-4xl font-black text-white italic tracking-tighter">Rp 75rb</span>
                        <span class="text-gray-500 text-xs mb-1">/ pcs</span>
                    </div>

                    <ul class="space-y-4 text-xs text-gray-400 mb-10">
                        <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Minimal 24 pcs</li>
                        <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Combed 24s Tebal
                        </li>
                        <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Free Label & Hangtag
                        </li>
                        <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Packing Premium</li>
                    </ul>

                    <a href="{{ route('order.create') }}"
                        class="block text-center py-4 bg-white/10 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-emerald-600 transition-all border border-white/5">
                        Order Sekarang
                    </a>
                </div>
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
                        📤
                    </div>
                    <span class="font-bold uppercase text-white text-[10px] tracking-widest">1.Upload Desain</span>
                </div>
                <div class="group flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                        📦
                    </div>
                    <span class="font-bold uppercase text-white text-[10px] tracking-widest">2.Pilih Paket</span>
                </div>
                <div class="group flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                        🖨️
                    </div>
                    <span class="font-bold uppercase text-white text-[10px] tracking-widest">3.Produksi</span>
                </div>
                <div class="group flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-white/5 text-white rounded-[20px] border border-white/10 flex items-center justify-center text-2xl mb-4 transition-all group-hover:bg-indigo-600 group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                        🚚
                    </div>
                    <span class="font-bold uppercase text-white text-[10px] tracking-widest">4.Pengiriman</span>
                </div>
            </div>
        </div>
    </div>

    <div class="py-32 bg-[#050505] text-center text-white relative overflow-hidden border-t border-white/5">
        <div class="absolute top-[-50%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/10 blur-[150px] rounded-full">
        </div>
        <div
            class="absolute bottom-[-50%] right-[-10%] w-[500px] h-[500px] bg-emerald-500/10 blur-[150px] rounded-full">
        </div>

        <div class="relative z-10 px-4">
            <h2 class="text-4xl md:text-5xl font-black mb-6 uppercase italic tracking-tighter">
                Siap Cetak <span class="text-indigo-500 text-opacity-80">Sekarang?</span>
            </h2>
            <p class="text-gray-500 mb-12 max-w-lg mx-auto text-base font-medium">
                Konsultasi desain <span class="text-white">GRATIS</span> & fast respon admin kami siap melayani ide
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

    <style>
        html {
            scroll-behavior: smooth;
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
    </style>

</x-app-layout>