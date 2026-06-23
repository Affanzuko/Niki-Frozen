@extends('layouts.app')

@section('title', 'Dashboard Owner - Niki Frozen')

@section('body_data')
    x-data="{ sidebarOpen: false }"
@endsection

@section('content')
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
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen</h1>
                        <p class="text-gray-500 mt-1 text-sm">Dashboard Owner — Monitoring Semua Cabang</p>
                    </div>
                </div>
                <div x-data="{
                    selectedDate: '{{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M Y') }}'
                }">
                    <button x-ref="dateBtn" x-init="flatpickr($refs.dateBtn, {
                        dateFormat: 'Y-m-d',
                        defaultDate: '{{ $dateObj->format('Y-m-d') }}',
                        onChange: function(selectedDates, dateStr) {
                            if (dateStr) {
                                window.location.href = '?tanggal=' + dateStr;
                            }
                        }
                    })"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer focus:ring-2 focus:ring-brand-blue">
                        <i class="fa-regular fa-calendar text-brand-textBlue"></i>
                        <span x-text="selectedDate">{{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M Y') }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-6 lg:px-8 pb-8">

                <!-- Global Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @php
                        $globalTodayRevenue = collect($branchSummaries)->sum('today_revenue');
                        $globalTotalRevenue = collect($branchSummaries)->sum('total_revenue');
                        $globalTodayPengeluaran = collect($branchSummaries)->sum('today_pengeluaran');
                        $globalTodayTx = collect($branchSummaries)->sum('today_transactions');
                        $globalTotalTx = collect($branchSummaries)->sum('total_transactions');
                        $globalLowStock = collect($branchSummaries)->sum('low_stock_count');
                        $globalTotalStock = collect($branchSummaries)->sum('total_stock');
                    @endphp

                    <!-- Total Pendapatan Hari Ini -->
                    <a href="{{ route('pages.owner.keuangan') }}"
                        class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden block">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Total Pendapatan
                                {{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M') }}</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-money-bills text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">Rp
                                {{ number_format($globalTodayRevenue, 0, ',', '.') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Dari {{ count($branchSummaries) }} cabang</p>
                        </div>
                    </a>

                    <!-- Total Pengeluaran Hari Ini -->
                    <a href="{{ route('pages.owner.keuangan') }}" class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-red-500 transition-all duration-300 relative overflow-hidden block">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Total Pengeluaran
                                {{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M') }}</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">Rp
                                {{ number_format($globalTodayPengeluaran, 0, ',', '.') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Estimasi pengeluaran dari seluruh cabang</p>
                        </div>
                    </a>

                    <!-- Total Stok Semua Cabang -->
                    <a href="{{ route('pages.owner.stok') }}"
                        class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-brand-textBlue transition-all duration-300 relative overflow-hidden block">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-full opacity-50 group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Total Stok Semua Cabang</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue group-hover:bg-brand-textBlue group-hover:text-white transition-colors">
                                <i class="fa-solid fa-boxes-stacked text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">
                                {{ number_format($globalTotalStock, 0, ',', '.') }} Unit</h3>
                            <p class="text-sm text-gray-500 font-medium">Dari {{ count($branchSummaries) }} cabang</p>
                        </div>
                    </a>
                </div>

                <!-- Per-Branch Cards -->
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-store text-brand-textBlue"></i>
                    Performa Per Cabang
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($branchSummaries as $summary)
                        <a href="{{ route('pages.owner.detail-cabang', $summary['branch']->id) }}" 
                           class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-brand-textBlue transition-all duration-300 overflow-hidden block">
                            
                            <!-- Branch Header -->
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-store text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-sm leading-tight">{{ $summary['branch']->name }}</h3>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $summary['branch']->address }}</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-brand-textBlue group-hover:translate-x-1 transition-all"></i>
                                </div>
                            </div>

                            <!-- Stats Grid -->
                            <div class="px-6 pb-4 grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pendapatan
                                        {{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M') }}</p>
                                    <p class="text-sm font-bold text-gray-900 tabular-nums">Rp {{ number_format($summary['today_revenue'], 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Transaksi
                                        {{ $dateObj->isToday() ? 'Hari Ini' : $dateObj->translatedFormat('d M') }}</p>
                                    <p class="text-sm font-bold text-gray-900 tabular-nums">{{ $summary['today_transactions'] }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Produk</p>
                                    <p class="text-sm font-bold text-gray-900 tabular-nums">{{ $summary['total_products'] }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Stok</p>
                                    <p class="text-sm font-bold text-gray-900 tabular-nums">{{ number_format($summary['total_stock']) }}</p>
                                </div>
                            </div>

                            <!-- Footer Alerts -->
                            <div class="px-6 pb-5 flex items-center gap-2">
                                @if ($summary['low_stock_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-200">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $summary['low_stock_count'] }} stok rendah
                                    </span>
                                @endif
                                @if ($summary['near_expiry_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $summary['near_expiry_count'] }} mendekati kadaluarsa
                                    </span>
                                @endif
                                @if ($summary['low_stock_count'] == 0 && $summary['near_expiry_count'] == 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-200">
                                        <i class="fa-solid fa-check-circle"></i>
                                        Semua baik
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- All Branches Comparison Table -->
                <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-white">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar text-brand-textBlue"></i>
                            Perbandingan Pendapatan Cabang - {{ $dateObj->translatedFormat('F Y') }}
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 text-left">Cabang</th>
                                    <th class="px-6 py-4">Profit</th>
                                    <th class="px-6 py-4">Total Transaksi</th>
                                    <th class="px-6 py-4">Rata-rata/Transaksi</th>
                                    <th class="px-6 py-4">Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($branchSummaries as $summary)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-left font-bold text-gray-900">{{ $summary['branch']->name }}</td>
                                        <td class="px-6 py-4 font-bold text-green-600 tabular-nums">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-900 tabular-nums">{{ $summary['total_transactions'] }}</td>
                                        <td class="px-6 py-4 text-gray-600 tabular-nums">
                                            Rp {{ $summary['total_transactions'] > 0 ? number_format($summary['total_revenue'] / $summary['total_transactions'], 0, ',', '.') : '0' }}
                                        </td>
                                        <td class="px-6 py-4 tabular-nums">
                                            <span class="{{ $summary['low_stock_count'] > 0 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                                {{ number_format($summary['total_stock']) }} unit
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection