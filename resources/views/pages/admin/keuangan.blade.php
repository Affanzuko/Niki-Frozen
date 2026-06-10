<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan Admin - Niki Frozen</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        keuanganFilter: 'Hari',
        sidebarOpen: false
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="keuangan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">
        
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

    </main>
</body>
</html>
