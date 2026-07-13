@extends('layouts.app')

@section('title', 'Riwayat Penjualan - Niki Frozen')

@section('body_class', 'bg-slate-50 h-screen overflow-hidden text-slate-800')

@section('body_data')
    x-data="{
        transactions: {{ Js::from($transactions->items() ?? []) }},
        penjualanFilter: '{{ request('status', 'Semua') }}',
        searchQuery: '{{ request('search', '') }}',
        activeRowId: null,
        showDetailSidebar: false,
        updateFilter(status) {
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        },
        search() {
            const url = new URL(window.location.href);
            if (this.searchQuery) {
                url.searchParams.set('search', this.searchQuery);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        },
        get activeTransaction() {
            return this.transactions.find(t => t.id === this.activeRowId);
        },
        openDetail(id) {
            this.activeRowId = id;
            this.showDetailSidebar = true;
        },
        closeDetail() {
            this.showDetailSidebar = false;
            this.activeRowId = null;
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        formatCurrency(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }
    }"
@endsection

@section('content')
    <div class="flex h-full w-full relative">

        {{-- Sidebar Component --}}
        <x-sidebar role="kasir" active="penjualan" />

        {{-- Mobile Header --}}
        <div
            class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 z-40 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <button onclick="toggleMobileMenu()" class="p-2 -ml-2 rounded-lg hover:bg-slate-100 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
                <h1 class="font-bold text-lg text-slate-800">Riwayat Penjualan</h1>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="flex-1 h-full flex flex-col pt-16 lg:pt-0 overflow-hidden relative">
            <div
                class="flex-1 flex flex-col p-4 lg:p-8 no-scrollbar bg-white shadow-sm border-l border-t lg:border-t-0 border-slate-200 min-h-0">

                <div class="shrink-0">
                    {{-- Header --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800">{{ auth()->user()->branch->name ?? 'Cabang' }}</h1>
                            <p class="text-slate-500 font-medium">Riwayat Penjualan</p>
                        </div>
                        <div class="relative w-full md:w-80">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" x-model="searchQuery" @keydown.enter="search()"
                                placeholder="Cari No. Referensi & Enter"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium placeholder-slate-400">
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <button @click="updateFilter('Semua')"
                            :class="penjualanFilter === 'Semua' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                            class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Semua</button>
                        <button @click="updateFilter('Berhasil')"
                            :class="penjualanFilter === 'Berhasil' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                            class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Berhasil</button>
                        <button @click="updateFilter('Pending')"
                            :class="penjualanFilter === 'Pending' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                            class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Menunggu</button>
                        <button @click="updateFilter('Batal')"
                            :class="penjualanFilter === 'Batal' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                            class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Gagal</button>
                        <button @click="updateFilter('Retur')"
                            :class="penjualanFilter === 'Retur' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                                'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                            class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Retur</button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="w-full border border-slate-200 rounded-xl overflow-hidden bg-white flex-1 flex flex-col min-h-0"
                    x-data="{
                        syncScroll(e) {
                            $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                        }
                    }">
                    <!-- Header Table (Static) -->
                    <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                        <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[10%]">
                            </colgroup>
                            <thead>
                                <tr class="text-xs text-slate-500 uppercase tracking-wider font-bold">
                                    <th class="p-4 py-4 pl-12">Waktu</th>
                                    <th class="p-4 py-4 pl-12">No. Referensi</th>
                                    <th class="p-4 py-4 text-left pl-12">Kasir</th>
                                    <th class="p-4 py-4 text-center">Total Belanja</th>
                                    <th class="p-4 py-4 text-center">Metode</th>
                                    <th class="p-4 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Body Table (Scrollable) -->
                    <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                        <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[10%]">
                            </colgroup>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <template x-for="trx in transactions" :key="trx.id">
                                    <tr class="transaction-row hover:bg-slate-50 cursor-pointer transition-colors"
                                        :class="activeRowId === trx.id ? 'bg-blue-100' : ''" @click="openDetail(trx.id)">
                                        <td class="p-4 pl-12 font-medium text-slate-700 truncate"
                                            x-text="formatDate(trx.created_at)"></td>
                                        <td class="p-4 pl-12 font-semibold text-blue-600 truncate" x-text="trx.transaction_code">
                                        </td>
                                        <td class="p-4 pl-12 font-medium text-slate-700 truncate" x-text="trx.cashier_session?.user?.name || '-'">
                                        </td>
                                        <td class="p-4 font-semibold text-slate-700 truncate text-center"
                                            x-text="formatCurrency(trx.total_price)">
                                        </td>
                                        <td class="p-4 truncate text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase border"
                                                :class="(trx.payment_method || '').toLowerCase() === 'cash' ?
                                                    'bg-green-50 text-green-700 border-green-100' :
                                                    'bg-blue-50 text-blue-700 border-blue-100'"
                                                x-text="trx.payment_method">
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <template x-if="trx.status === 'success' || trx.status === 'Selesai'">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                                    Selesai
                                                </span>
                                            </template>
                                            <template x-if="trx.status === 'pending' || trx.status === 'Pending'">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                                    Pending
                                                </span>
                                            </template>
                                            <template
                                                x-if="trx.status !== 'success' && trx.status !== 'Selesai' && trx.status !== 'pending' && trx.status !== 'Pending'">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-100 truncate max-w-[100px]"
                                                    x-text="trx.status"></span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="transactions.length === 0">
                                    <td colspan="6" class="p-4 py-8 text-center text-slate-500 font-medium">Tidak ada
                                        transaksi ditemukan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($transactions->hasPages())
                        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50 shrink-0">
                            <div class="flex gap-3">
                                {{-- Pagination links + showing (kiri) --}}
                                <div class="w-full">
                                    {{ $transactions->onEachSide(0)->links('pagination::tailwind') }}
                                </div>
                                {{-- Page jump (kanan) --}}
                                <div class="flex items-center gap-2 shrink-0 order-2">
                                    <span class="text-sm text-slate-600 font-medium">Hal:</span>
                                    <input type="number" min="1" max="{{ $transactions->lastPage() }}"
                                        class="w-16 px-2 py-1.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center shadow-sm"
                                        x-data="{ jumpPage: '{{ $transactions->currentPage() }}' }"
                                        x-model="jumpPage"
                                        @keydown.enter="
                                            let p = parseInt(jumpPage);
                                            if (p >= 1 && p <= {{ $transactions->lastPage() }}) {
                                                const url = new URL(window.location.href);
                                                url.searchParams.set('page', p);
                                                window.location.href = url.toString();
                                            }
                                        "
                                        onfocus="this.select()">
                                    <span class="text-sm text-slate-600 font-medium">/ {{ $transactions->lastPage() }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        {{-- Right Sidebar Detail --}}
        <aside id="detail-sidebar" x-show="activeTransaction"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl lg:shadow-none border-l border-slate-200 lg:static z-50 flex flex-col shrink-0">

            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-800 text-base">Detail Transaksi</h2>
                <button @click="closeDetail()"
                    class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <template x-if="activeTransaction">
                <div class="flex-1 overflow-y-auto p-6 no-scrollbar flex flex-col">
                    {{-- Header Receipt --}}
                    <div class="text-center mb-6 pb-6 border-b border-slate-200 border-dashed shrink-0">
                        <h3 class="text-xl font-bold text-slate-900" x-text="activeTransaction.transaction_code"></h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium"><span
                                x-text="formatDate(activeTransaction.created_at)"></span> • Kasir: <span
                                x-text="activeTransaction.cashier_session?.user?.name || '-'"></span></p>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-4 mb-6 flex-1">
                        <template x-for="item in activeTransaction.items" :key="item.id">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <p class="text-sm font-bold text-slate-800 leading-snug"
                                        x-text="item.product?.name || item.product_name">
                                    </p>
                                    <p class="text-xs font-medium text-slate-500 mt-0.5"
                                        x-text="item.quantity + ' x ' + formatCurrency(item.unit_price)"></p>
                                </div>
                                <p class="text-sm font-bold text-slate-900 whitespace-nowrap"
                                    x-text="formatCurrency(item.quantity * item.unit_price)"></p>
                            </div>
                        </template>
                    </div>

                    <div class="w-full border-t border-slate-200 mb-6 shrink-0"></div>

                    {{-- Summary --}}
                    <div class="space-y-3 shrink-0">
                        <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                            <span>Subtotal</span>
                            <span x-text="formatCurrency(activeTransaction.total_price / 1.11)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                            <span>Pajak (11%)</span>
                            <span
                                x-text="formatCurrency(activeTransaction.total_price - (activeTransaction.total_price / 1.11))"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 mt-3 border-t border-slate-200 border-dashed">
                            <span class="text-lg font-black text-slate-900">Total</span>
                            <span class="text-lg font-black text-slate-900"
                                x-text="formatCurrency(activeTransaction.total_price)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-3">
                            <span class="font-medium text-slate-500">Metode Pembayaran</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold uppercase border shadow-sm"
                                :class="(activeTransaction.payment_method || '').toLowerCase() === 'cash' ?
                                    'bg-green-50 text-green-700 border-green-200' :
                                    'bg-blue-50 text-blue-700 border-blue-200'"
                                x-text="activeTransaction.payment_method"></span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Action Buttons --}}
            <template x-if="activeTransaction">
                <div class="p-6 border-t border-slate-200 bg-slate-50 space-y-3 shrink-0">
                    <button @click="typeof cetakUlangStruk === 'function' ? cetakUlangStruk(activeTransaction) : null"
                        class="w-full py-3.5 px-4 rounded-xl border-2 border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Ulang Struk
                    </button>
                    <button
                        class="w-full py-3.5 px-4 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-md"
                        :class="activeTransaction.status === 'failed' || activeTransaction.status === 'Batal' ||
                            activeTransaction.status === 'return' ? 'opacity-50 cursor-not-allowed' : ''"
                        :disabled="activeTransaction.status === 'failed' || activeTransaction.status === 'Batal' ||
                            activeTransaction.status === 'return'"
                        @click="typeof batalkanTransaksi === 'function' ? batalkanTransaksi(activeTransaction) : null">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Batalkan Transaksi / Refund
                    </button>
                </div>
            </template>
        </aside>

        {{-- Mobile Menu Overlay --}}
        <div id="mobile-menu-overlay"
            class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"
            onclick="toggleMobileMenu()"></div>

        {{-- Mobile Sidebar Container --}}
        <div id="mobile-sidebar"
            class="fixed inset-y-0 left-0 w-64 bg-white z-50 transform -translate-x-full lg:hidden transition-transform duration-300 shadow-2xl flex flex-col">
            <x-sidebar role="kasir" active="penjualan" />
        </div>
    </div>

    <x-ui.custom-alert-modal />
@endsection

@push('scripts')
    @vite('resources/js/pages/kasir/penjualan.js')
@endpush
