<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Admin - Niki Frozen</title>
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
        penjualanFilter: 'Semua',
        showTransactionDetail: false,
        activeRowId: null,
        sidebarOpen: false,
        openDetail(id) {
            this.showTransactionDetail = true;
            this.activeRowId = id;
        }
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="penjualan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">
        
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

    </main>
</body>
</html>
