<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Journa Sablon</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Space+Grotesk:wght@300;500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --neon-indigo: #6366f1;
            --neon-blue: #38bdf8;
            --dark-bg: #0a0a0c;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background-color: var(--dark-bg);
            color: #e2e8f0;
        }

        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Dark Neon Background Gradients */
        .neon-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(56, 189, 248, 0.15) 0%, transparent 40%),
                #050505;
        }

        /* Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--neon-indigo);
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.2);
            transform: translateY(-5px);
        }

        /* Text Effects */
        .text-neon {
            color: #fff;
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.8), 0 0 20px rgba(99, 102, 241, 0.4);
        }

        .text-outline-neon {
            -webkit-text-stroke: 1px var(--neon-blue);
            color: transparent;
            filter: drop-shadow(0 0 5px var(--neon-blue));
        }

        /* Animations */
        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .bg-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            animation: pulse-glow 8s ease-in-out infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0c;
        }

        ::-webkit-scrollbar-thumb {
            background: #2d2d35;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neon-indigo);
        }
    </style>
</head>

<body class="antialiased">
    <div class="neon-bg"></div>

    <nav class="fixed w-full top-0 z-50 px-4 py-4 sm:px-6">
        <div
            class="max-w-7xl mx-auto bg-black/40 backdrop-blur-2xl border border-white/10 px-4 sm:px-8 py-3 rounded-2xl flex justify-between items-center shadow-2xl">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group shrink-0">
                <div
                    class="w-32 h-14 rounded-xl overflow-hidden bg-gradient-to-br from-indigo-500 to-blue-500 p-[2px] group-hover:scale-105 transition-all duration-500 shadow-lg shadow-indigo-500/20">
                    <div
                        class="w-full h-full bg-transparent rounded-[10px] flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/Logo.jpg') }}" alt="Logo"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                </div>

                <div class="leading-tight">
                    <div class="text-lg font-black heading-font tracking-tight uppercase text-white">Journa <span
                            class="text-indigo-400">Sablon</span></div>
                    <div class="text-[9px] text-indigo-300/60 tracking-widest uppercase font-bold">Premium Cyber Print
                    </div>
                </div>
            </a>

            <div
                class="hidden md:flex items-center space-x-8 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                <a href="#keunggulan" class="hover:text-white transition-colors">Keunggulan</a>
                <a href="#produk" class="hover:text-white transition-colors">Katalog</a>
                <a href="#testimoni" class="hover:text-white transition-colors">Testimoni</a>
                <a href="#hubungi" class="hover:text-white transition-colors">Hubungi</a>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 rounded-xl bg-indigo-600 text-white font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2.5 bg-white text-black rounded-xl font-bold text-xs uppercase shadow-xl transition hover:bg-indigo-400 hover:text-white">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

  <section class="relative min-h-screen flex items-center pt-24 pb-12 overflow-hidden">
    <div class="bg-glow absolute -top-20 -left-32 w-[500px] h-[500px] bg-indigo-600/20"></div>
    <div class="bg-glow absolute top-40 right-0 w-[400px] h-[400px] bg-blue-600/10"></div>

    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center relative z-10">
        <div class="space-y-8 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/30 rounded-full">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest">
                    Digital Printing Specialist
                </span>
            </div>

            <h1 class="text-6xl md:text-8xl font-black heading-font leading-[0.9] uppercase tracking-tighter italic text-white">
                Abadikan <br>
                <span class="text-outline-neon">Vibes</span> <br>
                Dalam <span class="text-indigo-400">Visual</span>
            </h1>

            <p class="text-lg text-gray-400 max-w-lg mx-auto lg:mx-0 leading-relaxed font-medium">
                Standar distro premium dengan sentuhan teknologi sablon masa kini. 
                Tinta Plastisol HD & DTF High-Res untuk hasil maksimal.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="{{ route('register') }}"
                   class="px-10 py-5 bg-indigo-600 text-white rounded-2xl font-black text-lg uppercase tracking-tighter hover:bg-indigo-500 transition shadow-[0_0_30px_rgba(99,102,241,0.4)] italic">
                    Mulai Pesan Sekarang
                </a>
            </div>
        </div>

        <div class="relative w-full max-w-lg mx-auto lg:ml-auto">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 relative w-full aspect-[16/9] md:aspect-[21/9] overflow-hidden rounded-[3rem] border border-white/10 group">
                    <img src="{{ asset('images/foto1.jpg') }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-[3s] ease-in-out opacity-80 group-hover:opacity-100"
                         alt="Main Photo">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent pointer-events-none"></div>

                    <div class="absolute bottom-8 left-8 z-10">
                        <p class="text-white font-black text-xs tracking-[0.3em] uppercase mb-2">Ukuran</p>
                    </div>
                </div>

                <div class="aspect-square rounded-[2rem] overflow-hidden border border-white/10 group bg-zinc-900">
                    <img src="{{ asset('images/foto2.jpg') }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-60 group-hover:opacity-100"
                         alt="Sub Photo 1">
                </div>

                <div class="aspect-square rounded-[2rem] overflow-hidden border border-white/10 group bg-zinc-900">
                    <img src="{{ asset('images/foto3.jpg') }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-60 group-hover:opacity-100"
                         alt="Sub Photo 2">
                </div>
            </div>
        </div>
    </div>
</section>

    <section id="keunggulan" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-black heading-font uppercase mb-16 tracking-tighter italic text-white">Keunggulan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div class="p-10 glass-card rounded-[2.5rem]">
                    <div
                        class="w-14 h-14 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                        💎</div>
                    <h3 class="text-xl font-bold mb-4 uppercase text-white">Kualitas Premium</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Cotton Combed 24s/30s reaktif. Bahan adem, awet,
                        dan nyaman dipakai harian.</p>
                </div>
                <div class="p-10 glass-card rounded-[2.5rem]">
                    <div
                        class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-[0_0_15px_rgba(56,189,248,0.3)]">
                        🎨</div>
                    <h3 class="text-xl font-bold mb-4 uppercase text-white">Sablon Presisi</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Reproduksi warna 99% akurat dengan gradasi halus
                        menggunakan mesin DTF Large Format.</p>
                </div>
                <div class="p-10 glass-card rounded-[2.5rem]">
                    <div
                        class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-[0_0_15px_rgba(168,85,247,0.3)]">
                        ⚡</div>
                    <h3 class="text-xl font-bold mb-4 uppercase text-white">Produksi Kilat</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Workflow terdigitalisasi. Pesanan masuk langsung
                        diproses ke antrean produksi otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="produk" class="py-24 relative bg-black/20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black heading-font uppercase tracking-tighter italic text-white">Katalog</h2>
                <div class="w-24 h-1 bg-indigo-500 mx-auto mt-4 rounded-full shadow-[0_0_10px_#6366f1]"></div>
            </div>

            <div class="max-w-5xl mx-auto px-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto4.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="Plastisol">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-indigo-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Plastisol Raster</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Teknik sablon
                                    yang menggunakan pola titik titik kecil untuk menciptakan gambar atau desain pada
                                    berbagai media kain</span>
                            </div>
                        </div>
                    </div>




                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto5.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="DTF">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-blue-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Plastol glosy</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Full
                                    Color</span>

                            </div>
                        </div>
                    </div>

                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto6.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="Hoodie">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-purple-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Sablon plascharge ink</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Teknik sablon
                                    manual yang menggabungkan keunggulan tinta plastisol(berbasis minyak) dengan metode
                                    discharge(penghilang warna kain) untuk menghasilkan desain yang solid,cerah, dan
                                    lembut di permukaan kain katun</span>

                            </div>
                        </div>
                    </div>

                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto7.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="Polo">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-gray-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Sablon PUFF PRINT</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Sablon puff
                                    print adalah teknik sablon yang menggunakan tinta khusus yang mengembang saat
                                    dipanaskan, menciptakan efek timbul 3D pada desain</span>

                            </div>
                        </div>
                    </div>

                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto8.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="Cotton">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-emerald-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Catton combed 24s</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Menggunakan
                                    Plastisol ink</span><br>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">IDR
                                    65,000.00</span></br>

                            </div>
                        </div>
                    </div>

                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden glass-card p-2">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            <img src="{{ asset('images/foto9.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100"
                                alt="Merchandise">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4
                                    class="text-red-400 text-lg font-black heading-font uppercase italic leading-none mt-1">
                                    Cotton combed 30s</h4>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Menggunakan
                                    Plastisol ink </span><br>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">IDR
                                    60,000.00</span></br>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('orders.create') }}"
                    class="inline-flex items-center gap-4 px-12 py-5 bg-white text-black rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all duration-300 italic shadow-[0_0_20px_rgba(255,255,255,0.2)] group">
                    Buka Katalog & Harga
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:translate-x-2 transition-transform" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
                <div>
                    <h2 class="text-4xl font-black heading-font uppercase tracking-tighter italic text-white">Testimoni
                    </h2>
                    <p class="text-indigo-400/60 font-bold uppercase text-[10px] tracking-[0.4em] mt-2">Verified
                        Feedback</p>
                </div>
                <div class="text-indigo-600 font-black heading-font text-8xl opacity-10">"</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-8 rounded-[2rem] glass-card">
                    <p class="text-gray-400 font-medium italic mb-6 leading-relaxed">"Sablon plastisolnya gila sih,
                        detail banget dan teksturnya mantap. Bakal langganan buat brand clothing-ku."</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center font-bold text-indigo-400 border border-indigo-500/30">
                            R</div>
                        <div>
                            <div class="text-sm font-black uppercase italic text-white">Rian Ardiansyah</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Brand Owner</div>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-[2rem] glass-card">
                    <p class="text-gray-400 font-medium italic mb-6 leading-relaxed">"Pesan buat kaos panitia event,
                        hasilnya rapi dan kainnya beneran premium. Adminnya juga fast respon."</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center font-bold text-blue-400 border border-blue-500/30">
                            M</div>
                        <div>
                            <div class="text-sm font-black uppercase italic text-white">Maya Putri</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Event Organizer
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-[2rem] glass-card">
                    <p class="text-gray-400 font-medium italic mb-6 leading-relaxed">"Paling suka sama hasil DTF-nya,
                        warnanya beneran keluar dan dicuci berkali-kali nggak rontok. Top!"</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center font-bold text-purple-400 border border-purple-500/30">
                            A</div>
                        <div>
                            <div class="text-sm font-black uppercase italic text-white">Aldi G.</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Customer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="hubungi" class="border-t border-white/5 pt-20 pb-10 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-16">
                <div class="space-y-6 text-center lg:text-left">
                    <div class="text-4xl font-black heading-font tracking-tighter uppercase italic text-white">
                        Journa<span class="text-indigo-500"> SABLON</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-md mx-auto lg:mx-0 font-medium">
                        Workshop sablon digital berbasis di Tegal. Menggabungkan estetika modern dengan kualitas
                        produksi konvensional terbaik.
                    </p>
                    <div
                        class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-xl text-indigo-400 text-[10px] font-black uppercase tracking-widest">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                        </span>
                        System Online: 24/7 Monitoring
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-center lg:text-left">
                    <div class="space-y-4">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-white">Connection</h4>
                        <div class="flex flex-col gap-3">
                            <a href="https://www.instagram.com/journastudio?igsh=MTd2ZG0yZmRhNHEycg=="
                                class="text-gray-500 hover:text-indigo-400 font-bold text-xs transition flex items-center justify-center lg:justify-start gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">IG</span>
                                @journastudio
                            </a>
                            <a href="https://wa.me/c/6289686202603"
                                class="text-gray-500 hover:text-green-400 font-bold text-xs transition flex items-center justify-center lg:justify-start gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">WA</span>
                                +62 8xx-xxxx-xxxx
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-white">Nexus Point</h4>
                        <p class="text-gray-500 text-xs font-bold leading-relaxed">
                            Jl. Raya Utama Tegal,<br>
                            Central Java, Cyber City
                        </p>
                        <a href="https://maps.app.goo.gl/mS2Lye55eigDZ8ab6?g_st=ipc(https://maps.app.goo.gl/mS2Lye55eigDZ8ab6?g_st=ipc)"
                            class="inline-block text-indigo-500 text-[10px] font-black uppercase tracking-widest underline underline-offset-8 decoration-indigo-500/30">
                            Satellite View (Maps)
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-gray-600 text-[9px] font-bold uppercase tracking-[0.4em]">
                    © 2025 Jarrz — Digital Artifact.
                </p>
                <div class="flex gap-6 opacity-30">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"
                        class="h-3">
                </div>
            </div>
        </div>
    </footer>
</body>

</html>