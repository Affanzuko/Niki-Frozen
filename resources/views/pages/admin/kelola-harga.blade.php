@extends('layouts.app')

@section('title', 'Kelola Harga - Niki Frozen')

@section('body_data')
    x-data="{
        sidebarOpen: false,
        searchQuery: '',
        sortColumn: 'name',
        sortDirection: 'asc',
        init() {
            this.$watch('sortColumn', value => this.applySort());
            this.$watch('sortDirection', value => this.applySort());
            
            // Set initial sort
            setTimeout(() => this.applySort(), 100);
        },
        sortBy(col) {
            if (this.sortColumn === col) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = col;
                this.sortDirection = 'asc';
            }
        },
        applySort() {
            const container = document.getElementById('price-container');
            if (!container) return;
            const groups = Array.from(container.querySelectorAll('tbody.price-group'));
            
            let col = this.sortColumn;
            let dir = this.sortDirection === 'asc' ? 1 : -1;

            groups.sort((a, b) => {
                if (col === 'name') {
                    return a.dataset.name.localeCompare(b.dataset.name) * dir;
                } else if (col === 'category') {
                    return a.dataset.category.localeCompare(b.dataset.category) * dir;
                } else if (col === 'price') {
                    return (parseFloat(a.dataset.price) - parseFloat(b.dataset.price)) * dir;
                } else if (col === 'discount') {
                    return (parseFloat(a.dataset.discount) - parseFloat(b.dataset.discount)) * dir;
                } else if (col === 'final') {
                    return (parseFloat(a.dataset.final) - parseFloat(b.dataset.final)) * dir;
                }
                return 0;
            });
            
            groups.forEach(group => container.appendChild(group));
        }
    }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar -->
    <x-sidebar role="admin" active="kelola-harga" />

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
                        <h1 class="text-2xl font-bold text-slate-800">{{ auth()->user()->branch->name ?? 'Cabang' }}</h1>
                        <p class="text-slate-500 font-medium">Kelola Harga Produk</p>
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

                <!-- Info Banner -->
                <div
                    class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-5 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-blue-500"></i>
                    <p class="text-sm font-medium">Klik tombol <strong>Edit</strong> pada produk untuk mengubah harga jual.
                        Harga yang diperbarui akan langsung tampil di kasir.</p>
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
                                <col class="w-[38%]">
                                <col class="w-[14%]">   
                                <col class="w-[10%]">
                                <col class="w-[12%]">
                                <col class="w-[6%]">
                                <col class="w-[12%]">
                                <col class="w-[8%]">
                            </colgroup>
                            <thead>
                                <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider select-none">
                                    <th class="p-4 pl-12 cursor-pointer hover:text-slate-800 transition-colors" @click="sortBy('name')">
                                        <div class="flex items-center gap-2">
                                            Produk 
                                            <div class="flex flex-col text-[8px] opacity-50" :class="{'opacity-100 text-blue-600': sortColumn === 'name'}">
                                                <i class="fa-solid fa-chevron-up mb-[-2px]" :class="{'opacity-30': sortColumn === 'name' && sortDirection === 'desc'}"></i>
                                                <i class="fa-solid fa-chevron-down" :class="{'opacity-30': sortColumn === 'name' && sortDirection === 'asc'}"></i>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="p-4 text-left">Barcode</th>
                                    <th class="p-4 cursor-pointer hover:text-slate-800 transition-colors" @click="sortBy('category')">
                                        <div class="flex items-center gap-2">
                                            Kategori 
                                            <div class="flex flex-col text-[8px] opacity-50" :class="{'opacity-100 text-blue-600': sortColumn === 'category'}">
                                                <i class="fa-solid fa-chevron-up mb-[-2px]" :class="{'opacity-30': sortColumn === 'category' && sortDirection === 'desc'}"></i>
                                                <i class="fa-solid fa-chevron-down" :class="{'opacity-30': sortColumn === 'category' && sortDirection === 'asc'}"></i>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="p-4 cursor-pointer hover:text-slate-800 transition-colors" @click="sortBy('price')">
                                        <div class="flex items-center justify-center gap-2">
                                            Harga Jual
                                            <div class="flex flex-col text-[8px] opacity-50" :class="{'opacity-100 text-blue-600': sortColumn === 'price'}">
                                                <i class="fa-solid fa-chevron-up mb-[-2px]" :class="{'opacity-30': sortColumn === 'price' && sortDirection === 'desc'}"></i>
                                                <i class="fa-solid fa-chevron-down" :class="{'opacity-30': sortColumn === 'price' && sortDirection === 'asc'}"></i>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="p-4 cursor-pointer hover:text-slate-800 transition-colors" @click="sortBy('discount')">
                                        <div class="flex items-center justify-center gap-2">
                                            Diskon
                                            <div class="flex flex-col text-[8px] opacity-50" :class="{'opacity-100 text-blue-600': sortColumn === 'discount'}">
                                                <i class="fa-solid fa-chevron-up mb-[-2px]" :class="{'opacity-30': sortColumn === 'discount' && sortDirection === 'desc'}"></i>
                                                <i class="fa-solid fa-chevron-down" :class="{'opacity-30': sortColumn === 'discount' && sortDirection === 'asc'}"></i>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="p-4 cursor-pointer hover:text-slate-800 transition-colors" @click="sortBy('final')">
                                        <div class="flex items-center justify-center gap-2">
                                            Harga Akhir
                                            <div class="flex flex-col text-[8px] opacity-50" :class="{'opacity-100 text-blue-600': sortColumn === 'final'}">
                                                <i class="fa-solid fa-chevron-up mb-[-2px]" :class="{'opacity-30': sortColumn === 'final' && sortDirection === 'desc'}"></i>
                                                <i class="fa-solid fa-chevron-down" :class="{'opacity-30': sortColumn === 'final' && sortDirection === 'asc'}"></i>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="p-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Body Table (Scrollable) -->
                    <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                        <table id="price-container" class="w-full text-left border-collapse table-fixed min-w-[900px]">
                            <colgroup>
                                <col class="w-[38%]">
                                <col class="w-[14%]">
                                <col class="w-[10%]">
                                <col class="w-[12%]">
                                <col class="w-[6%]">
                                <col class="w-[12%]">
                                <col class="w-[8%]">
                            </colgroup>
                            @forelse ($products as $product)
                                @php
                                    $activePromotion = $product->promotions->first();
                                    $discount = $activePromotion ? $activePromotion->discount_percentage : 0;
                                    $validUntil = $activePromotion ? $activePromotion->valid_until->format('Y-m-d') : '';
                                    $finalPrice = $discount > 0 ? $product->price * (1 - $discount / 100) : $product->price;
                                @endphp
                                <tbody class="price-group divide-y divide-slate-100 text-sm font-medium"
                                    data-name="{{ strtolower(addslashes($product->name)) }}"
                                    data-category="{{ strtolower(addslashes($product->category->name ?? '')) }}"
                                    data-price="{{ $product->price }}"
                                    data-discount="{{ $discount }}"
                                    data-final="{{ $finalPrice }}"
                                    x-show="('{{ strtolower(addslashes($product->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($product->barcode ?? '')) }}'.includes(searchQuery.toLowerCase()))">
                                    <tr class="hover:bg-slate-50 transition-colors group cursor-pointer">
                                        <td class="p-4 pl-12 text-left truncate">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-20 h-15 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center shrink-0">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <i class="fa-solid fa-cube text-slate-400"></i>
                                                    @endif
                                                </div>
                                                <span
                                                    class="text-slate-700 font-bold group-hover:text-blue-600 transition-colors truncate">{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-500 tabular-nums">
                                            {{ $product->barcode ?? '-' }}</td>
                                        <td class="p-4 text-slate-600 truncate">{{ $product->category->name ?? '-' }}</td>
                                        <td class="p-4 text-center">
                                            <span class="font-semibold text-slate-700 tabular-nums">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            @if($discount > 0)
                                                <span class="font-semibold text-red-500 tabular-nums">{{ number_format($discount, 0) }}%</span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="font-semibold text-blue-600 tabular-nums">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="openEditPriceModal({{ $product->id }}, {{ $product->price }})"
                                                    class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all" title="Edit Harga">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button onclick="openDiscountModal({{ $product->id }}, {{ $discount }}, '{{ $validUntil }}')"
                                                    class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all" title="Kelola Diskon">
                                                    <i class="fa-solid fa-tags"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 py-12 text-center text-slate-400">
                                            <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                            <p class="font-medium">Belum ada produk</p>
                                        </td>
                                    </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 flex justify-between items-center bg-slate-50">
                        <span class="text-xs font-medium text-slate-500">Total {{ $products->count() }} produk</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Edit Harga Modal --}}
    <div id="edit-price-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditPriceModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <form id="edit-price-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-5 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-regular fa-pen-to-square text-blue-500"></i> Edit Harga Jual
                            </h3>
                            <button type="button" onclick="closeEditPriceModal()" class="text-slate-400 hover:text-slate-500">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="edit_price_input" class="block text-sm font-bold text-slate-700 mb-2">Harga Jual (Rp)</label>
                                <input type="number" name="price" id="edit_price_input" required min="0" step="100"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all bg-white font-bold">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" onclick="closeEditPriceModal()"
                            class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-900 transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Custom Alert Modal --}}
    <x-ui.custom-alert-modal />

    {{-- Edit Diskon Modal --}}
    <div id="edit-discount-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDiscountModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <form id="edit-discount-form" method="POST" action="">
                    @csrf
                    <div class="bg-white px-6 pt-5 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-tags text-red-500"></i> Kelola Diskon
                            </h3>
                            <button type="button" onclick="closeDiscountModal()" class="text-slate-400 hover:text-slate-500">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="edit_discount_percentage" class="block text-sm font-bold text-slate-700 mb-2">Besaran Diskon (%)</label>
                                <input type="number" name="discount_percentage" id="edit_discount_percentage" required min="0" max="100" step="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all bg-white font-bold">
                            </div>

                            <div>
                                <label for="edit_discount_valid_until" class="block text-sm font-bold text-slate-700 mb-2">Berlaku Hingga</label>
                                <div class="relative">
                                    <input type="text" name="valid_until" id="edit_discount_valid_until" required
                                        placeholder="Pilih tanggal (tanggal/bulan/tahun)"
                                        class="w-full px-4 py-3 pl-11 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all bg-white cursor-pointer flatpickr-date">
                                    <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-200">
                        <button type="button" id="btn-reset-discount" onclick="resetDiscount()"
                            class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm font-bold hover:bg-red-100 transition-colors hidden">Hapus Diskon</button>
                        <div class="flex items-center gap-3 ml-auto">
                            <button type="button" onclick="closeDiscountModal()"
                                class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-colors">Simpan Diskon</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof flatpickr !== 'undefined' && document.querySelector('.flatpickr-date')) {
            flatpickr('.flatpickr-date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                minDate: 'today',
                disableMobile: true,
            });
        }
    });

    function openEditPriceModal(id, price) {
        const modal = document.getElementById('edit-price-modal');
        const form = document.getElementById('edit-price-form');
        const priceInput = document.getElementById('edit_price_input');

        form.action = "{{ url('admin/kelola-harga') }}/" + id;
        priceInput.value = price;

        modal.classList.remove('hidden');
    }

    function closeEditPriceModal() {
        const modal = document.getElementById('edit-price-modal');
        modal.classList.add('hidden');
    }

    function openDiscountModal(id, percentage, validUntil) {
        const modal = document.getElementById('edit-discount-modal');
        const form = document.getElementById('edit-discount-form');
        const percentageInput = document.getElementById('edit_discount_percentage');
        const validUntilInput = document.getElementById('edit_discount_valid_until');
        const resetBtn = document.getElementById('btn-reset-discount');

        form.action = "{{ url('admin/kelola-harga') }}/" + id + "/discount";
        percentageInput.value = percentage;

        if (validUntilInput._flatpickr) {
            validUntilInput._flatpickr.setDate(validUntil);
        } else {
            validUntilInput.value = validUntil;
        }

        if (percentage > 0) {
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    function closeDiscountModal() {
        const modal = document.getElementById('edit-discount-modal');
        modal.classList.add('hidden');
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

    function resetDiscount() {
        window.showCustomAlert({
            title: 'Hapus Diskon?',
            message: 'Apakah Anda yakin ingin menghapus diskon produk ini? Harga akan kembali normal.',
            type: 'warning',
            showCancel: true,
            cancelText: 'Batal',
            confirmText: 'Ya, Hapus',
            confirmColorClass: 'bg-red-500 hover:bg-red-600',
            onConfirm: () => {
                const percentageInput = document.getElementById('edit_discount_percentage');
                const validUntilInput = document.getElementById('edit_discount_valid_until');

                percentageInput.value = 0;

                const today = new Date();
                // Format YYYY-MM-DD
                const todayString = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

                if (validUntilInput._flatpickr) {
                    validUntilInput._flatpickr.setDate(todayString);
                } else {
                    validUntilInput.value = todayString;
                }

                document.getElementById('edit-discount-form').submit();
            }
        });
    }
</script>
@endpush
