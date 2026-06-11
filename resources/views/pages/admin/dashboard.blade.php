@extends('layouts.app')

@section('title', 'Dashboard Admin - Niki Frozen')

@section('body_data')
x-data="{
    selectedDate: 'Hari Ini',
    sidebarOpen: false
}"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

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
                    <button x-ref="dateBtn" x-init="flatpickr($refs.dateBtn, {
                        dateFormat: 'd M Y',
                        defaultDate: 'today',
                        onChange: function(selectedDates, dateStr) {
                            let today = flatpickr.formatDate(new Date(), 'd M Y');
                            selectedDate = (dateStr === today) ? 'Hari Ini' : dateStr;
                        }
                    })"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer focus:ring-2 focus:ring-brand-blue">
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
                    <div onclick="window.location='{{ route('pages.admin.keuangan') }}'"
                        class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Pendapatan Hari Ini</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-money-bills text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Pendapatan transaksi berhasil</p>
                        </div>
                    </div>

                    <!-- Kartu Transaksi -->
                    <div onclick="window.location='{{ route('pages.admin.penjualan') }}'"
                        class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Transaksi Hari Ini</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">{{ $todayTransactions }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Transaksi berhasil hari ini</p>
                        </div>
                    </div>

                    <!-- Kartu Stok -->
                    <div onclick="window.location='{{ route('stok.admin') }}'" class="group bg-white rounded-2xl p-6 border {{ $nearExpiryCount > 0 ? 'border-red-200' : 'border-gray-200' }} shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-red-400 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Peringatan Stok
                                Kadaluarsa</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-red-600 mb-1 tracking-tight tabular-nums">{{ $nearExpiryCount }} Barang</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $nearExpiryCount > 0 ? 'Butuh pengecekan segera' : 'Tidak ada peringatan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h2 class="text-base font-bold text-gray-900">Aktivitas Kasir Terbaru</h2>
                        <button onclick="window.location='{{ route('pages.admin.penjualan') }}'"
                            class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-all shadow-sm hover:shadow-md active:scale-95 cursor-pointer">
                            Lihat Semua Transaksi
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 w-40 text-center">Waktu</th>
                                    <th class="px-6 py-4">No. Referensi</th>
                                    <th class="px-6 py-4 text-center">Total (Rp)</th>
                                    <th class="px-6 py-4 text-center">Metode</th>
                                    <th class="px-6 py-4 w-32 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($recentTransactions as $trx)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-gray-500 font-medium text-center tabular-nums">{{ $trx->created_at->format('H:i:s') }}</td>
                                        <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">{{ $trx->transaction_code }}</td>
                                        <td class="px-6 py-4 text-center font-bold text-gray-900 tabular-nums">{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $trx->payment_method === 'cash' ? 'bg-green-50 text-green-700' : 'bg-purple-50 text-purple-700' }}">
                                                {{ strtoupper($trx->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($trx->status === 'success')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                            @elseif ($trx->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">Pending</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Batal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                                            <p class="font-medium">Belum ada transaksi hari ini</p>
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
@endsection
