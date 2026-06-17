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
            <div class="flex-1 flex flex-col gap-6" x-data="keuanganFilter()">
                <!-- Filter Section -->
                <div class="flex gap-2 relative items-center">
                    <button @click="fetchData('Hari')"
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors"
                        :class="filter === 'Hari' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                            'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'">Hari</button>
                    <button @click="fetchData('Minggu')"
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors"
                        :class="filter === 'Minggu' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                            'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'">Minggu</button>
                    <button @click="fetchData('Bulan')"
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors"
                        :class="filter === 'Bulan' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                            'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'">Bulan</button>
                    <button @click="fetchData('Tahun')"
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors"
                        :class="filter === 'Tahun' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                            'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'">Tahun</button>
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
                        <div @click="activeFilter = 'total'; updateChartData('total')"
                            :class="activeFilter === 'total' ? 'text-slate-900 bg-white shadow-sm' :
                                'border-slate-200 bg-white shadow-sm'"
                            class="cursor-pointer rounded-xl p-5 border shadow-sm flex-1 flex flex-col justify-center transition-all duration-300 hover:shadow-md hover:border-[#0f172a]">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600">
                                    <i class="fa-solid fa-money-bills text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-700 tabular-nums"
                                x-text="'Rp ' + formatRupiah(totalRevenue)">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div @click="activeFilter = 'cash'; updateChartData('cash')"
                            :class="activeFilter === 'cash' ? 'border-green-500 bg-white shadow-sm' :
                                'border-slate-200 bg-white shadow-sm'"
                            class="cursor-pointer rounded-xl p-5 border shadow-sm flex-1 flex flex-col justify-center transition-all duration-300 hover:shadow-md hover:border-green-500">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Cash</p>
                                <div
                                    class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-money-bill-wave text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-green-600 tabular-nums"
                                x-text="'Rp ' + formatRupiah(cashTotal)">Rp {{ number_format($cashTotal, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div @click="activeFilter = 'qris'; updateChartData('qris')"
                            :class="activeFilter === 'qris' ? 'border-blue-500 bg-white shadow-sm' :
                                'border-slate-200 bg-white shadow-sm'"
                            class="cursor-pointer rounded-xl p-5 border shadow-sm flex-1 flex flex-col justify-center transition-all duration-300 hover:shadow-md hover:border-blue-500">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan QRIS</p>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-qrcode text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 tabular-nums"
                                x-text="'Rp ' + formatRupiah(qrisTotal)">Rp {{ number_format($qrisTotal, 0, ',', '.') }}
                            </h3>
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
                    <div class="overflow-auto relative" @scroll="syncScroll">
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
                                <template x-for="row in tableData" :key="row.waktu">
                                    <tr class="bg-white shadow-sm hover:shadow-md hover:bg-slate-50 transition-all group">
                                        <td class="p-4 text-center text-slate-700 border border-r-0 border-slate-100"
                                            x-text="row.waktu"></td>
                                        <td class="p-4 text-center font-semibold text-green-600 tabular-nums border border-l-0 border-r-0 border-slate-100"
                                            x-text="'Rp ' + formatRupiah(row.cash)"></td>
                                        <td class="p-4 text-center font-semibold text-blue-600 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(row.qris)"></td>
                                        <td class="p-4 text-center font-semibold text-red-600 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(row.pengeluaran)"></td>
                                        <td class="p-4 text-center font-semibold text-slate-800 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(row.saldo_akhir)"></td>
                                        <td class="p-4 text-center font-semibold tabular-nums border border-l-0 border-slate-100"
                                            :class="row.profit > 0 ? 'text-green-600' : (row.profit < 0 ? 'text-red-500' :
                                                'text-slate-400')">
                                            <span x-show="row.profit > 0"
                                                x-text="'+ Rp ' + formatRupiah(row.profit)"></span>
                                            <span x-show="row.profit < 0"
                                                x-text="'- Rp ' + formatRupiah(Math.abs(row.profit))"></span>
                                            <span x-show="row.profit == 0">-</span>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="tableData.length === 0" style="display: none;">
                                    <td colspan="6" class="p-4 py-12 text-center text-slate-400">
                                        <i class="fa-solid fa-chart-line text-3xl mb-2 block"></i>
                                        <p class="font-medium">Belum ada data keuangan</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-container" class="px-4 py-3 border-t border-slate-200 bg-slate-50 shrink-0">
                        @if ($tablePaginated->hasPages())
                            {{ $tablePaginated->onEachSide(0)->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let keuanganChartInstance = null;
        document.addEventListener('alpine:init', () => {
            Alpine.data('keuanganFilter', () => ({
                filter: '{{ $filter }}',
                totalRevenue: {{ $totalRevenue }},
                cashTotal: {{ $cashTotal }},
                qrisTotal: {{ $qrisTotal }},
                tableData: @json(collect($tablePaginated->items())->values()),
                chartData: @json($chartData),
                isLoading: false,
                activeFilter: 'total',

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number).replace(/,/g, '.');
                },

                init() {
                    const ctx = document.getElementById('keuanganChart').getContext('2d');

                    this.initChartDatasets(this.chartData);

                    keuanganChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.chartData.map(item => item.label),
                            datasets: [{
                                label: window.chartDatasets['total'].label,
                                data: window.chartDatasets['total'].data,
                                borderColor: window.chartDatasets['total'].borderColor,
                                backgroundColor: window.chartDatasets['total']
                                    .backgroundColor,
                                borderWidth: 2,
                                pointBackgroundColor: window.chartDatasets['total']
                                    .pointBackgroundColor,
                                pointBorderColor: window.chartDatasets['total']
                                    .pointBorderColor,
                                pointRadius: 4,
                                fill: true,
                                tension: 0.4
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
                },

                initChartDatasets(data) {
                    window.chartDatasets = {
                        'total': {
                            label: 'Total Pendapatan',
                            data: data.map(item => item.total),
                            borderColor: '#0f172a',
                            backgroundColor: 'rgba(15, 23, 42, 0.05)',
                            pointBackgroundColor: '#0f172a',
                            pointBorderColor: '#0f172a'
                        },
                        'cash': {
                            label: 'Pendapatan Cash',
                            data: data.map(item => item.cash),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.05)',
                            pointBackgroundColor: '#16a34a',
                            pointBorderColor: '#16a34a'
                        },
                        'qris': {
                            label: 'Pendapatan QRIS',
                            data: data.map(item => item.qris),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.05)',
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#2563eb'
                        }
                    };
                },

                async fetchData(newFilter) {
                    if (this.filter === newFilter) return;
                    this.isLoading = true;
                    this.filter = newFilter;

                    try {
                        const response = await fetch(`?waktu=${newFilter}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.totalRevenue = data.totalRevenue;
                            this.cashTotal = data.cashTotal;
                            this.qrisTotal = data.qrisTotal;
                            this.tableData = data.tableData;
                            this.chartData = data.chartData;

                            this.updateChartDataObj(data.chartData);

                            const paginationContainer = document.getElementById(
                                'pagination-container');
                            if (paginationContainer && data.pagination !== undefined) {
                                paginationContainer.innerHTML = data.pagination;
                            }

                            window.history.pushState({}, '', `?waktu=${newFilter}`);
                        }
                    } catch (error) {
                        console.error("Error fetching data:", error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                updateChartDataObj(newChartData) {
                    if (!keuanganChartInstance) return;

                    this.initChartDatasets(newChartData);

                    keuanganChartInstance.data.labels = newChartData.map(item => item.label);

                    const ds = window.chartDatasets[this.activeFilter];
                    keuanganChartInstance.data.datasets[0].label = ds.label;
                    keuanganChartInstance.data.datasets[0].data = ds.data;
                    keuanganChartInstance.data.datasets[0].borderColor = ds.borderColor;
                    keuanganChartInstance.data.datasets[0].backgroundColor = ds.backgroundColor;
                    keuanganChartInstance.data.datasets[0].pointBackgroundColor = ds
                        .pointBackgroundColor;
                    keuanganChartInstance.data.datasets[0].pointBorderColor = ds.pointBorderColor;

                    keuanganChartInstance.update();
                },

                updateChartData(source) {
                    this.activeFilter = source;
                    if (!keuanganChartInstance) return;
                    const ds = window.chartDatasets[source];

                    keuanganChartInstance.data.datasets[0].label = ds.label;
                    keuanganChartInstance.data.datasets[0].data = ds.data;
                    keuanganChartInstance.data.datasets[0].borderColor = ds.borderColor;
                    keuanganChartInstance.data.datasets[0].backgroundColor = ds.backgroundColor;
                    keuanganChartInstance.data.datasets[0].pointBackgroundColor = ds
                        .pointBackgroundColor;
                    keuanganChartInstance.data.datasets[0].pointBorderColor = ds.pointBorderColor;

                    keuanganChartInstance.update();
                }
            }));
        });
    </script>
@endpush
