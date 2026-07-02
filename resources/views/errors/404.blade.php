<!-- Placeholder untuk halaman error 404 (Halaman Tidak Ditemukan) -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Membeku! | Niki Frozen</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .floating-logo {
            animation: floating 3.5s ease-in-out infinite;
            filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.15));
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        .frost-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.65);
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-brand-textBlue to-brand-dark flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Pattern Overlay Background -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>

    <!-- Main Content Box -->
    <div class="text-center max-w-lg px-8 py-12 frost-effect rounded-[2.5rem] relative z-10 mx-4 border-b-4 border-b-white/50 border-r-4 border-r-white/50">
        
        <!-- Logo with Floating Animation -->
        <div class="inline-block mb-6 relative">
            <div class="absolute -inset-4 bg-white rounded-full opacity-30 animate-pulse blur-2xl"></div>
            <!-- Menggunakan logo.png -->
            <img src="{{ asset('img/logo.png') }}" alt="Niki Frozen Logo" class="relative w-56 h-auto mx-auto floating-logo object-contain" 
                 onerror="this.outerHTML='<div class=\'relative bg-white w-40 h-40 flex items-center justify-center rounded-full border-4 border-brand-textBlue shadow-xl floating-logo mx-auto\'><i class=\'fa-solid fa-snowflake text-brand-textBlue text-7xl\'></i><span class=\'absolute bottom-4 text-brand-textBlue font-bold text-xs uppercase tracking-widest\'>Niki Frozen</span></div>'">
        </div>

        <h1 class="text-7xl font-black text-brand-textBlue mb-2 tracking-tighter drop-shadow-sm">
            404
        </h1>
        <h2 class="text-2xl font-bold text-brand-dark mb-4 drop-shadow-sm">
            Halaman Membeku! 🥶
        </h2>
        <p class="text-gray-800 mb-8 leading-relaxed font-semibold">
            Brrr... Sepertinya halaman yang Anda cari sudah hilang atau membeku di suatu tempat. Mari kita kembali ke tempat yang lebih hangat!
        </p>

        @php
            $homeUrl = url('/');
            if (auth()->check()) {
                $role = auth()->user()->role;
                if ($role === 'owner') $homeUrl = route('pages.owner.dashboard');
                elseif ($role === 'general_manager') $homeUrl = route('pages.general-manager.dashboard');
                elseif ($role === 'admin') $homeUrl = route('pages.admin.dashboard');
                elseif ($role === 'cashier') $homeUrl = route('pages.kasir.dashboard');
            }
        @endphp
        <div class="flex justify-center">
            <a href="{{ $homeUrl }}"
                class="group flex items-center justify-center gap-2 px-8 py-3.5 bg-brand-textBlue text-white rounded-2xl font-bold hover:bg-brand-textBlue/90 hover:shadow-lg hover:-translate-y-1 transition-all shadow-md">
                <span class="group-hover:-translate-y-0.5 transition-transform inline-block"><i class="fa-solid fa-home"></i></span>
                Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>