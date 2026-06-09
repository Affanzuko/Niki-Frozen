<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Niki Frozen</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Flatpickr (Kalender) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
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
        
        /* Custom Scrollbar untuk kesan lebih premium */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800 bg-[#f8fafc]" 
    x-data="{ 
        activeTab: 'dashboard', 
        selectedDate: 'Hari Ini',
        sidebarOpen: false,
        
        // State Filter
        penjualanFilter: 'Semua',
        keuanganFilter: 'Hari',
        stokFilter: 'Semua Barang',

        // State Detail Panel (Penjualan)
        showTransactionDetail: false,
        activeRowId: null,

        // State Dropdown (Stok)
        activeDropdown: null,
        
        toggleDropdown(id) {
            this.activeDropdown = this.activeDropdown === id ? null : id;
        },
        openDetail(id) {
            this.showTransactionDetail = true;
            this.activeRowId = id;
        }
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed lg:static inset-y-0 left-0 w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 z-30 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none">
        <div>
            <!-- Logo -->
            <div class="px-6 pt-8 pb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-dark rounded-full flex items-center justify-center text-white shrink-0 shadow-sm">
                        <i class="fa-solid fa-snowflake text-lg"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-brand-dark tracking-tight leading-none">Niki Frozen</div>
                        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mt-1">Admin Cabang</div>
                    </div>
                </div>
                <!-- Close Button Mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="px-3 mt-2 space-y-1">
                <a href="#" @click.prevent="activeTab = 'dashboard'; sidebarOpen = false" :class="activeTab === 'dashboard' ? 'bg-brand-blue text-brand-textBlue font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all">
                    <i class="fa-solid fa-border-all w-5 text-center text-lg transition-transform" :class="activeTab === 'dashboard' ? 'scale-110' : ''"></i>
                    Dashboard
                </a>
                <a href="#" @click.prevent="activeTab = 'stok'; sidebarOpen = false" :class="activeTab === 'stok' ? 'bg-brand-blue text-brand-textBlue font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-lg transition-transform" :class="activeTab === 'stok' ? 'scale-110' : ''"></i>
                    Kelola Stok
                </a>
                <a href="#" @click.prevent="activeTab = 'penjualan'; sidebarOpen = false" :class="activeTab === 'penjualan' ? 'bg-brand-blue text-brand-textBlue font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all">
                    <i class="fa-solid fa-cart-shopping w-5 text-center text-lg transition-transform" :class="activeTab === 'penjualan' ? 'scale-110' : ''"></i>
                    Penjualan
                </a>
                <a href="#" @click.prevent="activeTab = 'keuangan'; sidebarOpen = false" :class="activeTab === 'keuangan' ? 'bg-brand-blue text-brand-textBlue font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all">
                    <i class="fa-solid fa-chart-line w-5 text-center text-lg transition-transform" :class="activeTab === 'keuangan' ? 'scale-110' : ''"></i>
                    Keuangan
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <div class="p-4 mb-2">
            <button class="w-full flex items-center justify-start gap-3 px-4 py-3 bg-brand-dark hover:bg-gray-800 text-white rounded-lg font-medium transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        
        <!-- ==================== TAB: DASHBOARD ==================== -->
        <div x-show="activeTab === 'dashboard'" x-cloak x-transition.opacity.duration.300ms class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                        <p class="text-gray-500 mt-1 text-sm">Dashboard Admin</p>
                    </div>
                </div>
                <div>
                    <button x-ref="dateBtn" x-init="flatpickr($refs.dateBtn, { dateFormat: 'd M Y', defaultDate: 'today', onChange: function(selectedDates, dateStr) { let today = flatpickr.formatDate(new Date(), 'd M Y'); selectedDate = (dateStr === today) ? 'Hari Ini' : dateStr; } })" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer focus:ring-2 focus:ring-brand-blue">
                        <i class="fa-regular fa-calendar text-brand-textBlue"></i>
                        <span x-text="selectedDate">Hari Ini</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-6 lg:px-8 pb-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Kartu Pendapatan -->
                    <div @click="activeTab = 'keuangan'" class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Pendapatan Hari Ini</p>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-money-bills text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">Rp 24.850.000</h3>
                            <p class="text-sm font-medium text-green-600 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up text-xs"></i> +12.5% <span class="text-gray-500 font-normal">dari kemarin</span></p>
                        </div>
                    </div>

                    <!-- Kartu Transaksi -->
                    <div @click="activeTab = 'penjualan'" class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Transaksi Hari Ini</p>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">184</h3>
                            <p class="text-sm text-gray-500 font-medium">Rata-rata keranjang Rp 135.054</p>
                        </div>
                    </div>

                    <!-- Kartu Stok -->
                    <div @click="activeTab = 'stok'; stokFilter = 'Status Kedaluwarsa'" class="group bg-white rounded-2xl p-6 border border-red-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-red-400 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Peringatan Stok Kadaluarsa</p>
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-red-600 mb-1 tracking-tight tabular-nums">8 Barang</h3>
                            <p class="text-sm text-gray-500 font-medium">Butuh pengecekan segera</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h2 class="text-base font-bold text-gray-900">Aktivitas Kasir Terbaru</h2>
                        <button @click="activeTab = 'penjualan'" class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-all shadow-sm hover:shadow-md active:scale-95 cursor-pointer">
                            Lihat Semua Transaksi
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 w-40 text-center">Waktu</th>
                                    <th class="px-6 py-4">Kasir</th>
                                    <th class="px-6 py-4">No. Referensi</th>
                                    <th class="px-6 py-4 text-center">Total (Rp)</th>
                                    <th class="px-6 py-4 w-32 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center tabular-nums">10:45:22</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-brand-textBlue flex items-center justify-center text-xs">BS</div> Budi Santoso
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">POS-8921-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900 tabular-nums">450.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center tabular-nums">10:42:15</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center text-xs">SA</div> Siti Aminah
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">POS-8920-A</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900 tabular-nums">1.250.500</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium text-center tabular-nums">10:31:50</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs">RM</div> Rina Melati
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">POS-8918-B</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900 tabular-nums">890.000</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Batal</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: KELOLA STOK ==================== -->
        <div x-show="activeTab === 'stok'" x-cloak x-transition.opacity.duration.300ms class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 shrink-0 flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                    <p class="text-gray-500 mt-1 text-sm">Kelola Stok</p>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-6 lg:px-8 pb-8 flex-1 flex flex-col min-h-0">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2 mb-4 items-center">
                    <button @click="stokFilter = 'Semua Barang'" :class="stokFilter === 'Semua Barang' ? 'bg-brand-blue text-brand-textBlue font-semibold border border-brand-blue ring-1 ring-brand-blue' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'" class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Semua Barang</button>
                    
                    <button @click="stokFilter = 'Status Kedaluwarsa'" :class="stokFilter === 'Status Kedaluwarsa' ? 'bg-red-50 text-red-700 font-semibold border border-red-200 ring-1 ring-red-200' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'" class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Status Kedaluwarsa</button>
                    
                    <div class="flex-1"></div>
                    
                    <button class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Input Barang Masuk
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                    <th class="px-6 py-4 text-left">Product</th>
                                    <th class="px-6 py-4">Barcode</th>
                                    <th class="px-6 py-4">Stock Level</th>
                                    <th class="px-6 py-4">Expiry Date</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm font-medium">
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                            <img src="https://images.unsplash.com/photo-1603048297172-c92544798d5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">Premium Short Ribs 500g</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 tabular-nums">8991234567890</td>
                                    <td class="px-6 py-4 text-gray-900 tabular-nums">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>45 / 50</span>
                                            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full w-[90%]"></div></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">12 Oct 2024</span>
                                    </td>
                                    <td class="px-6 py-4 text-right relative">
                                        <button @click.stop="toggleDropdown(1)" class="w-8 h-8 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div x-show="activeDropdown === 1" @click.away="activeDropdown = null" x-cloak x-transition class="absolute right-6 top-10 w-40 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-1 text-left">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-pen-to-square w-4 mr-1 text-gray-400"></i> Edit Detail</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-regular fa-trash-can w-4 mr-1"></i> Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Row 2 (Kedaluwarsa) -->
                                <tr class="hover:bg-red-50/30 transition-colors group" :class="stokFilter === 'Status Kedaluwarsa' ? 'bg-red-50/10' : ''">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                            <img src="https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">Norwegian Salmon Fillet 200g</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 tabular-nums">8990987654321</td>
                                    <td class="px-6 py-4 text-gray-900 tabular-nums">
                                        <div class="flex items-center justify-center gap-2">
                                            <span>45 / 50</span>
                                            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full w-[90%]"></div></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> 26 Sep 2024
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right relative">
                                        <button @click.stop="toggleDropdown(2)" class="w-8 h-8 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div x-show="activeDropdown === 2" @click.away="activeDropdown = null" x-cloak x-transition class="absolute right-6 top-10 w-40 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-1 text-left">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-pen-to-square w-4 mr-1 text-gray-400"></i> Edit Detail</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-regular fa-trash-can w-4 mr-1"></i> Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Footer Pagination -->
                    <div class="px-6 py-3 border-t border-gray-200 flex justify-between items-center bg-gray-50/50">
                        <span class="text-xs font-medium text-gray-500">Showing 1-2 of 42 items</span>
                        <div class="flex gap-1">
                            <button class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition-all shadow-sm active:scale-95"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                            <button class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all shadow-sm active:scale-95"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: PENJUALAN ==================== -->
        <div x-show="activeTab === 'penjualan'" x-cloak x-transition.opacity.duration.300ms class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between sm:items-center shrink-0 gap-4">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                        <p class="text-gray-500 mt-1 text-sm">Riwayat Penjualan</p>
                    </div>
                </div>
                <div>
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-brand-textBlue transition-colors"></i>
                        <input type="text" placeholder="Cari No. Referensi / Nama Kasir" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-textBlue w-full sm:w-[300px] transition-all">
                    </div>
                </div>
            </header>

            <!-- Content Area (Split layout) -->
            <div class="px-6 lg:px-8 pb-8 flex flex-col lg:flex-row gap-6 flex-1 min-h-0">
                <!-- Left: Table -->
                <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300">
                    <!-- Filters -->
                    <div class="p-4 flex gap-2 border-b border-gray-100 bg-white overflow-x-auto no-scrollbar">
                        <button @click="penjualanFilter = 'Semua'" :class="penjualanFilter === 'Semua' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Semua</button>
                        <button @click="penjualanFilter = 'Berhasil'" :class="penjualanFilter === 'Berhasil' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Berhasil</button>
                        <button @click="penjualanFilter = 'Batal'" :class="penjualanFilter === 'Batal' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Dibatalkan/Refund</button>
                    </div>
                    <!-- Table -->
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50/80 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">No. Referensi</th>
                                    <th class="px-6 py-4">Total Belanja</th>
                                    <th class="px-6 py-4">Metode</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr @click="openDetail(1)" :class="activeRowId === 1 ? 'bg-blue-50/50' : 'hover:bg-gray-50'" class="transition-colors cursor-pointer group">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Hari ini, 14:32</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">POS-8921-A</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 tabular-nums">Rp 135.975</td>
                                    <td class="px-6 py-4 text-gray-500">QRIS</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                    </td>
                                </tr>
                                <tr @click="openDetail(2)" :class="activeRowId === 2 ? 'bg-blue-50/50' : 'hover:bg-gray-50'" class="transition-colors cursor-pointer">
                                    <td class="px-6 py-4 text-gray-500 font-medium">Hari ini, 13:15</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">POS-8920-B</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 tabular-nums">Rp 450.000</td>
                                    <td class="px-6 py-4 text-gray-500">Tunai</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Detail Panel -->
                <div x-show="showTransactionDetail" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-8 opacity-0" x-transition:enter-end="translate-x-0 opacity-100" class="w-full lg:w-[380px] bg-white rounded-xl border border-gray-200 shadow-xl flex flex-col shrink-0 lg:static absolute inset-y-8 right-8 z-20">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-xl">
                        <h3 class="font-bold text-gray-900">Detail Transaksi</h3>
                        <button @click="showTransactionDetail = false; activeRowId = null" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 flex-1 overflow-y-auto">
                        <div class="text-center mb-8 bg-gray-50 rounded-xl py-4 border border-gray-100">
                            <h2 class="text-2xl font-bold text-brand-dark font-mono tracking-tight">POS-8921-A</h2>
                            <p class="text-xs text-gray-500 mt-1 font-medium"><i class="fa-regular fa-clock mr-1"></i> 14 Nov 2023 • 14:32 &nbsp;|&nbsp; <i class="fa-regular fa-user mr-1"></i> Kasir: Budi</p>
                        </div>

                        <!-- Items -->
                        <div class="space-y-5 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm text-gray-900 leading-tight">Fiesta Chicken Nugget 500g</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">2 x Rp 45.000</p>
                                </div>
                                <p class="font-bold text-sm text-gray-900 tabular-nums">Rp 90.000</p>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm text-gray-900 leading-tight">Champ Sosis Sapi 375g</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">1 x Rp 32.500</p>
                                </div>
                                <p class="font-bold text-sm text-gray-900 tabular-nums">Rp 32.500</p>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-300 pt-5 mb-2">
                            <div class="flex justify-between text-sm mb-3">
                                <span class="text-gray-500 font-medium">Subtotal</span>
                                <span class="text-gray-900 font-semibold tabular-nums">Rp 122.500</span>
                            </div>
                            <div class="flex justify-between text-sm mb-5">
                                <span class="text-gray-500 font-medium">Pajak (11%)</span>
                                <span class="text-gray-900 font-semibold tabular-nums">Rp 13.475</span>
                            </div>
                            <div class="flex justify-between items-center mb-6 bg-blue-50 p-3 rounded-lg border border-brand-blue">
                                <span class="font-bold text-lg text-brand-textBlue">Total</span>
                                <span class="font-bold text-2xl text-brand-textBlue tabular-nums">Rp 135.975</span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-500 font-medium">Metode Pembayaran</span>
                                <span class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded border border-gray-200">QRIS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="p-6 border-t border-gray-100 flex flex-col gap-3 bg-gray-50/50 rounded-b-xl">
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-brand-dark rounded-lg font-bold transition-all shadow-sm active:scale-95">
                            <i class="fa-solid fa-receipt text-gray-500"></i>
                            Cetak Ulang Struk
                        </button>
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#b91c1c] hover:bg-red-800 text-white rounded-lg font-bold transition-all shadow-sm active:scale-95">
                            <i class="fa-regular fa-circle-xmark"></i>
                            Batalkan Transaksi / Refund
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB: KEUANGAN ==================== -->
        <div x-show="activeTab === 'keuangan'" x-cloak x-transition.opacity.duration.300ms class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 shrink-0 flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                    <p class="text-gray-500 mt-1 text-sm">Laporan Keuangan</p>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-6 lg:px-8 pb-8 flex-1 flex flex-col min-h-0">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <template x-for="filter in ['Hari', 'Minggu', 'Bulan', 'Tahun']" :key="filter">
                        <button @click="keuanganFilter = filter" 
                            :class="keuanganFilter === filter ? 'bg-brand-blue text-brand-textBlue font-semibold border border-brand-blue ring-1 ring-brand-blue' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'" 
                            class="px-6 py-2 text-sm rounded-lg shadow-sm transition-all" x-text="filter"></button>
                    </template>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
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
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500 tabular-nums">Rp 4.000.000</td>
                                    <td class="px-6 py-4 text-gray-900 font-bold tabular-nums">Rp. 30.000.000</td>
                                    <td class="px-6 py-4 text-gray-400">-</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">Selasa, 6 Juni 2026</td>
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums">Rp. 7.000.000</td>
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums">Rp. 27.000.000</td>
                                    <td class="px-6 py-4 text-red-500 tabular-nums">Rp 4.000.000</td>
                                    <td class="px-6 py-4 text-gray-900 font-bold tabular-nums">Rp. 60.000.000</td>
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums bg-green-50/50"><i class="fa-solid fa-arrow-trend-up text-[10px] mr-1"></i> Rp. 30.000.000</td>
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
