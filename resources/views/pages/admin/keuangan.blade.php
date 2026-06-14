@extends('layouts.app')

@section('title', 'Keuangan Admin - Niki Frozen')

@section('body_data')
x-data="{
    keuanganFilter: 'Hari',
    sidebarOpen: false
}"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="keuangan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Niki Frozen - Condongcatur</h1>
                        <p class="text-slate-500 font-medium">Laporan Keuangan Kasir</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-h-0">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-slate-900 tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pendapatan Cash</p>
                    <h3 class="text-2xl font-bold text-green-600 tabular-nums">Rp {{ number_format($cashTotal, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pendapatan QRIS</p>
                    <h3 class="text-2xl font-bold text-brand-textBlue tabular-nums">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0" x-data="{
                syncScroll(e) {
                    $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                }
            }">
                <!-- Header Table (Static) -->
                <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[700px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[35%]">
                            <col class="w-[20%]">
                            <col class="w-[20%]">
                        </colgroup>
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4 py-3 text-left">Waktu</th>
                                <th class="p-4 py-3 text-left">No. Referensi</th>
                                <th class="p-4 py-3 text-center">Metode</th>
                                <th class="p-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Body Table (Scrollable) -->
                <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[700px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[35%]">
                            <col class="w-[20%]">
                            <col class="w-[20%]">
                        </colgroup>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
                            @forelse ($transactions as $trx)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-4 font-medium text-slate-700 truncate">{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    <td class="p-4 font-semibold text-blue-600 truncate">{{ $trx->transaction_code }}</td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase border shadow-sm {{ strtolower($trx->payment_method) === 'cash' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                            {{ $trx->payment_method }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-green-600 font-bold tabular-nums text-right truncate">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 py-12 text-center text-slate-400">
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
        </div>
    </main>
@endsection
