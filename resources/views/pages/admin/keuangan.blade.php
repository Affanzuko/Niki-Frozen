@extends('layouts.app')

@section('title', 'Keuangan Admin - Niki Frozen')

@section('body_data')
    x-data="{
        sidebarOpen: false
    }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="keuangan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300 bg-[#f8fafc]">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-[#f8fafc] rounded-tl-none lg:rounded-tl-2xl">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Niki Frozen - Condongcatur</h1>
                        <p class="text-slate-500 font-medium">Laporan Keuangan</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-h-0">

                <!-- Filter Section -->
                <div class="mb-6 flex gap-2">
                    <a href="?waktu=Hari"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors border {{ $filter === 'Hari' ? 'bg-[#dbeafe] text-blue-600 border-[#dbeafe]' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">Hari</a>
                    <a href="?waktu=Minggu"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors border {{ $filter === 'Minggu' ? 'bg-[#dbeafe] text-blue-600 border-[#dbeafe]' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">Minggu</a>
                    <a href="?waktu=Bulan"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors border {{ $filter === 'Bulan' ? 'bg-[#dbeafe] text-blue-600 border-[#dbeafe]' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">Bulan</a>
                    <a href="?waktu=Tahun"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors border {{ $filter === 'Tahun' ? 'bg-[#dbeafe] text-blue-600 border-[#dbeafe]' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">Tahun</a>
                </div>

                <!-- Visualization & Summary Cards -->
                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <!-- Line Chart -->
                    <div class="flex-1 bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative min-h-[300px]">
                        <canvas id="keuanganChart"></canvas>
                    </div>

                    <!-- Summary Cards -->
                    <div class="lg:w-1/3 xl:w-1/4 flex flex-col gap-4">
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-center min-h-[100px]">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                            <h3 class="text-2xl font-bold text-slate-800 tabular-nums">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-center min-h-[100px]">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pendapatan Cash</p>
                            <h3 class="text-2xl font-bold text-green-500 tabular-nums">Rp
                                {{ number_format($cashTotal, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-center min-h-[100px]">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pendapatan QRIS</p>
                            <h3 class="text-2xl font-bold text-blue-600 tabular-nums">Rp
                                {{ number_format($qrisTotal, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0"
                    x-data="{
                        syncScroll(e) {
                            $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                        }
                    }">
                    <!-- Header Table (Static) -->
                    <div class="bg-[#f8fafc] border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                        <table class="w-full text-left border-collapse table-fixed min-w-[800px]">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                            </colgroup>
                            <thead>
                                <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="p-4 py-3 text-center">Waktu</th>
                                    <th class="p-4 py-3 text-center">Cash</th>
                                    <th class="p-4 py-3 text-center">QRIS</th>
                                    <th class="p-4 py-3 text-center">Pengeluaran</th>
                                    <th class="p-4 py-3 text-center">Saldo Akhir</th>
                                    <th class="p-4 py-3 text-center">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Body Table (Scrollable) -->
                    <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                        <table class="w-full text-left border-collapse table-fixed min-w-[800px]">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                            </colgroup>
                            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                @forelse ($tableData as $row)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="p-4 text-center text-slate-800">{{ $row['waktu'] }}</td>
                                        <td class="p-4 text-center text-green-500 tabular-nums">Rp
                                            {{ number_format($row['cash'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-green-500 tabular-nums">Rp
                                            {{ number_format($row['qris'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-orange-500 tabular-nums">Rp
                                            {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-slate-800 tabular-nums">Rp
                                            {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
                                        <td
                                            class="p-4 text-center tabular-nums {{ $row['profit'] > 0 ? 'text-green-500' : ($row['profit'] < 0 ? 'text-red-500' : 'text-slate-400') }}">
                                            @if ($row['profit'] > 0)
                                                + Rp {{ number_format($row['profit'], 0, ',', '.') }}
                                            @elseif($row['profit'] < 0)
                                                - Rp {{ number_format(abs($row['profit']), 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 py-12 text-center text-slate-400">
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('keuanganChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.label),
                    datasets: [{
                        label: 'Total Pendapatan',
                        data: chartData.map(item => item.total),
                        borderColor: '#1e293b',
                        backgroundColor: 'rgba(219, 234, 254, 0.5)', // bg-[#dbeafe] with opacity
                        borderWidth: 2,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#1e293b',
                        pointRadius: 4,
                        fill: true,
                        tension: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000) + 'K';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endpush
