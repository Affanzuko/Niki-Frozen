@extends('layouts.app')

@section('title', 'Input Barang Masuk - Niki Frozen')

@section('body_data')
    x-data="{ sidebarOpen: false }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar -->
    <x-sidebar role="admin" active="stok" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('pages.admin.stok') }}"
                        class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Input Barang Masuk</h1>
                        <p class="text-slate-500 font-medium">Catat barang masuk dengan tanggal kadaluarsa</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-h-0">
            <div class="max-w-2xl">
                <!-- Success Message -->
                @if (session('success'))
                    <div
                        class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-green-600"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <p class="text-sm font-bold">Terjadi kesalahan:</p>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('pages.admin.stok.barang-masuk.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Card Form -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-box text-brand-textBlue"></i>
                                Detail Barang Masuk
                            </h2>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Produk -->
                            <div>
                                <label for="product_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Pilih Produk <span class="text-red-500">*</span>
                                </label>
                                <select name="product_id" id="product_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->barcode ?? 'No barcode' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label for="quantity" class="block text-sm font-bold text-gray-700 mb-2">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" min="1"
                                    value="{{ old('quantity') }}" required placeholder="Masukkan jumlah barang"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all">
                            </div>

                            <!-- Total Harga Beli -->
                            <div x-data="{ 
                                rawPrice: '{{ old('total_purchase_price') }}',
                                get formattedPrice() {
                                    if (!this.rawPrice) return '';
                                    return parseInt(this.rawPrice).toLocaleString('id-ID');
                                },
                                updatePrice(value) {
                                    let clean = value.replace(/[^0-9]/g, '');
                                    this.rawPrice = clean;
                                }
                            }">
                                <label for="total_purchase_price_display" class="block text-sm font-bold text-gray-700 mb-2">
                                    Total Harga Beli (Total Modal) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm font-bold">Rp</span>
                                    </div>
                                    <input type="hidden" name="total_purchase_price" :value="rawPrice">
                                    <input type="text" id="total_purchase_price_display" 
                                        :value="formattedPrice"
                                        @input="updatePrice($event.target.value); $event.target.value = formattedPrice"
                                        required placeholder="0"
                                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500">Harga beli/modal keseluruhan untuk semua barang pada batch ini. Harga per satuan akan dihitung otomatis.</p>
                            </div>

                            <!-- Tanggal Kadaluarsa (Manual Input) -->
                            <div>
                                <label for="expiry_date" class="block text-sm font-bold text-gray-700 mb-2">
                                    Tanggal Kadaluarsa <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="expiry_date" id="expiry_date"
                                        value="{{ old('expiry_date') }}" required readonly
                                        placeholder="Pilih tanggal kadaluarsa"
                                        class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white cursor-pointer flatpickr-date">
                                    <i
                                        class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info text-brand-textBlue"></i>
                                    Isi tanggal kadaluarsa sesuai label kemasan produk
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-8 py-3 bg-brand-dark text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98] flex items-center gap-2">
                            Simpan Barang Masuk
                        </button>
                        <a href="{{ route('pages.admin.stok') }}"
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </main>
@endsection

@vite('resources/js/pages/admin/barang-masuk.js')
</body>

</html>
