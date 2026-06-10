@extends('layouts.app')

@section('title', 'Keuangan - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar-gm', ['active' => 'keuangan'])

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-6 relative">
            {{-- Header --}}
            <div class="mb-6 flex-shrink-0">
                <h1 class="text-2xl font-bold text-slate-800">Keuangan</h1>
                <p class="text-sm text-slate-500">Sistem Manajemen untuk Mengelola Keuangan.</p>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 no-scrollbar">
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="p-4">Cabang</th>
                                <th class="p-4 text-center">Total Pemasukan</th>
                                <th class="p-4 text-center">Total Pengeluaran</th>
                                <th class="p-4 text-center">Laba Bersih</th>
                                <th class="p-4 text-center">Margin</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-800">Condongcatur</td>
                                <td class="p-4 text-center text-slate-600">Rp 985.000.000</td>
                                <td class="p-4 text-center text-slate-600">Rp 632.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp 353.000.000</td>
                                <td class="p-4 text-center text-slate-500 text-xs">35.8%</td>
                                <td class="p-4 text-center"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold border border-green-100">Profit</span></td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('pages.general-manager.keuangan-detail', 'Condongcatur') }}" class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">Detail</a>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-800">Gejayan</td>
                                <td class="p-4 text-center text-slate-600">Rp 456.200.000</td>
                                <td class="p-4 text-center text-slate-600">Rp 478.000.000</td>
                                <td class="p-4 text-center font-bold text-red-500">-Rp 21.800.000</td>
                                <td class="p-4 text-center text-slate-500 text-xs">-4.8%</td>
                                <td class="p-4 text-center"><span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-xs font-bold border border-red-100">Rugi</span></td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('pages.general-manager.keuangan-detail', 'Gejayan') }}" class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">Detail</a>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-800">Depok</td>
                                <td class="p-4 text-center text-slate-600">Rp 743.500.000</td>
                                <td class="p-4 text-center text-slate-600">Rp 512.000.000</td>
                                <td class="p-4 text-center font-bold text-green-600">Rp 231.500.000</td>
                                <td class="p-4 text-center text-slate-500 text-xs">31.1%</td>
                                <td class="p-4 text-center"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold border border-green-100">Profit</span></td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('pages.general-manager.keuangan-detail', 'Depok') }}" class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
