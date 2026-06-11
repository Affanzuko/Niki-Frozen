<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail {{ $branch->name }} - Niki Frozen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
    x-data="{ sidebarOpen: false, activeTab: 'transaksi' }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar -->
    <x-sidebar role="owner" active="dashboard" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('pages.owner.dashboard') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-brand-textBlue hover:border-brand-textBlue transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $branch->name }}</h1>
                        <p class="text-gray-500 mt-1 text-sm">{{ $branch->address }}</p>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-6 lg:px-8 pb-8">

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Pendapatan Total -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pendapatan Total</p>
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-money-bills text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <!-- Pendapatan Hari Ini -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hari Ini</p>
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-brand-textBlue">
                                <i class="fa-solid fa-calendar-day text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 tabular-nums">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <!-- Cash vs QRIS -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cash / QRIS</p>
                            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                                <i class="fa-solid fa-credit-card text-sm"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="font-bold text-gray-900 tabular-nums">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</span>
                            <span class="text-gray-400">/</span>
                            <span class="font-bold text-brand-textBlue tabular-nums">Rp {{ number_format($qrisRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <!-- Total Transaksi -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                            <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                                <i class="fa-solid fa-receipt text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 tabular-nums">{{ $totalTransactions }}</h3>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex gap-2 mb-6">
                    <button @click="activeTab = 'transaksi'" :class="activeTab === 'transaksi' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all">
                        <i class="fa-solid fa-receipt mr-1.5"></i> Transaksi
                    </button>
                    <button @click="activeTab = 'stok'" :class="activeTab === 'stok' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all">
                        <i class="fa-solid fa-box mr-1.5"></i> Stok Produk
                    </button>
                    <button @click="activeTab = 'barang-masuk'" :class="activeTab === 'barang-masuk' ? 'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 text-sm rounded-lg border transition-all">
                        <i class="fa-solid fa-truck mr-1.5"></i> Barang Masuk
                    </button>
                </div>

                <!-- Tab: Transaksi -->
                <div x-show="activeTab === 'transaksi'" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Kode Transaksi</th>
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4">Metode</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($transactions as $trx)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-gray-500 font-medium tabular-nums">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-gray-500 font-mono text-xs font-medium">{{ $trx->transaction_code }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-900 tabular-nums">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $trx->payment_method === 'cash' ? 'bg-green-50 text-green-700' : 'bg-purple-50 text-purple-700' }}">
                                                {{ strtoupper($trx->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($trx->status === 'success')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                            @elseif ($trx->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">Pending</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Gagal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                                            <p class="font-medium">Belum ada transaksi</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Stok -->
                <div x-show="activeTab === 'stok'" x-cloak class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 text-left">Produk</th>
                                    <th class="px-6 py-4">Stok</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($stocks as $stock)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-left font-bold text-gray-900">{{ $stock->product->name ?? '-' }}</td>
                                        <td class="px-6 py-4 font-bold tabular-nums {{ $stock->quantity < 20 ? 'text-red-600' : 'text-gray-900' }}">{{ $stock->quantity }}</td>
                                        <td class="px-6 py-4">
                                            @if ($stock->quantity < 10)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Kritis</span>
                                            @elseif ($stock->quantity < 20)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">Rendah</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                                            <p class="font-medium">Belum ada data stok</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Barang Masuk -->
                <div x-show="activeTab === 'barang-masuk'" x-cloak class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 text-left">Produk</th>
                                    <th class="px-6 py-4">Jumlah</th>
                                    <th class="px-6 py-4">Tanggal Kadaluarsa</th>
                                    <th class="px-6 py-4">Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($stockIncomings as $incoming)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-left font-bold text-gray-900">{{ $incoming->product->name ?? '-' }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-900 tabular-nums">{{ $incoming->quantity }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $isExpired = $incoming->expiry_date->isPast();
                                                $isNearExpiry = !$isExpired && $incoming->expiry_date->diffInDays(today()) <= 30;
                                            @endphp
                                            @if ($isExpired)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                                    {{ $incoming->expiry_date->format('d M Y') }}
                                                </span>
                                            @elseif ($isNearExpiry)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                    {{ $incoming->expiry_date->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">
                                                    {{ $incoming->expiry_date->format('d M Y') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 tabular-nums">{{ $incoming->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fa-solid fa-truck text-3xl mb-2 block"></i>
                                            <p class="font-medium">Belum ada data barang masuk</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>
