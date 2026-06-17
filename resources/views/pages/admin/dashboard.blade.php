@extends('layouts.app')

@section('title', 'Dashboard Admin - Niki Frozen')

@section('body_data')
    x-data="{
        selectedDate: '{{ $targetDate->isToday() ? "Hari Ini" : $targetDate->format("d M Y") }}',
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
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300">
        <div
            class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200 overflow-y-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Niki Frozen - Condongcatur</h1>
                        <p class="text-slate-500 font-medium">Ringkasan aktivitas hari ini</p>
                    </div>
                </div>
                <div>
                    <button x-ref="dateBtn" x-init="flatpickr($refs.dateBtn, {
                        dateFormat: 'd M Y',
                        defaultDate: '{{ $targetDate->format("d M Y") }}',
                        onChange: function(selectedDates, dateStr) {
                            if (selectedDates.length > 0) {
                                let formattedDate = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                                window.location.href = '?date=' + formattedDate;
                            }
                        }
                    })"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer focus:ring-2 focus:ring-brand-blue">
                        <i class="fa-regular fa-calendar text-brand-textBlue"></i>
                        <span x-text="selectedDate">Hari Ini</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div>
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
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">Rp
                                {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
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
                            <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight tabular-nums">
                                {{ $todayTransactions }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Transaksi berhasil hari ini</p>
                        </div>
                    </div>

                    <!-- Kartu Stok -->
                    <div onclick="window.location='{{ route('pages.admin.stok') }}'"
                        class="group bg-white rounded-2xl p-6 border {{ $nearExpiryCount > 0 ? 'border-red-200' : 'border-gray-200' }} shadow-sm flex flex-col justify-between cursor-pointer hover:-translate-y-1 hover:shadow-md hover:border-red-400 transition-all duration-300 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full opacity-50 group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <p class="text-xs font-bold text-gray-500 tracking-wider uppercase">Peringatan Stok
                                Kadaluarsa</p>
                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold text-red-600 mb-1 tracking-tight tabular-nums">
                                {{ $nearExpiryCount }} Barang</h3>
                            <p class="text-sm text-gray-500 font-medium">
                                {{ $nearExpiryCount > 0 ? 'Butuh pengecekan segera' : 'Tidak ada peringatan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6 flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white shrink-0">
                        <h2 class="text-base font-bold text-slate-800">Aktivitas Kasir Terbaru</h2>
                        <button onclick="window.location='{{ route('pages.admin.penjualan') }}'"
                            class="px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-lg hover:bg-slate-900 transition-all shadow-sm hover:shadow-md active:scale-95 cursor-pointer">
                            Lihat Semua Transaksi
                        </button>
                    </div>

                    <div class="w-full overflow-x-auto" x-data="{
                        syncScroll(e) {
                            if ($refs.headerScroll) $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                        }
                    }">
                        <!-- Header Table (Static) -->
                        <div class="bg-slate-50 border-b border-slate-200 overflow-hidden" x-ref="headerScroll">
                            <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                                <colgroup>
                                    <col class="w-[15%]">
                                    <col class="w-[30%]">
                                    <col class="w-[15%]">
                                    <col class="w-[20%]">
                                    <col class="w-[20%]">
                                </colgroup>
                                <thead>
                                    <tr class="text-xs text-slate-500 uppercase tracking-wider font-bold">
                                        <th class="p-4 py-4 pl-12 text-left">Waktu</th>
                                        <th class="p-4 py-4 text-center">Kasir</th>
                                        <th class="p-4 py-4 text-center">Transaksi</th>
                                        <th class="p-4 py-4 text-center">Total (Rp)</th>
                                        <th class="p-4 py-4 text-center">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <!-- Body Table (Scrollable) -->
                        <div class="overflow-auto relative bg-white" @scroll="syncScroll">
                            <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                                <colgroup>
                                    <col class="w-[15%]">
                                    <col class="w-[30%]">
                                    <col class="w-[15%]">
                                    <col class="w-[20%]">
                                    <col class="w-[20%]">
                                </colgroup>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse ($recentSessions as $session)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="p-4 pl-12 text-slate-700 font-medium tabular-nums text-left">
                                                {{ $session->started_at ? $session->started_at->format('H:i') : '-' }}
                                                -
                                                {{ $session->ended_at ? $session->ended_at->format('H:i') : 'Sekarang' }}
                                            </td>
                                            <td class="p-4 text-slate-800 font-bold text-center truncate">
                                                {{ $session->user->name ?? '-' }}</td>
                                            <td class="p-4 text-slate-700 font-semibold tabular-nums text-center">
                                                {{ $session->total_transactions }}</td>
                                            <td class="p-4 font-bold text-slate-800 tabular-nums text-center">
                                                {{ number_format($session->total_revenue ?? 0, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center">
                                                @if (is_null($session->ended_at))
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700">Aktif</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-4 py-12 text-center text-slate-400">
                                                <i class="fa-solid fa-user-clock text-3xl mb-2 block"></i>
                                                <p class="font-medium">Belum ada aktivitas kasir</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
