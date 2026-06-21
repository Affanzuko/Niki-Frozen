@extends('layouts.app')

@section('title', 'Keuangan - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    {{-- Sidebar --}}
    <x-sidebar role="general-manager" active="keuangan" />

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto relative w-full transition-all duration-300 bg-[#f8fafc]">
        <div class="min-h-full p-4 lg:p-8 bg-[#f8fafc] rounded-tl-none lg:rounded-tl-2xl flex flex-col"
            x-data="keuanganGlobal()">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button x-data @click="$dispatch('open-sidebar')" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Keuangan</h1>
                        <p class="text-gray-500 mt-1 text-sm">Sistem Manajemen untuk Mengelola Keuangan.</p>
                    </div>
                </div>
                <div
                    class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <i class="fa-regular fa-calendar text-brand-textBlue"></i>
                    <span class="text-sm font-semibold text-gray-700">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <div class="flex-1 flex flex-col relative gap-6">
                <!-- Filter Section -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2 relative">
                        <template x-for="tab in ['Hari', 'Minggu', 'Bulan', 'Tahun']">
                            <button @click="fetchData(tab)"
                                class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors"
                                :class="filterWaktu === tab ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                    'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                                x-text="tab">
                            </button>
                        </template>

                    </div>
                </div>

                <!-- Visualization & Summary Cards -->
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Trend Chart Container -->
                    <div class="flex-1 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="px-1">
                            <h2
                                class="text-base font-bold text-slate-800 mb-8 flex
                            items-center gap-4">
                                <i class="fa-solid fa-chart-line text-brand-textBlue"></i>
                                Tren Pendapatan Gabungan
                            </h2>
                        </div>
                        <div class="relative w-full h-[350px] lg:h-[450px]">
                            <canvas id="trendPendapatanChart"></canvas>
                        </div>
                    </div>

                    <!-- Global Summary Cards -->
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
                                x-text="'Rp ' + formatRupiah(globalTotalPemasukan)">Rp
                                {{ number_format($globalTotalPemasukan, 0, ',', '.') }}</h3>
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
                                x-text="'Rp ' + formatRupiah(globalTotalCash)">Rp
                                {{ number_format($globalTotalCash, 0, ',', '.') }}
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
                                x-text="'Rp ' + formatRupiah(globalTotalQris)">Rp
                                {{ number_format($globalTotalQris, 0, ',', '.') }}
                            </h3>
                        </div>
                        <!-- Tambahan: Total Transaksi -->
                        <div
                            class="rounded-xl p-5 border border-slate-200 bg-white shadow-sm flex-1 flex flex-col justify-center transition-all duration-300 hover:shadow-md hover:border-orange-500">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                                <div
                                    class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-700 tabular-nums"
                                x-text="formatRupiah(globalTotalTransaksi)">
                                {{ number_format($globalTotalTransaksi, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex-1 flex flex-col">
                    <div class="px-6 py-5">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-4">
                            <i class="fa-solid fa-store text-brand-textBlue"></i>
                            Rincian Keuangan per Cabang
                        </h2>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
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
                                <template x-for="summary in branchSummaries" :key="summary.branch.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white shrink-0">
                                                    <i class="fa-solid fa-store text-sm"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p class="font-bold text-slate-800" x-text="summary.branch.name"></p>
                                                    <p class="text-[11px] text-gray-500" x-text="summary.branch.address">
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-green-600 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(summary.total_pemasukan)"></td>
                                        <td class="px-6 py-4 font-bold text-red-500 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(summary.total_pengeluaran)"></td>
                                        <td class="px-6 py-4 font-bold text-slate-800 tabular-nums"
                                            x-text="'Rp ' + formatRupiah(summary.laba_bersih)"></td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold text-slate-800"
                                                    x-text="Number(summary.margin).toFixed(1) + '%'"></span>
                                                <div class="w-16 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-full"
                                                        :class="summary.margin >= 30 ? 'bg-green-500' : (summary.margin > 15 ?
                                                            'bg-yellow-400' : 'bg-red-500')"
                                                        :style="'width: ' + Math.min(summary.margin, 100) + '%'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <template x-if="summary.margin >= 30">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200">
                                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                                    Sangat Baik
                                                </span>
                                            </template>
                                            <template x-if="summary.margin > 15 && summary.margin < 30">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">
                                                    <i class="fa-solid fa-minus"></i>
                                                    Normal
                                                </span>
                                            </template>
                                            <template x-if="summary.margin <= 15">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                                    Perlu Evaluasi
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a :href="'/gm/keuangan/' + encodeURIComponent(summary.branch.name)"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-brand-textBlue hover:bg-brand-blue hover:text-white transition-colors"
                                                title="Detail Cabang">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="branchSummaries.length === 0" x-cloak>
                                    <td colspan="7" class="p-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                            <p>Belum ada data keuangan cabang.</p>
                                        </div>
                                    </td>
                                </tr>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('keuanganGlobal', () => ({
                filterWaktu: '{{ $filter ?? 'Hari' }}',


                globalTotalPemasukan: {{ $globalTotalPemasukan }},
                globalTotalCash: {{ $globalTotalCash }},
                globalTotalQris: {{ $globalTotalQris }},
                globalTotalTransaksi: {{ $globalTotalTransaksi }},
                branchSummaries: @json($branchSummaries),

                trendChartData: @json($trendChartData),

                activeFilter: 'total',

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number).replace(/,/g, '.');
                },

                init() {
                    this.initChartDatasets(this.trendChartData);
                    this.initCharts();
                },

                async fetchData(newFilter = null) {
                    if (newFilter) {
                        this.filterWaktu = newFilter;
                    }

                    this.isLoading = true;
                    try {
                        const url = new URL(window.location.href);
                        url.searchParams.set('waktu', this.filterWaktu);
                        url.searchParams.delete('tanggal');

                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.globalTotalPemasukan = data.globalTotalPemasukan;
                            this.globalTotalCash = data.globalTotalCash;
                            this.globalTotalQris = data.globalTotalQris;
                            this.globalTotalTransaksi = data.globalTotalTransaksi;
                            this.branchSummaries = data.branchSummaries;
                            this.trendChartData = data.trendChartData;

                            this.updateChartDataObj(data.trendChartData);
                            window.history.pushState({}, '', url);
                        }
                    } catch (error) {
                        console.error("Error fetching data:", error);
                    } finally {
                        this.isLoading = false;
                    }
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

                initCharts() {
                    const trendCtx = document.getElementById('trendPendapatanChart').getContext('2d');

                    window.trendChartInstance = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: this.trendChartData.map(item => item.label),
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

                updateChartDataObj(newChartData) {
                    if (!window.trendChartInstance) return;

                    this.initChartDatasets(newChartData);

                    window.trendChartInstance.data.labels = newChartData.map(item => item.label);

                    const ds = window.chartDatasets[this.activeFilter];
                    window.trendChartInstance.data.datasets[0].label = ds.label;
                    window.trendChartInstance.data.datasets[0].data = ds.data;
                    window.trendChartInstance.data.datasets[0].borderColor = ds.borderColor;
                    window.trendChartInstance.data.datasets[0].backgroundColor = ds.backgroundColor;
                    window.trendChartInstance.data.datasets[0].pointBackgroundColor = ds
                        .pointBackgroundColor;
                    window.trendChartInstance.data.datasets[0].pointBorderColor = ds.pointBorderColor;

                    window.trendChartInstance.update();
                },

                updateChartData(source) {
                    this.activeFilter = source;
                    if (!window.trendChartInstance) return;

                    const ds = window.chartDatasets[source];

                    window.trendChartInstance.data.datasets[0].label = ds.label;
                    window.trendChartInstance.data.datasets[0].data = ds.data;
                    window.trendChartInstance.data.datasets[0].borderColor = ds.borderColor;
                    window.trendChartInstance.data.datasets[0].backgroundColor = ds.backgroundColor;
                    window.trendChartInstance.data.datasets[0].pointBackgroundColor = ds
                        .pointBackgroundColor;
                    window.trendChartInstance.data.datasets[0].pointBorderColor = ds.pointBorderColor;

                    window.trendChartInstance.update();
                }
            }));
        });
    </script>
@endpush
