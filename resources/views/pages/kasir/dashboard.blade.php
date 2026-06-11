@extends('layouts.app')

@section('title', 'Kasir POS - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Mobile Header --}}
    <div class="lg:hidden bg-[#0f172a] text-white p-4 flex items-center justify-between z-20 shadow-md shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-none">Niki Frozen</h1>
                <p class="text-[10px] text-slate-300 mt-1 uppercase tracking-wider">Kasir POS</p>
            </div>
        </div>
        <button onclick="toggleMobileCart()"
            class="relative p-2.5 bg-white/10 rounded-lg hover:bg-white/20 transition-colors active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <span id="mobile-cart-badge"
                class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-[#0f172a] shadow-sm">0</span>
        </button>
    </div>

    {{-- Sidebar (Dynamic Role) --}}
    <x-sidebar role="kasir" active="dashboard" />

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">

        {{-- Middle Column: Products --}}
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-4 lg:p-6 relative">

            {{-- Header / Search --}}
            <div class="mb-4 lg:mb-6 flex-shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="search-produk" placeholder="Search products..."
                        class="w-full h-12 pl-12 pr-4 text-sm font-medium bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm"
                        autofocus>
                </div>

                {{-- Filter Pills --}}
                <div class="flex items-center gap-2 lg:gap-3 mt-4 overflow-x-auto no-scrollbar pb-1" id="category-filters">
                    <button onclick="filterCategory('all')"
                        class="category-btn px-4 lg:px-5 py-2 rounded-lg bg-[#0f172a] text-white text-xs font-semibold whitespace-nowrap shadow-sm"
                        data-category="all">Semua</button>
                    @php
                        $lainnyaCategory = null;
                    @endphp
                    @foreach ($categories as $category)
                        @if (strtolower($category->name) === 'lainnya')
                            @php $lainnyaCategory = $category; @endphp
                            @continue
                        @endif
                        <button onclick="filterCategory('{{ strtolower($category->name) }}')"
                            class="category-btn px-4 lg:px-5 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-semibold whitespace-nowrap"
                            data-category="{{ strtolower($category->name) }}">{{ $category->name }}</button>
                    @endforeach
                    @if ($lainnyaCategory)
                        <button onclick="filterCategory('{{ strtolower($lainnyaCategory->name) }}')"
                            class="category-btn px-4 lg:px-5 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-semibold whitespace-nowrap"
                            data-category="{{ strtolower($lainnyaCategory->name) }}">{{ $lainnyaCategory->name }}</button>
                    @endif
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto pr-1 lg:pr-2 no-scrollbar pb-24 lg:pb-0">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4" id="product-container">
                    @foreach ($products as $product)
                        <div onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                            data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode }}"
                            data-category="{{ $product->category ? strtolower($product->category->name) : '' }}"
                            class="product-card group cursor-pointer bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col active:scale-95">

                            {{-- Product Image Placeholder --}}
                            <div
                                class="w-full h-28 lg:h-36 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-tr from-slate-200 to-slate-50 opacity-50"></div>
                                <span
                                    class="text-4xl lg:text-5xl relative z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-md">❄️</span>
                            </div>

                            <div class="p-3 flex flex-col flex-1">
                                <h3 class="text-[13px] lg:text-sm font-bold text-slate-800 leading-tight line-clamp-2 mb-2">
                                    {{ $product->name }}</h3>

                                <div
                                    class="mt-auto flex flex-col lg:flex-row items-start lg:items-end justify-between gap-1">
                                    <p class="text-sm font-bold text-blue-700 lg:text-slate-900">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <span
                                        class="text-[9px] lg:text-[10px] font-bold px-2 py-0.5 rounded-md {{ $product->id % 3 == 0 ? 'bg-red-100 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                        {{ $product->id % 3 == 0 ? '3 in stock' : '12 in stock' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Mobile Overlay for Cart --}}
        <div id="mobile-cart-overlay" onclick="toggleMobileCart()"
            class="lg:hidden fixed inset-0 bg-slate-900/60 z-30 hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm">
        </div>

        {{-- Right Column: Cart --}}
        <div id="cart-panel"
            class="fixed inset-y-0 right-0 w-[85%] max-w-[400px] lg:w-[400px] bg-white border-l border-slate-200 flex flex-col flex-shrink-0 z-40 transform translate-x-full lg:translate-x-0 lg:relative lg:z-10 lg:shadow-[-4px_0_15px_-3px_rgba(0,0,0,0.05)] transition-transform duration-300 ease-in-out shadow-2xl">

            {{-- Cart Header --}}
            <div class="p-4 lg:p-5 border-b border-slate-200 flex items-center justify-between bg-white">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Keranjang <span id="cart-item-count"
                        class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full ml-1">0</span>
                </h2>
                <div class="flex items-center gap-1">
                    <button onclick="clearCart()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"
                        title="Kosongkan Keranjang">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                    <button onclick="toggleMobileCart()"
                        class="lg:hidden text-slate-400 hover:bg-slate-100 p-2 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Cart Items --}}
            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50">
                <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-3">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-sm font-medium">Keranjang masih kosong</p>
                </div>
            </div>

            {{-- Cart Summary & Payment --}}
            <div class="p-4 lg:p-5 bg-white border-t border-slate-200">
                <div class="space-y-1.5 lg:space-y-2 mb-3 lg:mb-4">
                    <div class="flex justify-between text-[13px] lg:text-sm text-slate-500 font-medium">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-[13px] lg:text-sm text-slate-500 font-medium">
                        <span>Tax (11%)</span>
                        <span id="cart-tax">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4 lg:mb-6 pt-3 border-t border-slate-100">
                    <span class="text-base font-bold text-slate-800">Total</span>
                    <span id="cart-total" class="text-xl lg:text-2xl font-black text-[#0f172a]">Rp 0</span>
                </div>

                <div class="grid grid-cols-2 gap-2 lg:gap-3">
                    <button onclick="openCashModal()"
                        class="w-full py-3 lg:py-3.5 rounded-xl bg-[#1e293b] text-white font-bold text-sm hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 active:scale-95">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        CASH
                    </button>
                    <button onclick="processPayment('qris')"
                        class="w-full py-3 lg:py-3.5 rounded-xl bg-[#0284c7] text-white font-bold text-sm hover:bg-sky-700 transition-colors flex items-center justify-center gap-2 active:scale-95">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        QRIS
                    </button>
                </div>
            </div>
        </div>

    </main>

    {{-- Cash Payment Modal --}}
    <div id="cash-modal" class="fixed inset-0 z-[60] hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCashModal()">
        </div>

        {{-- Modal Dialog --}}
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-sm flex flex-col overflow-hidden animate-in zoom-in duration-200 mt-[-5%] lg:mt-0 max-h-[90vh]">

                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Pembayaran Tunai (Cash)
                    </h3>
                    <button onclick="closeCashModal()"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1 rounded-md transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-5 overflow-y-auto flex-1 bg-white">

                    {{-- Total Tagihan Box --}}
                    <div
                        class="bg-slate-50 rounded-xl p-3 lg:p-4 flex items-center justify-between mb-4 lg:mb-5 border border-slate-200">
                        <span class="text-[11px] lg:text-xs font-semibold text-slate-500 uppercase tracking-wide">Total
                            Tagihan</span>
                        <span id="modal-total-tagihan" class="text-lg lg:text-xl font-black text-slate-900">Rp 0</span>
                    </div>

                    {{-- Uang Diterima Input --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                            <input type="tel" inputmode="numeric" id="modal-uang-diterima"
                                oninput="handleUangDiterimaInput(event)"
                                class="w-full text-right text-2xl font-bold text-[#0f172a] bg-white border-2 border-slate-300 rounded-xl py-3 pl-12 pr-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all shadow-sm">
                        </div>
                    </div>

                    {{-- Numpad --}}
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <button onclick="numpadPress('1')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">1</button>
                        <button onclick="numpadPress('2')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">2</button>
                        <button onclick="numpadPress('3')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">3</button>
                        <button onclick="numpadPress('4')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">4</button>
                        <button onclick="numpadPress('5')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">5</button>
                        <button onclick="numpadPress('6')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">6</button>
                        <button onclick="numpadPress('7')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">7</button>
                        <button onclick="numpadPress('8')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">8</button>
                        <button onclick="numpadPress('9')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">9</button>
                        <button onclick="numpadPress('0')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">0</button>
                        <button onclick="numpadPress('000')"
                            class="py-2.5 lg:py-3 bg-white border border-slate-200 rounded-lg text-lg font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-sm">000</button>
                        <button onclick="numpadBackspace()"
                            class="py-2.5 lg:py-3 bg-red-50 text-red-600 border border-red-200 rounded-lg text-lg font-bold hover:bg-red-100 hover:border-red-300 active:scale-95 flex items-center justify-center transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    {{-- Quick Amounts --}}
                    <div id="modal-quick-amounts" class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                        <!-- Populated dynamically -->
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 p-4 lg:p-5 border-t border-slate-200 shrink-0">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <span class="text-sm font-semibold text-slate-500">Kembalian</span>
                        <span id="modal-kembalian" class="text-xl font-bold text-[#0f172a]">Rp 0</span>
                    </div>
                    <button onclick="submitCashPayment()" id="btn-submit-cash" disabled
                        class="w-full bg-[#0f172a] disabled:bg-slate-400 disabled:cursor-not-allowed hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm shadow-lg active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Selesaikan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <x-ui.success-modal />

    {{-- Custom Alert Modal --}}
    <x-ui.custom-alert-modal />

@endsection

@push('head_scripts')
    {{-- Midtrans Snap --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@push('scripts')
    @vite('resources/js/pages/kasir/dashboard.js')
@endpush
