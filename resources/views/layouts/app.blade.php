<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Journa Sablon') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Mengatur font default ke Space Grotesk dengan background gelap pekat */
            body {
                font-family: 'Space Grotesk', sans-serif;
                background-color: #09090b; /* Warna hitam pekat agar serasi dengan dashboard */
                color: #ffffff; /* Memastikan teks default menjadi putih */
            }

            /* Menghilangkan scrollbar putih bawaan browser jika diperlukan */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #09090b;
            }
            ::-webkit-scrollbar-thumb {
                background: #27272a;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #3f3f46;
            }
        </style>
    </head>
    <body class="antialiased text-white">
        <div class="min-h-screen bg-[#09090b]">
            @include('layouts.navigation')

            @isset($header)
                {{-- Header diubah menjadi gelap dengan blur tipis dan border samar --}}
                <header class="bg-[#09090b]/80 backdrop-blur-md sticky top-0 z-40 border-b border-zinc-800 shadow-lg">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script type="text/javascript">
        window.payMidtrans = function(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    window.location.href = "{{ route('user.status') }}";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda!");
                    window.location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    window.location.reload();
                },
                onClose: function() {
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
</body>
</html>
    </body>
</html>