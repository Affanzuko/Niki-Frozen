@extends('layouts.app')

@section('title', 'Kelola Stok Admin - Niki Frozen')

@section('body_data')
    x-data="{
        stokFilter: 'Semua Barang',
        searchQuery: '',
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
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Niki Frozen - Condongcatur</h1>
                        <p class="text-slate-500 font-medium">Kelola Stok</p>
                    </div>
                </div>

                <div class="relative w-full md:w-80">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" x-model="searchQuery" placeholder="Cari produk / barcode..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium placeholder-slate-400">
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-h-0">

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-4 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-green-500"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-2 mb-6 shrink-0">
                <button @click="stokFilter = 'Semua Barang'"
                    :class="stokFilter === 'Semua Barang' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                        'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Semua Barang</button>

                <button @click="stokFilter = 'Status Kedaluwarsa'"
                    :class="stokFilter === 'Status Kedaluwarsa' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                        'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Status
                    Kedaluwarsa</button>


                <div class="flex-1"></div>

                <a href="{{ route('pages.admin.stok.barang-masuk.create') }}"
                    class="px-5 py-2 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Input Barang Masuk
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0"
                x-data="{
                    syncScroll(e) {
                        $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                    }
                }">
                <!-- Header Table (Static) -->
                <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[30%]">
                            <col class="w-[15%]">
                            <col class="w-[20%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4 py-3 pl-12">Product</th>
                                <th class="p-4 py-3 text-center">Barcode</th>
                                <th class="p-4 py-3 text-center">Stock Level</th>
                                <th class="p-4 py-3 text-center">Expiry Date</th>
                                <th class="p-4 text-center pr-4 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Body Table (Scrollable) -->
                <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[30%]">
                            <col class="w-[15%]">
                            <col class="w-[20%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
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
                                <tr class="{{ $isExpired ? 'bg-red-50/30' : '' }} hover:bg-slate-50 transition-colors group cursor-pointer"
                                    x-show="(stokFilter === 'Semua Barang' || (stokFilter === 'Status Kedaluwarsa' && {{ $isExpired || $isNearExpiry ? 'true' : 'false' }})) && ('{{ strtolower(addslashes($stock->product->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($stock->product->barcode ?? '')) }}'.includes(searchQuery.toLowerCase()))">
                                    <td class="p-4 pl-12 flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-cube text-slate-400"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors">{{ $stock->product->name }}</span>
                                            @if ($stock->product->category)
                                                <p class="text-xs text-slate-500 mt-0.5">
                                                    {{ $stock->product->category->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 text-center text-slate-500 tabular-nums">
                                        {{ $stock->product->barcode ?? '-' }}
                                    </td>
                                    <td class="p-4 text-center text-slate-900 tabular-nums">
                                        <div class="flex items-center justify-center gap-2">
                                            <span
                                                class="{{ $stock->quantity < 20 ? 'text-red-600 font-bold' : '' }}">{{ $stock->quantity }}</span>
                                            <div class="w-12 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full {{ $stockColor }} rounded-full"
                                                    style="width: {{ $stockPercent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center text-slate-500 tabular-nums">
                                        @if ($expiryDate)
                                            @if ($isExpired)
                                                <span class="text-red-600 font-bold">
                                                    <i
                                                        class="fa-solid fa-circle-exclamation mr-1"></i>{{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                </span>
                                            @elseif ($isNearExpiry)
                                                <span class="text-yellow-600 font-bold">
                                                    <i
                                                        class="fa-solid fa-triangle-exclamation mr-1"></i>{{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                </span>
                                            @else
                                                {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.admin.stok.edit', $stock->product->id) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 py-12 text-center text-slate-400">
                                        <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                        <p class="font-medium">Belum ada data stok</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 flex justify-between items-center bg-slate-50">
                    <span class="text-xs font-medium text-slate-500">Showing {{ $stocks->count() }} items</span>
                </div>
            </div>
            </div>
        </div>

    </main>
@endsection
