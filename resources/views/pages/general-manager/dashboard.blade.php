@extends('layouts.app')

@section('title', 'Dashboard GM - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar (Dynamic Role) --}}
    <x-sidebar role="general-manager" active="dashboard" />

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-6 relative">
            {{-- Header --}}
            <div class="mb-6 flex-shrink-0">
                <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
                <p class="text-sm text-slate-500">Sistem Manajemen untuk melihat dan memantau semua kegiatan.</p>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 no-scrollbar">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div onclick="goToKeuanganDetail()"
                        class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:border-blue-300 transition-all active:scale-95 group">
                        <div class="flex justify-between items-start mb-2">
                            <h3
                                class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-blue-600 transition-colors">
                                PENDAPATAN HARI INI</h3>
                            <div
                                class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-800 mb-1">Rp 24.850.000</p>
                        <p class="text-xs font-bold text-slate-600">+12.5% dari kemarin</p>
                    </div>

                    <div onclick="goToKeuanganDetail()"
                        class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:border-blue-300 transition-all active:scale-95 group">
                        <div class="flex justify-between items-start mb-2">
                            <h3
                                class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-blue-600 transition-colors">
                                PEMBAYARAN CASH</h3>
                            <div
                                class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-800 mb-1">Rp 12.000.000</p>
                        <p class="text-xs font-bold text-slate-600">Rata-rata pembayaran Rp. 15.250.000</p>
                    </div>

                    <div onclick="goToKeuanganDetail()"
                        class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:border-blue-300 transition-all active:scale-95 group">
                        <div class="flex justify-between items-start mb-2">
                            <h3
                                class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-blue-600 transition-colors">
                                PEMBAYARAN QRIS</h3>
                            <div
                                class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-slate-800 mb-1">Rp 12.850.000</p>
                        <p class="text-xs font-bold text-slate-600">Rata rata pembayaran Rp. 13.150.000</p>
                    </div>
                </div>

                {{-- Chart Area --}}
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 mb-1">Pilih Cabang</p>
                            <select id="pilih-cabang"
                                class="border border-slate-200 rounded-lg text-sm px-3 py-1.5 outline-none focus:border-blue-500">
                                <option value="Condongcatur">Condongcatur</option>
                                <option value="Gejayan">Gejayan</option>
                                <option value="Depok">Depok</option>
                            </select>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Grafik Keuntungan Setiap Cabang</h2>
                        <div></div> <!-- spacer -->
                    </div>

                    <div class="h-72 border-t border-slate-100 pt-6 flex items-end relative mt-2">
                        <!-- Y-Axis Lines -->
                        <div
                            class="absolute left-0 bottom-6 top-6 w-full flex flex-col justify-between text-[10px] text-slate-400 pointer-events-none z-0">
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">Rp 25 Jt</div>
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">Rp 20 Jt</div>
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">Rp 15 Jt</div>
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">Rp 10 Jt</div>
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">Rp 5 Jt</div>
                            <div class="border-b border-slate-100 border-dashed w-full text-left pb-1">0</div>
                        </div>

                        <!-- Bars -->
                        <div
                            class="flex-1 flex items-end justify-around h-full z-10 pl-12 sm:pl-16 pr-2 pb-6 relative w-full overflow-x-auto no-scrollbar">
                            @php
                                $chartData = [
                                    ['day' => 'Senin', 'height' => 40, 'color' => 'bg-slate-300'],
                                    ['day' => 'Selasa', 'height' => 60, 'color' => 'bg-slate-400'],
                                    ['day' => 'Rabu', 'height' => 45, 'color' => 'bg-slate-300'],
                                    ['day' => 'Kamis', 'height' => 80, 'color' => 'bg-slate-900'],
                                    ['day' => 'Jumat', 'height' => 55, 'color' => 'bg-slate-400'],
                                    ['day' => 'Sabtu', 'height' => 90, 'color' => 'bg-[#0f172a]'],
                                    ['day' => 'Minggu', 'height' => 75, 'color' => 'bg-slate-800'],
                                ];
                            @endphp
                            @foreach ($chartData as $data)
                                <div class="flex flex-col items-center group cursor-pointer w-full max-w-[40px] md:max-w-[50px]"
                                    style="height: 100%">
                                    <div class="flex-1 flex items-end w-full justify-center relative">
                                        {{-- Tooltip --}}
                                        <div
                                            class="absolute -top-8 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                            {{ $data['height'] }}%
                                        </div>
                                        <div class="w-8 md:w-10 {{ $data['color'] }} rounded-t-md transition-all group-hover:opacity-80"
                                            style="height: {{ $data['height'] }}%"></div>
                                    </div>
                                    <span
                                        class="text-[10px] md:text-xs text-slate-500 font-medium mt-2">{{ $data['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    </main>

    @push('scripts')
    @vite('resources/js/pages/general-manager/dashboard.js')
    @endpush
@endsection
