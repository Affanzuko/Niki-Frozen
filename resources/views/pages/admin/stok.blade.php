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
        },
        init() {
            this.$watch('stokFilter', value => {
                const container = document.getElementById('stocks-container');
                if (!container) return;
                const groups = Array.from(container.querySelectorAll('tbody.stock-group'));
                if (value === 'Status Kedaluwarsa') {
                    groups.sort((a, b) => parseInt(a.dataset.expiry) - parseInt(b.dataset.expiry));
                } else {
                    groups.sort((a, b) => parseInt(a.dataset.index) - parseInt(b.dataset.index));
                }
                groups.forEach(group => container.appendChild(group));
            });
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
        <div
            class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">

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
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Semua
                        Barang</button>

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
                        <table id="stocks-container" class="w-full text-left border-collapse table-fixed min-w-[900px]">
                            <colgroup>
                                <col class="w-[30%]">
                                <col class="w-[15%]">
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                            </colgroup>
                            @forelse ($stocks as $stock)
                                @php
                                    $batches = $activeBatches->get($stock->product_id, collect());
                                    $earliestBatch = $batches->first();
                                    $expiryDate = $earliestBatch ? $earliestBatch->expiry_date : null;
                                    $isExpired = $expiryDate && \Carbon\Carbon::parse($expiryDate)->isPast();
                                    $isNearExpiry =
                                        $expiryDate &&
                                        !$isExpired &&
                                        today()->diffInDays(\Carbon\Carbon::parse($expiryDate), false) <= 7;
                                    $stockPercent = $stock->quantity > 0 ? min(100, ($stock->quantity / 200) * 100) : 0;
                                    $stockColor =
                                        $stock->quantity < 20
                                            ? 'bg-red-500'
                                            : ($stock->quantity < 50
                                                ? 'bg-yellow-500'
                                                : 'bg-green-500');
                                @endphp
                                <tbody class="stock-group border-b border-slate-100 text-sm font-medium"
                                    data-index="{{ $loop->index }}"
                                    data-expiry="{{ $expiryDate ? \Carbon\Carbon::parse($expiryDate)->timestamp : 9999999999 }}"
                                    x-data="{ expanded: false }"
                                    x-show="(stokFilter === 'Semua Barang' || (stokFilter === 'Status Kedaluwarsa' && {{ $isExpired || $isNearExpiry ? 'true' : 'false' }})) && ('{{ strtolower(addslashes($stock->product->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($stock->product->barcode ?? '')) }}'.includes(searchQuery.toLowerCase()))">
                                    <tr class="stock-row {{ $isExpired ? 'bg-red-50/30' : '' }} hover:bg-slate-50 transition-colors {{ $batches->count() > 0 ? 'group cursor-pointer' : '' }}"
                                        @if ($batches->count() > 0) @click="expanded = !expanded" @endif>
                                        <td class="p-4 pl-12 flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center shrink-0">
                                                <i
                                                    class="fa-solid fa-cube text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors">{{ $stock->product->name }}</span>
                                                @if ($stock->product->category)
                                                    <p class="text-xs text-slate-500 mt-0.5">
                                                        {{ $stock->product->category->name }}</p>
                                                @endif
                                                @if ($batches->count() > 0)
                                                    <span
                                                        class="inline-block mt-1 text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold"><i
                                                            class="fa-solid fa-layer-group mr-1"></i>
                                                        {{ $batches->count() }}
                                                        Batch{{ $batches->count() > 1 ? 'es' : '' }}</span>
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
                                                    <span class="text-red-500">
                                                        {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
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
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                        </path>
                                                    </svg></a>
                                                <button type="button"
                                                    onclick="confirmDeleteProduct({{ $stock->product->id }}, '{{ addslashes($stock->product->name) }}')"
                                                    class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                        class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg></button>
                                                <form id="delete-form-{{ $stock->product->id }}"
                                                    action="{{ route('pages.admin.stok.destroy', $stock->product->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Accordion Details -->
                                    @if ($batches->count() > 0)
                                        <tr x-show="expanded" class="bg-slate-50">
                                            <td colspan="5" class="p-0 border-t border-slate-100">
                                                <div class="pl-[4.5rem] pr-6 py-5 shadow-inner">
                                                    <h4
                                                        class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">
                                                        <i class="fa-solid fa-layer-group mr-2"></i> Rincian Batch (FEFO)
                                                    </h4>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                        @forelse($batches as $idx => $batch)
                                                            <div
                                                                class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm flex flex-col gap-2 relative group/batch">
                                                                <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <p class="text-xs font-bold text-slate-800">Batch
                                                                            {{ $idx + 1 }}</p>
                                                                        <p class="text-[10px] text-slate-500 mt-0.5">Jumlah
                                                                            awal:
                                                                            <span
                                                                                class="font-bold text-slate-700">{{ $batch->quantity }}
                                                                                pcs</span>
                                                                        </p>
                                                                        <p class="text-[10px] text-slate-500 mt-0.5">Sisa:
                                                                            <span
                                                                                class="font-bold text-slate-700">{{ $batch->current_quantity }}
                                                                                pcs</span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        @php
                                                                            $bExp = \Carbon\Carbon::parse(
                                                                                $batch->expiry_date,
                                                                            );
                                                                            $bIsExp = $bExp->isPast();
                                                                            $bIsNear =
                                                                                !$bIsExp &&
                                                                                today()->diffInDays($bExp, false) <= 7;
                                                                        @endphp
                                                                        <span
                                                                            class="text-xs font-bold {{ $bIsExp ? 'text-red-600' : ($bIsNear ? 'text-red-500' : 'text-slate-600') }}">
                                                                            @if ($bIsExp)
                                                                                <i
                                                                                    class="fa-solid fa-circle-exclamation mr-1"></i>
                                                                            @endif
                                                                            {{ $bExp->format('d M Y') }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                    <div class="flex items-center justify-end gap-2 mt-1 pt-2 border-t border-slate-100">
                                                                        @php
                                                                            $activePromo = \App\Models\Promotion::where('stock_incoming_id', $batch->id)->where('is_active', true)->first();
                                                                        @endphp
                                                                        
                                                                        @if ($activePromo)
                                                                            <form action="{{ route('pages.admin.promotions.destroy', $activePromo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus promo untuk batch ini?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="p-1.5 px-2 text-[10px] font-bold text-white bg-red-500 hover:bg-red-600 rounded transition-colors" title="Hentikan Promo">
                                                                                    <i class="fa-solid fa-ban"></i> Hentikan Promo
                                                                                </button>
                                                                            </form>
                                                                        @elseif ($bIsNear)
                                                                            <button type="button"
                                                                                onclick="openPromoModal({{ $stock->product->id }}, {{ $batch->id }}, '{{ addslashes($stock->product->name) }} (Batch {{ $idx + 1 }})', {{ $batch->current_quantity }}, '{{ $bExp->format('Y-m-d') }}')"
                                                                                class="p-1.5 px-2 text-[10px] font-bold text-red-500 border border-red-200 hover:bg-red-50 rounded transition-colors"
                                                                                title="Beri Promo/Diskon">
                                                                                <i class="fa-solid fa-tags"></i> Promo
                                                                            </button>
                                                                        @endif
                                                                    <button type="button"
                                                                        onclick="openEditBatchModal({{ $batch->id }}, {{ $batch->current_quantity }}, '{{ $bExp->format('Y-m-d') }}')"
                                                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                                                        title="Edit Batch">
                                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        onclick="confirmDeleteBatch({{ $batch->id }}, {{ $idx + 1 }})"
                                                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                                        title="Hapus Batch">
                                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                                    </button>
                                                                    <form id="delete-batch-form-{{ $batch->id }}"
                                                                        action="{{ route('pages.admin.stok.batch.destroy', $batch->id) }}"
                                                                        method="POST" class="hidden">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-xs text-slate-500 italic">Belum ada batch
                                                                aktif.</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            @empty
                                <tbody id="stocks-tbody">
                                    <tr>
                                        <td colspan="5" class="p-4 py-12 text-center text-slate-400">
                                            <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                            <p class="font-medium">Belum ada data stok</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
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

    {{-- Custom Alert Modal --}}
    <x-ui.custom-alert-modal />

    {{-- Edit Batch Modal --}}
    <div id="edit-batch-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditBatchModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <form id="edit-batch-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-5 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-500"></i> Edit Batch
                            </h3>
                            <button type="button" onclick="closeEditBatchModal()"
                                class="text-slate-400 hover:text-slate-500">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="edit_batch_qty" class="block text-sm font-bold text-slate-700 mb-2">Sisa Stok
                                    (pcs)</label>
                                <input type="number" name="current_quantity" id="edit_batch_qty" required
                                    min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white">
                            </div>

                            <div>
                                <label for="edit_batch_expiry" class="block text-sm font-bold text-slate-700 mb-2">Tanggal
                                    Kadaluarsa</label>
                                <div class="relative">
                                    <input type="text" name="expiry_date" id="edit_batch_expiry" required
                                        placeholder="Pilih tanggal kadaluarsa"
                                        class="w-full px-4 py-3 pl-11 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white cursor-pointer flatpickr-date">
                                    <i
                                        class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" onclick="closeEditBatchModal()"
                            class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-900 transition-colors">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Promo Modal --}}
    <div id="promo-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closePromoModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <form id="promo-form" method="POST" action="{{ route('pages.admin.promotions.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="promo_product_id">
                    <input type="hidden" name="stock_incoming_id" id="promo_stock_incoming_id">
                    <div class="bg-white px-6 pt-5 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-tags text-red-500"></i> Beri Promo Produk
                            </h3>
                            <button type="button" onclick="closePromoModal()"
                                class="text-slate-400 hover:text-slate-500">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                        
                        <p class="text-sm text-slate-600 mb-4">Produk: <span id="promo_product_name" class="font-bold"></span></p>

                        <div class="space-y-4">
                            <div>
                                <label for="promo_discount" class="block text-sm font-bold text-slate-700 mb-2">Diskon (%)</label>
                                <input type="number" name="discount_percentage" id="promo_discount" required
                                    min="1" max="100" placeholder="Contoh: 20"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all bg-white">
                            </div>
                            
                            <div>
                                <label for="promo_quota" class="block text-sm font-bold text-slate-700 mb-2">Kuota Promo (pcs)</label>
                                <input type="number" name="quota" id="promo_quota" required readonly
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm bg-slate-100 text-slate-500 font-bold cursor-not-allowed focus:outline-none transition-all">
                                <p class="text-[10px] text-slate-500 mt-1">Sistem otomatis mendeteksi jumlah stok pada batch ini.</p>
                            </div>

                            <div>
                                <label for="promo_valid_until" class="block text-sm font-bold text-slate-700 mb-2">Berlaku Sampai</label>
                                <div class="relative">
                                    <input type="text" name="valid_until" id="promo_valid_until" required
                                        placeholder="Pilih tanggal"
                                        class="w-full px-4 py-3 pl-11 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all bg-white cursor-pointer flatpickr-date">
                                    <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" onclick="closePromoModal()"
                            class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold hover:bg-red-500 transition-colors">Terapkan Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function confirmDeleteProduct(id, name) {
            window.showCustomAlert({
                title: 'Hapus Produk?',
                message: `Apakah Anda yakin ingin menghapus produk "${name}"? Tindakan ini tidak dapat dibatalkan.`,
                type: 'error',
                showCancel: true,
                cancelText: 'Batal',
                confirmText: 'Ya, Hapus',
                confirmColorClass: 'bg-red-500 hover:bg-red-600',
                onConfirm: () => {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function openEditBatchModal(batchId, currentQty, expiryDate) {
            const modal = document.getElementById('edit-batch-modal');
            const form = document.getElementById('edit-batch-form');
            const qtyInput = document.getElementById('edit_batch_qty');
            const expiryInput = document.getElementById('edit_batch_expiry');

            form.action = "{{ url('admin/stok/batch') }}/" + batchId;
            qtyInput.value = currentQty;

            if (expiryInput._flatpickr) {
                expiryInput._flatpickr.setDate(expiryDate);
            } else {
                expiryInput.value = expiryDate;
            }

            modal.classList.remove('hidden');
        }

        function closeEditBatchModal() {
            const modal = document.getElementById('edit-batch-modal');
            modal.classList.add('hidden');
        }

        function openPromoModal(productId, batchId, productName, currentQty, expiryDate) {
            const modal = document.getElementById('promo-modal');
            document.getElementById('promo_product_id').value = productId;
            document.getElementById('promo_stock_incoming_id').value = batchId;
            document.getElementById('promo_product_name').textContent = productName;
            document.getElementById('promo_quota').value = currentQty;
            
            const expiryInput = document.getElementById('promo_valid_until');
            if (expiryInput._flatpickr) {
                expiryInput._flatpickr.setDate(expiryDate);
            } else {
                expiryInput.value = expiryDate;
            }

            modal.classList.remove('hidden');
        }

        function closePromoModal() {
            document.getElementById('promo-modal').classList.add('hidden');
        }

        function confirmDeleteBatch(id, batchNumber) {
            window.showCustomAlert({
                title: 'Hapus Batch?',
                message: `Apakah Anda yakin ingin menghapus Batch ${batchNumber}? Tindakan ini akan mengurangi total stok produk terkait.`,
                type: 'error',
                showCancel: true,
                cancelText: 'Batal',
                confirmText: 'Ya, Hapus',
                confirmColorClass: 'bg-red-500 hover:bg-red-600',
                onConfirm: () => {
                    document.getElementById('delete-batch-form-' + id).submit();
                }
            });
        }

        // Custom Alert Logic
        window.showCustomAlert = function(options) {
            const modal = document.getElementById('custom-alert-modal');
            if (!modal) return;
            const titleEl = document.getElementById('custom-alert-title');
            const messageEl = document.getElementById('custom-alert-message');
            const iconContainer = document.getElementById('custom-alert-icon-container');
            const buttonsContainer = document.getElementById('custom-alert-buttons');

            titleEl.textContent = options.title || 'Perhatian';
            messageEl.textContent = options.message || '';

            if (options.type === 'warning') {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-amber-100 text-amber-500';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else if (options.type === 'error') {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-red-100 text-red-500';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-blue-100 text-blue-500';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            buttonsContainer.innerHTML = '';

            if (options.showCancel) {
                const cancelBtn = document.createElement('button');
                cancelBtn.className =
                    'flex-1 py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-[13px] hover:bg-slate-50 transition-colors active:scale-95';
                cancelBtn.textContent = options.cancelText || 'Batal';
                cancelBtn.onclick = () => {
                    window.closeCustomAlert();
                    if (options.onCancel) options.onCancel();
                };
                buttonsContainer.appendChild(cancelBtn);
            }

            const confirmBtn = document.createElement('button');
            confirmBtn.className =
                `flex-1 py-3 px-4 rounded-xl text-white font-bold text-[13px] transition-colors active:scale-95 shadow-md ${options.confirmColorClass || 'bg-[#0f172a] hover:bg-slate-800'}`;
            confirmBtn.textContent = options.confirmText || 'Oke';
            confirmBtn.onclick = () => {
                window.closeCustomAlert();
                if (options.onConfirm) options.onConfirm();
            };
            buttonsContainer.appendChild(confirmBtn);

            modal.classList.remove('hidden');
        };

        window.closeCustomAlert = function() {
            const modal = document.getElementById('custom-alert-modal');
            if (modal) modal.classList.add('hidden');
        };
    </script>
@endpush

@vite('resources/js/pages/admin/barang-masuk.js')
