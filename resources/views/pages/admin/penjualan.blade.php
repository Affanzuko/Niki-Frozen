@extends('layouts.app')

@section('title', 'Penjualan Admin - Niki Frozen')

@section('body_data')
    x-data="{
        transactions: {{ Js::from($transactions ?? []) }},
        penjualanFilter: 'Semua',
        searchQuery: '',
        showTransactionDetail: false,
        activeRowId: null,
        sidebarOpen: false,
        get filteredTransactions() {
            return this.transactions.filter(t => {
                let matchesFilter = true;
                if (this.penjualanFilter === 'Berhasil') {
                    matchesFilter = t.status === 'success' || t.status === 'Selesai';
                } else if (this.penjualanFilter === 'Batal') {
                    matchesFilter = t.status === 'failed' || t.status === 'Batal' || t.status === 'refund';
                }
    
                let matchesSearch = true;
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    const ref = (t.transaction_code || '').toLowerCase();
                    const cashier = (t.cashier_session?.user?.name || '').toLowerCase();
                    matchesSearch = ref.includes(q) || cashier.includes(q);
                }
    
                return matchesFilter && matchesSearch;
            });
        },
        get activeTransaction() {
            return this.transactions.find(t => t.id === this.activeRowId);
        },
        openDetail(id) {
            this.showTransactionDetail = true;
            this.activeRowId = id;
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
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="penjualan" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">

        <!-- Header -->
        <header class="px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between sm:items-center shrink-0 gap-4">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                    <p class="text-gray-500 mt-1 text-sm">Riwayat Penjualan</p>
                </div>
            </div>
            <div>
                <div class="relative group">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-brand-textBlue transition-colors"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari No. Referensi / Nama Kasir"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-textBlue w-full sm:w-[300px] transition-all">
                </div>
            </div>
        </header>

        <!-- Content Area (Split layout) -->
        <div class="px-6 lg:px-8 pb-8 flex flex-col lg:flex-row gap-6 flex-1 min-h-0">
            <!-- Left: Table -->
            <div
                class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300">
                <!-- Filters -->
                <div class="p-4 flex gap-2 border-b border-gray-100 bg-white overflow-x-auto no-scrollbar">
                    <button @click="penjualanFilter = 'Semua'"
                        :class="penjualanFilter === 'Semua' ?
                            'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' :
                            'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                        class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Semua</button>
                    <button @click="penjualanFilter = 'Berhasil'"
                        :class="penjualanFilter === 'Berhasil' ?
                            'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' :
                            'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                        class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Berhasil</button>
                    <button @click="penjualanFilter = 'Batal'"
                        :class="penjualanFilter === 'Batal' ?
                            'bg-brand-blue text-brand-textBlue font-semibold border-brand-blue' :
                            'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                        class="px-5 py-2 text-sm rounded-lg border transition-all whitespace-nowrap">Dibatalkan/Refund</button>
                </div>
                <!-- Table -->
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-center border-collapse min-w-[600px]">
                        <thead>
                            <tr
                                class="bg-gray-50/80 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">No. Referensi</th>
                                <th class="px-6 py-4">Total Belanja</th>
                                <th class="px-6 py-4">Metode</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="trx in filteredTransactions" :key="trx.id">
                                <tr @click="openDetail(trx.id)"
                                    :class="activeRowId === trx.id ? 'bg-blue-50/50' : 'hover:bg-gray-50'"
                                    class="transition-colors cursor-pointer group">
                                    <td class="px-6 py-4 text-gray-500 font-medium" x-text="formatDate(trx.created_at)">
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs"
                                        x-text="trx.transaction_code"></td>
                                    <td class="px-6 py-4 font-bold text-gray-900 tabular-nums"
                                        x-text="formatCurrency(trx.total_price)"></td>
                                    <td class="px-6 py-4 text-gray-500" x-text="trx.payment_method"></td>
                                    <td class="px-6 py-4">
                                        <template x-if="trx.status === 'success' || trx.status === 'Selesai'">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                                        </template>
                                        <template x-if="trx.status !== 'success' && trx.status !== 'Selesai'">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200"
                                                x-text="trx.status"></span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredTransactions.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada transaksi ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Detail Panel -->
            <div x-show="showTransactionDetail && activeTransaction" x-cloak
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-8 opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                class="w-full lg:w-[380px] bg-white rounded-xl border border-gray-200 shadow-xl flex flex-col shrink-0 lg:static absolute inset-y-8 right-8 z-20">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-xl">
                    <h3 class="font-bold text-gray-900">Detail Transaksi</h3>
                    <button @click="showTransactionDetail = false; activeRowId = null"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 flex-1 overflow-y-auto">
                    <div class="text-center mb-8 bg-gray-50 rounded-xl py-4 border border-gray-100">
                        <h2 class="text-2xl font-bold text-brand-dark font-mono tracking-tight"
                            x-text="activeTransaction.transaction_code"></h2>
                        <p class="text-xs text-gray-500 mt-1 font-medium"><i class="fa-regular fa-clock mr-1"></i> <span
                                x-text="formatDate(activeTransaction.created_at)"></span> &nbsp;|&nbsp; <i
                                class="fa-regular fa-user mr-1"></i> Kasir: <span
                                x-text="activeTransaction.cashier_session?.user?.name || '-'"></span></p>
                    </div>

                    <!-- Items -->
                    <div class="space-y-5 mb-6">
                        <template x-for="item in activeTransaction.items" :key="item.id">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm text-gray-900 leading-tight" x-text="item.product?.name">
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium"
                                        x-text="item.quantity + ' x ' + formatCurrency(item.unit_price)"></p>
                                </div>
                                <p class="font-bold text-sm text-gray-900 tabular-nums"
                                    x-text="formatCurrency(item.quantity * item.unit_price)"></p>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-5 mb-2">
                        <div class="flex justify-between text-sm mb-3">
                            <span class="text-gray-500 font-medium">Subtotal</span>
                            <span class="text-gray-900 font-semibold tabular-nums"
                                x-text="formatCurrency(activeTransaction.total_price / 1.11)"></span>
                        </div>
                        <div class="flex justify-between text-sm mb-5">
                            <span class="text-gray-500 font-medium">Pajak (11%)</span>
                            <span class="text-gray-900 font-semibold tabular-nums"
                                x-text="formatCurrency(activeTransaction.total_price - (activeTransaction.total_price / 1.11))"></span>
                        </div>
                        <div
                            class="flex justify-between items-center mb-6 bg-blue-50 p-3 rounded-lg border border-brand-blue">
                            <span class="font-bold text-lg text-brand-textBlue">Total</span>
                            <span class="font-bold text-2xl text-brand-textBlue tabular-nums"
                                x-text="formatCurrency(activeTransaction.total_price)"></span>
                        </div>
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-gray-500 font-medium">Metode Pembayaran</span>
                            <span class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded border border-gray-200"
                                x-text="activeTransaction.payment_method"></span>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="p-6 border-t border-gray-100 flex flex-col gap-3 bg-gray-50/50 rounded-b-xl">
                    <button
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-brand-dark rounded-lg font-bold transition-all shadow-sm active:scale-95">
                        <i class="fa-solid fa-receipt text-gray-500"></i>
                        Cetak Ulang Struk
                    </button>
                    <button
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#b91c1c] hover:bg-red-800 text-white rounded-lg font-bold transition-all shadow-sm active:scale-95">
                        <i class="fa-regular fa-circle-xmark"></i>
                        Batalkan Transaksi / Refund
                    </button>
                </div>
            </div>
        </div>

    </main>
@endsection
