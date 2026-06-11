<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan Admin - Niki Frozen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { dark: '#0f172a', blue: '#e0f2fe', textBlue: '#0284c7' }
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
    x-data="{ sidebarOpen: false }">

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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900 tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pendapatan Cash</p>
                    <h3 class="text-2xl font-bold text-green-600 tabular-nums">Rp {{ number_format($cashTotal, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pendapatan QRIS</p>
                    <h3 class="text-2xl font-bold text-brand-textBlue tabular-nums">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">Kode Transaksi</th>
                                <th class="px-6 py-4">Metode</th>
                                <th class="px-6 py-4">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium">
                            @forelse ($transactions as $trx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700 tabular-nums">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $trx->transaction_code }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $trx->payment_method === 'cash' ? 'bg-green-50 text-green-700' : 'bg-purple-50 text-purple-700' }}">
                                            {{ strtoupper($trx->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-green-600 font-bold tabular-nums">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fa-solid fa-chart-line text-3xl mb-2 block"></i>
                                        <p class="font-medium">Belum ada data keuangan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</body>
</html>
