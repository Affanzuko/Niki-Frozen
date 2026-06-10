@extends('layouts.app')

@section('title', 'Detail Keuangan - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar-gm', ['active' => 'keuangan'])

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-6 relative">
            {{-- Header --}}
            <div class="mb-6 flex-shrink-0">
                <h1 class="text-2xl font-bold text-slate-800">Detail Keuangan Cabang {{ $cabang ?? 'Condongcatur' }}</h1>
                <p class="text-sm text-slate-500">Sistem Manajemen untuk Mengelola Keuangan.</p>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 no-scrollbar">
                {{-- Filters --}}
                <div class="flex items-center gap-2 mb-6">
                    <button class="px-5 py-2 rounded-lg bg-[#dbeafe] text-blue-700 text-sm font-semibold border border-[#bfdbfe]">Hari</button>
                    <button class="px-5 py-2 rounded-lg bg-white text-slate-600 border border-slate-200 text-sm font-semibold hover:bg-slate-50">Minggu</button>
                    <button class="px-5 py-2 rounded-lg bg-white text-slate-600 border border-slate-200 text-sm font-semibold hover:bg-slate-50">Bulan</button>
                    <button class="px-5 py-2 rounded-lg bg-white text-slate-600 border border-slate-200 text-sm font-semibold hover:bg-slate-50">Tahun</button>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="p-4 text-center">Waktu</th>
                                <th class="p-4 text-center">Cash</th>
                                <th class="p-4 text-center">QRIS</th>
                                <th class="p-4 text-center">Pengeluaran</th>
                                <th class="p-4 text-center">Saldo Akhir</th>
                                <th class="p-4 text-center">Profit</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 text-center font-medium text-slate-800">Senin, 5 Juni 2026</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 7.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 27.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">Rp. 4.000.000</td>
                                <td class="p-4 text-center font-bold text-slate-800">Rp. 30.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">-</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 text-center font-medium text-slate-800">Selasa, 6 Juni 2026</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 7.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 27.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">Rp. 4.000.000</td>
                                <td class="p-4 text-center font-bold text-slate-800">Rp. 60.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">+ Rp. 30.000.000</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 text-center font-medium text-slate-800">Rabu, 7 Juni 2026</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 7.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 27.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">Rp. 4.000.000</td>
                                <td class="p-4 text-center font-bold text-slate-800">Rp. 90.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">+ Rp. 30.000.000</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 text-center font-medium text-slate-800">Kamis, 8 Juni 2026</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 7.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp. 27.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">Rp. 34.000.000</td>
                                <td class="p-4 text-center font-bold text-slate-800">Rp. 60.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">- Rp. 30.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
