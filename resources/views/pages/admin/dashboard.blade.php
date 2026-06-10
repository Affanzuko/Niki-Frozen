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
        body { background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800 bg-[#f8fafc]" 
    x-data="{ 
        selectedDate: 'Hari Ini',
        sidebarOpen: false
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="dashboard" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
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
                    <div onclick="window.location='{{ route('keuangan.admin') }}'" class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
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
                    <div onclick="window.location='{{ route('penjualan.admin') }}'" class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
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
                    <div onclick="window.location='{{ route('stok.admin') }}'" class="group bg-white rounded-2xl p-6 border border-red-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-red-400 transition-all duration-300 relative overflow-hidden">
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
                        <button onclick="window.location='{{ route('penjualan.admin') }}'" class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-all shadow-sm hover:shadow-md active:scale-95 cursor-pointer">
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
    </main>
</body>
</html>
