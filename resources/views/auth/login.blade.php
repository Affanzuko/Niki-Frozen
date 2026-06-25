<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk - Niki Frozen</title>

    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
    
    <!-- FontAwesome (Menyesuaikan dengan tampilan lain) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Alpine.js (Bawaan sistem Niki Frozen) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Configuration (Provided) -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-container": "#8393bc",
                        "secondary": "#526069",
                        "error": "#ba1a1a",
                        "surface-variant": "#e1e3e4",
                        "surface": "#f8f9fa",
                        "surface-container": "#edeeef",
                        "primary-container": "#1b2b4e",
                        "on-secondary": "#ffffff",
                        "surface-bright": "#f8f9fa",
                        "surface-container-low": "#f3f4f5",
                        "primary-fixed-dim": "#b6c6f1",
                        "surface-dim": "#d9dadb",
                        "surface-tint": "#4e5e84",
                        "on-error-container": "#93000a",
                        "surface-container-lowest": "#ffffff",
                        "background": "#f8f9fa",
                        "outline-variant": "#c5c6cf",
                        "secondary-container": "#d3e2ed",
                        "on-secondary-fixed": "#0f1d25",
                        "on-background": "#191c1d",
                        "tertiary-fixed": "#ffddb5",
                        "inverse-surface": "#2e3132",
                        "tertiary-container": "#402700",
                        "tertiary": "#251400",
                        "secondary-fixed": "#d6e5ef",
                        "on-tertiary-fixed-variant": "#5e4116",
                        "on-primary": "#ffffff",
                        "outline": "#75777f",
                        "surface-container-highest": "#e1e3e4",
                        "on-primary-fixed-variant": "#37466b",
                        "tertiary-fixed-dim": "#eabf89",
                        "on-error": "#ffffff",
                        "on-surface-variant": "#45464e",
                        "inverse-on-surface": "#f0f1f2",
                        "secondary-fixed-dim": "#bac9d3",
                        "on-tertiary-fixed": "#2a1800",
                        "on-secondary-container": "#56656e",
                        "inverse-primary": "#b6c6f1",
                        "on-tertiary": "#ffffff",
                        "primary": "#041638",
                        "on-surface": "#191c1d",
                        "surface-container-high": "#e7e8e9",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#081a3d",
                        "on-secondary-fixed-variant": "#3b4951",
                        "on-tertiary-container": "#b38d5b",
                        "primary-fixed": "#d9e2ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "md": "16px",
                        "touch-target": "44px",
                        "margin": "24px",
                        "xl": "32px",
                        "xs": "4px",
                        "lg": "24px",
                        "gutter": "16px",
                        "unit": "4px",
                        "sm": "8px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "display": ["Inter"],
                        "title-lg": ["Inter"],
                        "numeric-pos": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-md": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "display": ["30px", {"lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "title-lg": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "numeric-pos": ["22px", {"lineHeight": "28px", "fontWeight": "700"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom input border handling to match 1px default, 2px focus without layout shift */
        .input-group {
            position: relative;
            box-sizing: border-box;
        }
        .input-group::after {
            content: '';
            position: absolute;
            inset: 0;
            border: 1px solid theme('colors.outline-variant');
            border-radius: theme('borderRadius.lg');
            pointer-events: none;
            transition: border-width 0.1s, border-color 0.1s;
        }
        .input-group:focus-within::after {
            border-width: 2px;
            border-color: theme('colors.primary');
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen w-full font-body-md overflow-hidden">
    <!-- Split Screen Layout -->
    <main class="flex h-screen w-full">
        <!-- Left Side: Visual Branding (Hidden on mobile, visible on tablet landscape and up) -->
        <section class="hidden md:flex md:w-1/2 lg:w-[55%] relative bg-primary items-center justify-center overflow-hidden">
            <!-- Background Imagery with solid enterprise feel -->
            <div class="absolute inset-0 z-0">
                <img class="w-full h-full object-cover opacity-30 mix-blend-luminosity grayscale" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGZCH7Qw2rKFihPZ-1DWqigPdJbld_1T1HJcSDdZ_9h0ulO4c4mq4oMxc5HSEItbgT2mntjagCm-vBGfXeZYPgv9jo8AhYCfrjF8A6pIPJCk4TrlewDtjtlZo0h-V3m1wXGsxLSFaGv5dcWBZtEKjjZQ4p1abAzXsr3XIJVUehqeXZU1KLeHkBq0CJ7gV4nl5D2P59sG0FtLlZiFoZed1jFfs9fuL6898DVV9stIe-3Ud6mNW4a2DwxNO_mk-Dy5KljWX7v8R-LtU" alt="Warehouse Background" />
                <!-- Tonal gradient overlay to enforce Navy Blue brand color -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary to-primary-container opacity-80"></div>
            </div>
            <!-- Branding Content -->
            <div class="relative z-10 p-xl flex flex-col items-center text-center max-w-md">
                <!-- Decorative Icon representing supply chain/frozen -->
                <div class="w-20 h-20 mb-lg rounded-xl bg-surface/10 border border-surface/20 flex items-center justify-center backdrop-blur-sm shadow-sm">
                    <i class="fa-solid fa-snowflake text-[40px] text-on-primary"></i>
                </div>
                <h1 class="font-display text-display text-on-primary mb-sm">Niki Frozen</h1>
                <p class="font-headline-md text-headline-md text-primary-fixed-dim">Sistem ERP & Inventaris</p>
                <div class="mt-xl pt-xl border-t border-primary-fixed-dim/30 w-full flex justify-center gap-md">
                    <div class="flex items-center gap-sm text-on-primary font-label-md text-label-md">
                        <i class="fa-solid fa-shield-halved text-[16px]"></i>
                        Akses Aman
                    </div>
                    <div class="flex items-center gap-sm text-on-primary font-label-md text-label-md">
                        <i class="fa-solid fa-bolt text-[16px]"></i>
                        Kecepatan Tinggi
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Right Side: Login Form -->
        <section class="w-full md:w-1/2 lg:w-[45%] flex flex-col justify-center items-center bg-surface-bright relative p-margin">
            <!-- Mobile Header (Only visible on small screens) -->
            <div class="md:hidden flex flex-col items-center mb-xl w-full">
                <div class="w-16 h-16 mb-md rounded-xl bg-primary flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-snowflake text-[32px] text-on-primary"></i>
                </div>
                <h1 class="font-display text-display text-primary text-center">Niki Frozen</h1>
            </div>
            
            <!-- Login Card -->
            <div class="w-full max-w-[400px] bg-surface rounded-xl border border-outline-variant p-xl shadow-sm flex flex-col gap-lg z-10">
                <header class="flex flex-col gap-xs">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface">Masuk</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Masukkan kredensial Anda untuk mengakses sistem Kasir POS dan Inventaris.</p>
                </header>
                
                <!-- Error Notification Area (Shows only when errors exist) -->
                @if ($errors->any())
                <div class="bg-error-container border border-error/20 rounded-lg p-md flex items-start gap-sm">
                    <i class="fa-solid fa-circle-exclamation text-error text-[20px] shrink-0 mt-xs"></i>
                    <div class="flex flex-col">
                        <span class="font-label-md text-label-md text-on-error-container">Autentikasi Gagal</span>
                        <span class="font-body-md text-body-md text-on-error-container text-[13px]">Username atau kata sandi tidak valid. Silakan coba lagi atau hubungi IT.</span>
                    </div>
                </div>
                @endif
                
                <form action="#" method="POST" class="flex flex-col gap-md">
                    @csrf
                    
                    <!-- Username Field -->
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant ml-xs" for="username">ID Pegawai atau Username</label>
                        <div class="input-group bg-surface rounded-lg flex items-center h-touch-target overflow-hidden px-md gap-sm transition-colors hover:bg-surface-container-low">
                            <i class="fa-regular fa-circle-user text-outline text-lg"></i>
                            <input autocomplete="username" class="w-full h-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:ring-0" id="username" name="username" placeholder="username" type="text" required autofocus/>
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="flex flex-col gap-xs" x-data="{ show: false }">
                        <div class="flex justify-between items-center ml-xs pr-xs">
                            <label class="font-label-md text-label-md text-on-surface-variant" for="password">Kata Sandi</label>
                            <a class="font-label-md text-label-md text-primary hover:text-primary-fixed-dim transition-colors" href="#">Lupa?</a>
                        </div>
                        <div class="input-group bg-surface rounded-lg flex items-center h-touch-target overflow-hidden px-md gap-sm transition-colors hover:bg-surface-container-low">
                            <i class="fa-solid fa-lock text-outline"></i>
                            <input autocomplete="current-password" :type="show ? 'text' : 'password'" class="w-full h-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:ring-0" id="password" name="password" placeholder="••••••••" required/>
                            <button aria-label="Toggle password visibility" class="text-outline hover:text-on-surface flex items-center justify-center p-xs rounded focus:outline-none focus:ring-2 focus:ring-primary/50" type="button" @click="show = !show">
                                <i class="fa-regular fa-eye text-lg" x-show="!show"></i>
                                <i class="fa-regular fa-eye-slash text-lg" x-show="show" x-cloak></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="pt-sm flex flex-col gap-md">
                        <button class="w-full h-touch-target bg-primary text-on-primary rounded-lg font-label-md text-label-md flex justify-center items-center gap-sm hover:bg-on-primary-fixed transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" type="submit">
                            <i class="fa-solid fa-arrow-right-to-bracket text-lg"></i>
                            Masuk
                        </button>

                    </div>
                </form>
            </div>
            
            <!-- Footer subtle links -->
            <div class="absolute bottom-margin text-center w-full max-w-[400px]">
                <p class="font-label-md text-label-md text-outline">
                    &copy; 2024 Niki Frozen ERP. <a class="hover:text-primary transition-colors" href="#">Kebijakan IT</a> &bull; <a class="hover:text-primary transition-colors" href="#">Bantuan</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
