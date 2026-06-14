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
    <main class="flex-1 overflow-y-auto relative w-full transition-all duration-300 bg-[#f8fafc]">
        <div class="min-h-full p-4 lg:p-8 bg-[#f8fafc] rounded-tl-none lg:rounded-tl-2xl flex flex-col">
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
            <div class="flex-1 flex flex-col gap-6">
                <!-- Filter Section -->
                <div class="flex gap-2">
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
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Line Chart -->
                    <div class="flex-1 bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                        <div class="relative w-full h-[300px] lg:h-[400px]">
                            <canvas id="keuanganChart"></canvas>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="w-full lg:w-[320px] xl:w-[380px] flex flex-col gap-5">
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex-1 flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600">
                                    <i class="fa-solid fa-money-bills text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800 tabular-nums">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex-1 flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Cash</p>
                                <div
                                    class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-money-bill-wave text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-green-600 tabular-nums">Rp
                                {{ number_format($cashTotal, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex-1 flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan QRIS</p>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-qrcode text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 tabular-nums">Rp
                                {{ number_format($qrisTotal, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full border border-slate-200 rounded-xl overflow-hidden bg-white flex-1 flex flex-col min-h-0"
                    x-data="{
                        syncScroll(e) {
                            $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                        }
                    }">
                    <!-- Header Table (Static) -->
                    <div class="bg-[#f8fafc] overflow-hidden" x-ref="headerScroll">
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
                                    <th class="p-4 py-4 text-center">Waktu</th>
                                    <th class="p-4 py-4 text-center">Cash</th>
                                    <th class="p-4 py-4 text-center">QRIS</th>
                                    <th class="p-4 py-4 text-center">Pengeluaran</th>
                                    <th class="p-4 py-4 text-center">Saldo Akhir</th>
                                    <th class="p-4 py-4 text-center whitespace-nowrap">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Body Table (Scrollable) -->
                    <div class="overflow-x-auto relative" @scroll="syncScroll">
                        <table class="w-full text-left table-fixed min-w-[800px] shadow-sm rounded-xl">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                                <col class="w-[16%]">
                            </colgroup>
                            <tbody class="text-sm font-medium">
                                @forelse ($tableData as $row)
                                    <tr class="bg-white shadow-sm hover:shadow-md hover:bg-slate-50 transition-all group">
                                        <td class="p-4 text-center text-slate-800 border border-r-0 border-slate-100">
                                            {{ $row['waktu'] }}</td>
                                        <td
                                            class="p-4 text-center text-green-600 tabular-nums border border-l-0 border-r-0 border-slate-100">
                                            Rp
                                            {{ number_format($row['cash'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-green-600 tabular-nums">Rp
                                            {{ number_format($row['qris'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-red-600 tabular-nums">Rp
                                            {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center text-slate-800 tabular-nums">Rp
                                            {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
                                        <td
                                            class="p-4 text-center tabular-nums border border-l-0 border-slate-100 {{ $row['profit'] > 0 ? 'text-green-600' : ($row['profit'] < 0 ? 'text-red-500' : 'text-slate-400') }}">
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
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(219, 234, 254, 0.4)',
                        borderWidth: 2,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#2563eb',
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
