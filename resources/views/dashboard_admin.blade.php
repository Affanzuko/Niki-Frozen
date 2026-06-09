<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Niki Frozen</title>
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#111827',
                            blue: '#e0f2fe',
                            textBlue: '#0284c7',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<!-- Gunakan Alpine.js untuk mengatur state tab aktif -->
<body class="flex h-screen overflow-hidden text-gray-800" x-data="{ activeTab: 'dashboard' }">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#f8fafc] border-r border-gray-200 flex flex-col justify-between shrink-0">
        <div>
            <!-- Logo -->
            <div class="px-6 pt-8 pb-6">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-8 h-8 bg-brand-dark rounded-full flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-snowflake text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-brand-dark tracking-tight">Niki Frozen</span>
                </div>
                <div class="text-sm font-medium text-gray-600">Admin Cabang</div>
            </div>

            <!-- Navigation -->
            <nav class="px-4 mt-2 space-y-1">
                <a href="#" @click.prevent="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'bg-brand-blue text-brand-textBlue font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-border-all w-5 text-center text-lg"></i>
                    Dashboard
                </a>
                <a href="#" @click.prevent="activeTab = 'stok'" :class="activeTab === 'stok' ? 'bg-brand-blue text-brand-textBlue font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-snowflake w-5 text-center text-lg"></i>
                    Kelola Stok
                </a>
                <a href="#" @click.prevent="activeTab = 'penjualan'" :class="activeTab === 'penjualan' ? 'bg-brand-blue text-brand-textBlue font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-cart-shopping w-5 text-center text-lg"></i>
                    Penjualan
                </a>
                <a href="#" @click.prevent="activeTab = 'keuangan'" :class="activeTab === 'keuangan' ? 'bg-brand-blue text-brand-textBlue font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-chart-simple w-5 text-center text-lg"></i>
                    Keuangan
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <div class="p-4 mb-2">
            <button class="w-full flex items-center justify-start gap-3 px-4 py-3 bg-brand-dark hover:bg-gray-800 text-white rounded-lg font-medium transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- ==================== TAB: DASHBOARD ==================== -->
        <div x-show="activeTab === 'dashboard'" x-cloak class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Header -->
            <header class="px-8 py-8 flex justify-between items-start shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                    <p class="text-gray-500 mt-1">Dashboard Admin</p>
                </div>
                <div>
                    <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-regular fa-calendar text-gray-500"></i>
                        Hari Ini
                        <i class="fa-solid fa-chevron-down text-xs ml-1 text-gray-400"></i>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-8 pb-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 border border-brand-dark shadow-sm flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-4">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">PENDAPATAN HARI INI</p>
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-indigo-800">
                                <i class="fa-solid fa-money-bills text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-1">Rp 24.850.000</h3>
                            <p class="text-sm font-medium text-gray-900">+12.5% dari kemarin</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 border border-brand-dark shadow-sm flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-4">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">TRANSAKSI HARI INI</p>
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-indigo-800">
                                <i class="fa-solid fa-cart-shopping text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-1">184</h3>
                            <p class="text-sm font-medium text-gray-900">Rata-rata keranjang Rp. 135.054</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 border border-red-500 shadow-sm flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-4">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">PERINGATAN STOK KADALUARSA (< 3 HARI)</p>
                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-red-600 mb-1">8 Barang</h3>
                            <p class="text-sm font-medium text-gray-900">+12.5% dari kemarin</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-[#f8fafc]">
                        <h2 class="text-base font-bold text-gray-900">Aktivitas Kasir Terbaru</h2>
                        <button class="px-4 py-2 bg-brand-dark text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                            Lihat Semua Transaksi
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 w-40 text-center">Waktu</th>
                                    <th class="px-6 py-4">Kasir</th>
                                    <th class="px-6 py-4">No. Referensi</th>
                                    <th class="px-6 py-4 text-center">Total (Rp)</th>
                                    <th class="px-6 py-4 w-32 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center">10:45:22</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Budi Santoso</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8921-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">450.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center">10:42:15</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Siti Aminah</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8920-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">1.250.500</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center">10:38:05</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Budi Santoso</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8919-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">75.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center">10:31:50</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rina Melati</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8918-B</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">890.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Batal</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center">10:25:12</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Budi Santoso</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8917-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">320.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Selesai</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: KELOLA STOK ==================== -->
        <div x-show="activeTab === 'stok'" x-cloak class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-8 py-6 shrink-0">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                <p class="text-gray-500 mt-1">Kelola Stok</p>
            </header>

            <!-- Content Area -->
            <div class="px-8 pb-8 flex-1 flex flex-col min-h-0">
                <!-- Filters -->
                <div class="flex gap-2 mb-6 items-center">
                    <button class="px-5 py-2.5 bg-brand-blue text-brand-textBlue text-sm font-semibold rounded-lg shadow-sm border border-brand-blue">Semua Barang</button>
                    <button class="px-5 py-2.5 bg-white border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Status Kedaluwarsa</button>
                    <button class="px-5 py-2.5 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm ml-2 flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Input Barang Masuk
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                    <th class="px-6 py-4 text-left">Product</th>
                                    <th class="px-6 py-4">Barcode</th>
                                    <th class="px-6 py-4">Stock Level</th>
                                    <th class="px-6 py-4">Expiry Date</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm font-medium">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                            <img src="https://images.unsplash.com/photo-1603048297172-c92544798d5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-gray-900 font-bold">Premium Short Ribs 500g</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">8991234567890</td>
                                    <td class="px-6 py-4 text-gray-900">45 / 50</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">12 Oct 2024</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 cursor-pointer hover:text-gray-600">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                            <img src="https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-gray-900 font-bold">Norwegian Salmon Fillet 200g</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">8990987654321</td>
                                    <td class="px-6 py-4 text-gray-900">45 / 50</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-xs font-bold bg-red-100 text-red-700"><i class="fa-solid fa-triangle-exclamation text-[10px]"></i> 26 Sep 2024</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 cursor-pointer hover:text-gray-600">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                                            <i class="fa-regular fa-image"></i>
                                        </div>
                                        <span class="text-gray-900 font-bold">Chicken Breast Fillet 1kg</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">8994445556667</td>
                                    <td class="px-6 py-4 text-gray-900">45 / 50</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">05 Nov 2024</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 cursor-pointer hover:text-gray-600">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Footer Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center bg-[#f8fafc]">
                        <span class="text-xs font-medium text-gray-500">Showing 1-3 of 42 items</span>
                        <div class="flex gap-1">
                            <button class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors shadow-sm"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition-colors shadow-sm"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: PENJUALAN ==================== -->
        <div x-show="activeTab === 'penjualan'" x-cloak class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-8 py-6 flex justify-between items-center shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                    <p class="text-gray-500 mt-1">Riwayat Penjualan</p>
                </div>
                <div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Cari No. Referensi / Nama Kasir" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-brand-textBlue w-[300px]">
                    </div>
                </div>
            </header>

            <!-- Content Area (Split layout) -->
            <div class="px-8 pb-8 flex gap-6 flex-1 min-h-0">
                <!-- Left: Table -->
                <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Filters -->
                    <div class="p-4 flex gap-2 border-b border-gray-100 bg-[#f8fafc]">
                        <button class="px-5 py-2 bg-brand-blue text-brand-textBlue text-sm font-semibold rounded-lg shadow-sm">Semua</button>
                        <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Berhasil</button>
                        <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Dibatalkan/Refund</button>
                    </div>
                    <!-- Table -->
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-white text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">No. Referensi</th>
                                    <th class="px-6 py-4">Total Belanja</th>
                                    <th class="px-6 py-4">Metode</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Hari ini, 14:32</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8921-A</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rp 135.975</td>
                                    <td class="px-6 py-4 text-gray-500">QRIS</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Hari ini, 13:15</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8920-B</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rp 450.000</td>
                                    <td class="px-6 py-4 text-gray-500">Tunai</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Hari ini, 11:45</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8919-C</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rp 85.500</td>
                                    <td class="px-6 py-4 text-gray-500">Tunai</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Batal</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Kemarin, 16:20</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8918-D</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rp 1.250.000</td>
                                    <td class="px-6 py-4 text-gray-500">Kartu Kredit</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Kemarin, 09:30</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">POS-8917-E</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">Rp 215.000</td>
                                    <td class="px-6 py-4 text-gray-500">QRIS</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Detail Panel -->
                <div class="w-[380px] bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col shrink-0">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-[#f8fafc] rounded-t-xl">
                        <h3 class="font-bold text-gray-900">Detail Transaksi</h3>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
                    </div>
                    
                    <div class="p-6 flex-1 overflow-y-auto">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-900">POS-8921-A</h2>
                            <p class="text-sm text-gray-500 mt-1 font-medium">14 Nov 2023 • 14:32 • Kasir: Budi</p>
                        </div>

                        <!-- Items -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm text-gray-900">Fiesta Chicken Nugget 500g</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">2 x Rp 45.000</p>
                                </div>
                                <p class="font-bold text-sm text-gray-900">Rp 90.000</p>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm text-gray-900">Champ Sosis Sapi 375g</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">1 x Rp 32.500</p>
                                </div>
                                <p class="font-bold text-sm text-gray-900">Rp 32.500</p>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-300 pt-5 mb-2">
                            <div class="flex justify-between text-sm mb-3">
                                <span class="text-gray-500 font-medium">Subtotal</span>
                                <span class="text-gray-500 font-medium">Rp 122.500</span>
                            </div>
                            <div class="flex justify-between text-sm mb-5">
                                <span class="text-gray-500 font-medium">Pajak (11%)</span>
                                <span class="text-gray-500 font-medium">Rp 13.475</span>
                            </div>
                            <div class="flex justify-between items-center mb-5">
                                <span class="font-bold text-xl text-gray-900">Total</span>
                                <span class="font-bold text-2xl text-gray-900">Rp 135.975</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Metode Pembayaran</span>
                                <span class="font-bold text-gray-900">QRIS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="p-6 border-t border-gray-100 flex flex-col gap-3">
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-brand-dark rounded-lg font-bold transition-colors shadow-sm">
                            <i class="fa-solid fa-receipt"></i>
                            Cetak Ulang Struk
                        </button>
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#b91c1c] hover:bg-red-800 text-white rounded-lg font-bold transition-colors shadow-sm">
                            <i class="fa-regular fa-circle-xmark"></i>
                            Batalkan Transaksi / Refund
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: KEUANGAN ==================== -->
        <div x-show="activeTab === 'keuangan'" x-cloak class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-8 py-8 shrink-0">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                <p class="text-gray-500 mt-1">Laporan Keuangan</p>
            </header>

            <!-- Content Area -->
            <div class="px-8 pb-8 flex-1 flex flex-col min-h-0">
                <!-- Filters -->
                <div class="flex gap-2 mb-6">
                    <button class="px-6 py-2 bg-brand-blue text-brand-textBlue text-sm font-semibold rounded-lg shadow-sm">Hari</button>
                    <button class="px-6 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Minggu</button>
                    <button class="px-6 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Bulan</button>
                    <button class="px-6 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Tahun</button>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Cash</th>
                                    <th class="px-6 py-4">QRIS</th>
                                    <th class="px-6 py-4">Pengeluaran</th>
                                    <th class="px-6 py-4">Saldo Akhir</th>
                                    <th class="px-6 py-4">Profit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm font-medium">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">Senin, 5 Juni 2026</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500">Rp 4.000.000</td>
                                    <td class="px-6 py-4 text-gray-900">Rp. 30.000.000</td>
                                    <td class="px-6 py-4 text-gray-500">-</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">Selasa, 6 Juni 2026</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500">Rp 4.000.000</td>
                                    <td class="px-6 py-4 text-gray-900">Rp. 60.000.000</td>
                                    <td class="px-6 py-4 text-green-500">+ Rp. 30.000.000</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">Rabu, 7 Juni 2026</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500">Rp 4.000.000</td>
                                    <td class="px-6 py-4 text-gray-900">Rp. 90.000.000</td>
                                    <td class="px-6 py-4 text-green-500">+ Rp. 30.000.000</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">Kamis, 8 Juni 2026</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-500">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500">Rp 34.000.000</td>
                                    <td class="px-6 py-4 text-gray-900">Rp. 60.000.000</td>
                                    <td class="px-6 py-4 text-red-500">- Rp. 30.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
