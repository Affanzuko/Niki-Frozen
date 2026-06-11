@extends('layouts.app')

@section('title', 'Kelola Stok Admin - Niki Frozen')

@section('body_data')
    x-data="{
        stokFilter: 'Semua Barang',
        activeDropdown: null,
        sidebarOpen: false,
        toggleDropdown(id) {
            this.activeDropdown = this.activeDropdown === id ? null : id;
        }
    }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="stok" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">

        <!-- Header -->
        <header class="px-6 lg:px-8 py-6 shrink-0 flex items-center gap-4">
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                <p class="text-gray-500 mt-1 text-sm">Kelola Stok</p>
            </div>
        </header>

        <!-- Content Area -->
        <div class="px-6 lg:px-8 pb-8 flex-1 flex flex-col min-h-0">

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-4 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-green-500"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mb-4 items-center">
                <button @click="stokFilter = 'Semua Barang'"
                    :class="stokFilter === 'Semua Barang' ?
                        'bg-brand-blue text-brand-textBlue font-semibold border border-brand-blue ring-1 ring-brand-blue' :
                        'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'"
                    class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Semua Barang</button>

                <button @click="stokFilter = 'Status Kedaluwarsa'"
                    :class="stokFilter === 'Status Kedaluwarsa' ?
                        'bg-red-50 text-red-700 font-semibold border border-red-200 ring-1 ring-red-200' :
                        'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'"
                    class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Status Kedaluwarsa</button>

                <div class="flex-1"></div>

                <a href="{{ route('pages.admin.barang-masuk.create') }}"
                    class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Input Barang Masuk
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0" x-data="{
                syncScroll(e) {
                    $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                }
            }">
                <!-- Header Table (Static) -->
                <div class="bg-gray-50 border-b border-gray-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                    <table class="w-full text-center border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[25%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 text-left">Product</th>
                                <th class="px-6 py-4">Barcode</th>
                                <th class="px-6 py-4">Stock Level</th>
                                <th class="px-6 py-4">Expiry Date</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Body Table (Scrollable) -->
                <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                    <table class="w-full text-center border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[25%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium">
                            @forelse ($stocks as $stock)
                                @php
                                    $expiryDate = $latestExpiries[$stock->product_id] ?? null;
                                    $isExpired = $expiryDate && \Carbon\Carbon::parse($expiryDate)->isPast();
                                    $isNearExpiry =
                                        $expiryDate &&
                                        !$isExpired &&
                                        \Carbon\Carbon::parse($expiryDate)->diffInDays(today()) <= 30;
                                    $stockPercent = $stock->quantity > 0 ? min(100, ($stock->quantity / 200) * 100) : 0;
                                    $stockColor =
                                        $stock->quantity < 20
                                            ? 'bg-red-500'
                                            : ($stock->quantity < 50
                                                ? 'bg-yellow-500'
                                                : 'bg-green-500');
                                @endphp
                                <tr class="{{ $isExpired ? 'bg-red-50/30' : '' }} hover:bg-gray-50 transition-colors group"
                                    x-show="stokFilter === 'Semua Barang' || (stokFilter === 'Status Kedaluwarsa' && {{ $isExpired || $isNearExpiry ? 'true' : 'false' }})">
                                    <td class="px-6 py-4 text-left flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-cube text-gray-400"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">{{ $stock->product->name }}</span>
                                            @if ($stock->product->category)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $stock->product->category->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 tabular-nums">{{ $stock->product->barcode ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 tabular-nums">
                                        <div class="flex items-center justify-center gap-2">
                                            <span
                                                class="{{ $stock->quantity < 20 ? 'text-red-600 font-bold' : '' }}">{{ $stock->quantity }}</span>
                                            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full {{ $stockColor }} rounded-full"
                                                    style="width: {{ $stockPercent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($expiryDate)
                                            @if ($isExpired)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                                    {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                </span>
                                            @elseif ($isNearExpiry)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                    {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">
                                                    {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs">Belum ada data</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right relative">
                                        <button @click.stop="toggleDropdown({{ $stock->id }})"
                                            class="w-8 h-8 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div x-show="activeDropdown === {{ $stock->id }}"
                                            @click.away="activeDropdown = null" x-cloak x-transition
                                            class="absolute right-6 top-10 w-40 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-1 text-left">
                                            <a href="#"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i
                                                    class="fa-regular fa-pen-to-square w-4 mr-1 text-gray-400"></i> Edit
                                                Detail</a>
                                            <a href="#"
                                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i
                                                    class="fa-regular fa-trash-can w-4 mr-1"></i> Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                        <p class="font-medium">Belum ada data stok</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Footer -->
                <div class="px-6 py-3 border-t border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <span class="text-xs font-medium text-gray-500">Showing {{ $stocks->count() }} items</span>
                </div>
            </div>
        </div>

    </main>
@endsection
