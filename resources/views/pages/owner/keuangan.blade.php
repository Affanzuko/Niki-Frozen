@extends('layouts.app')

@section('title', 'Keuangan Owner - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    {{-- Sidebar --}}
    <x-sidebar role="owner" active="keuangan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Header -->
            <header class="px-6 lg:px-8 py-6 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <button x-data @click="$dispatch('open-sidebar')" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Keuangan</h1>
                        <p class="text-gray-500 mt-1 text-sm">Sistem Manajemen untuk Mengelola Keuangan.</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <i class="fa-regular fa-calendar text-brand-textBlue"></i>
                    <span class="text-sm font-semibold text-gray-700">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </header>

            <div class="px-6 lg:px-8 pb-8 flex-1 flex flex-col" x-data="{ filterWaktu: 'Hari' }">
                
                <!-- Filter and Summary Container -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                    <!-- Filters -->
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <template x-for="tab in ['Hari', 'Minggu', 'Bulan', 'Tahun']">
                                <button @click="filterWaktu = tab"
                                    :class="filterWaktu === tab ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'"
                                    class="px-5 py-2 rounded-xl text-sm font-bold shadow-sm border transition-colors" x-text="tab">
                                </button>
                            </template>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="date" class="pl-11 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-textBlue text-gray-600 font-medium bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <!-- Global Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Pendapatan Total -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col relative overflow-hidden group hover:border-brand-textBlue transition-colors">
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendapatan Total</p>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-brand-textBlue">
                                    <i class="fa-solid fa-money-bills text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 tabular-nums relative z-10 tracking-tight">Rp {{ number_format($globalTotalPemasukan, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Pendapatan Cash -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col relative overflow-hidden group hover:border-green-500 transition-colors">
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendapatan Cash</p>
                                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-money-bill-wave text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 tabular-nums relative z-10 tracking-tight">Rp {{ number_format($globalTotalCash, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Pendapatan QRIS -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col relative overflow-hidden group hover:border-purple-500 transition-colors">
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendapatan QRIS</p>
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fa-solid fa-qrcode text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 tabular-nums relative z-10 tracking-tight">Rp {{ number_format($globalTotalQris, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Total Transaksi -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col relative overflow-hidden group hover:border-orange-500 transition-colors">
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 tabular-nums relative z-10 tracking-tight">{{ number_format($globalTotalTransaksi, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex-1 flex flex-col">
                    <div class="px-6 py-5 border-b border-gray-100 bg-white">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-store text-brand-textBlue"></i>
                            Rincian Keuangan per Cabang
                        </h2>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 text-left">Cabang</th>
                                    <th class="px-6 py-4">Total Pemasukan</th>
                                    <th class="px-6 py-4">Total Pengeluaran</th>
                                    <th class="px-6 py-4">Laba Bersih</th>
                                    <th class="px-6 py-4">Margin</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($branchSummaries as $summary)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white shrink-0">
                                                    <i class="fa-solid fa-store text-sm"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p class="font-bold text-gray-900">{{ $summary['branch']->name }}</p>
                                                    <p class="text-[11px] text-gray-500">{{ $summary['branch']->address }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-green-600 tabular-nums">
                                            Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-red-500 tabular-nums">
                                            Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-blue-600 tabular-nums">
                                            Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold text-gray-900">{{ number_format($summary['margin'], 1) }}%</span>
                                                <div class="w-16 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-full {{ $summary['margin'] >= 30 ? 'bg-green-500' : ($summary['margin'] > 15 ? 'bg-yellow-400' : 'bg-red-500') }}" 
                                                         style="width: {{ min($summary['margin'], 100) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($summary['margin'] >= 30)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200">
                                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                                    Sangat Baik
                                                </span>
                                            @elseif ($summary['margin'] > 15)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">
                                                    <i class="fa-solid fa-minus"></i>
                                                    Normal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                                    Perlu Evaluasi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('pages.owner.detail-cabang', $summary['branch']->id) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-brand-textBlue hover:bg-brand-blue hover:text-white transition-colors" title="Detail Cabang">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                                <p>Belum ada data keuangan cabang.</p>
                                            </div>
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
