@extends('layouts.app')

@section('title', 'Stok Produk Owner - Niki Frozen')

@section('body_data')
    x-data="{
        stokFilter: sessionStorage.getItem('ownerStokFilter') || 'Semua Barang',
        searchQuery: '',
        activeDropdown: null,
        sidebarOpen: false,
        toggleDropdown(id) {
            this.activeDropdown = this.activeDropdown === id ? null : id;
        },
        init() {
            const applySort = (value) => {
                const container = document.getElementById('stocks-container');
                if (!container) return;
                const groups = Array.from(container.querySelectorAll('tbody.stock-group'));
                if (value === 'Status Kedaluwarsa') {
                    groups.sort((a, b) => parseInt(a.dataset.expiry) - parseInt(b.dataset.expiry));
                } else {
                    groups.sort((a, b) => parseInt(a.dataset.index) - parseInt(b.dataset.index));
                }
                groups.forEach(group => container.appendChild(group));
            };
            
            // Terapkan sort awal jika bukan Semua Barang
            if (this.stokFilter === 'Status Kedaluwarsa') {
                setTimeout(() => applySort(this.stokFilter), 50);
            }

            this.$watch('stokFilter', value => {
                sessionStorage.setItem('ownerStokFilter', value);
                applySort(value);
            });
        }
    }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="owner" active="stok" />

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
                        <h1 class="text-2xl font-bold text-slate-800">Stok Produk</h1>
                        <p class="text-slate-500 font-medium">Owner - {{ $selectedBranchId === 'Semua' ? 'Semua Cabang' : $branches->firstWhere('id', $selectedBranchId)->name }}</p>
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

                    <button @click="stokFilter = 'Riwayat Barang Masuk'"
                        :class="stokFilter === 'Riwayat Barang Masuk' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' :
                            'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors">Riwayat
                        Barang Masuk</button>


                    <div class="flex-1"></div>

                    <select onchange="window.location.href='?cabang='+this.value" class="px-4 py-2 border border-slate-300 rounded-xl text-sm font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Semua" {{ $selectedBranchId === 'Semua' ? 'selected' : '' }}>Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>Cabang {{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Table Stok -->
                <div x-show="stokFilter !== 'Riwayat Barang Masuk'" class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0"
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
                                    <th class="p-4 py-3 text-center">Cabang</th>
                                    <th class="p-4 py-3 text-center">Barcode</th>
                                    <th class="p-4 py-3 text-center">Stock Level</th>
                                    <th class="p-4 py-3 text-center pr-4">Expiry Date</th>
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
                                    $batches = $activeBatches->get($stock->product_id . '-' . $stock->branch_id, collect());
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
                                        <td class="p-4 text-center text-slate-500 tabular-nums font-bold">
                                            {{ $stock->branch->name }}
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
                                        <td class="p-4 text-center text-slate-500 tabular-nums pr-6">
                                            @if ($expiryDate)
                                                @if ($isExpired)
                                                    <span class="text-red-600 font-bold">
                                                        <i
                                                            class="fa-solid fa-circle-exclamation mr-1"></i>{{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                    </span>
                                                @elseif ($isNearExpiry)
                                                    <span class="text-orange-600">
                                                        {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                    </span>
                                                @else
                                                    {{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}
                                                @endif
                                            @else
                                                -
                                            @endif
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
                                                                class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm flex items-center justify-between">
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
                                                                        class="text-xs font-bold {{ $bIsExp ? 'text-red-600' : ($bIsNear ? 'text-orange-600' : 'text-slate-600') }}">
                                                                        @if ($bIsExp)
                                                                            <i
                                                                                class="fa-solid fa-circle-exclamation mr-1"></i>
                                                                        @endif
                                                                        {{ $bExp->format('d M Y') }}
                                                                    </span>
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

                <!-- Table Riwayat Barang Masuk -->
                <div x-show="stokFilter === 'Riwayat Barang Masuk'" x-cloak class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0"
                    x-data="{
                        syncScroll(e) {
                            $refs.incomingsHeaderScroll.scrollLeft = e.target.scrollLeft;
                        }
                    }">
                    <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="incomingsHeaderScroll">
                        <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                            </colgroup>
                            <thead>
                                <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="p-4 py-3 pl-8">Tanggal Masuk</th>
                                    <th class="p-4 py-3">Produk</th>
                                    <th class="p-4 py-3 text-center">Cabang</th>
                                    <th class="p-4 py-3 text-center">Jumlah</th>
                                    <th class="p-4 py-3 text-center">Harga Beli</th>
                                    <th class="p-4 py-3 text-center pr-4">Kedaluwarsa</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                        <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                            <colgroup>
                                <col class="w-[20%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                                <col class="w-[15%]">
                            </colgroup>
                            <tbody class="text-sm font-medium">
                                @forelse ($incomings as $in)
                                    <tr x-show="'{{ strtolower(addslashes($in->product->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($in->product->barcode ?? '')) }}'.includes(searchQuery.toLowerCase())" 
                                        class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="p-4 pl-8 text-slate-500">
                                            {{ \Carbon\Carbon::parse($in->created_at)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="p-4 text-slate-800 font-bold">
                                            {{ $in->product->name }}
                                        </td>
                                        <td class="p-4 text-center text-slate-500 font-bold">
                                            {{ $in->branch->name }}
                                        </td>
                                        <td class="p-4 text-center text-blue-600 font-bold">
                                            +{{ $in->quantity }} Unit
                                        </td>
                                        <td class="p-4 text-center text-slate-600 tabular-nums">
                                            Rp {{ number_format($in->purchase_price, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 text-center text-slate-500 tabular-nums pr-4">
                                            {{ \Carbon\Carbon::parse($in->expiry_date)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 py-12 text-center text-slate-400">
                                            <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                            <p class="font-medium">Belum ada riwayat barang masuk</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-200 flex justify-between items-center bg-slate-50">
                        <span class="text-xs font-medium text-slate-500">Showing {{ $incomings->count() }} items</span>
                    </div>
                </div>
            </div>
        </div>

    </main>

    {{-- Custom Alert Modal --}}
    <x-ui.custom-alert-modal />

@endsection

@push('scripts')
    <script>
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
