@extends('layouts.app')

@section('title', 'Edit Produk - Niki Frozen')

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
                        <h1 class="text-2xl font-bold text-slate-800">Edit Produk</h1>
                        <p class="text-slate-500 font-medium">Perbarui informasi detail produk</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
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
                <form action="{{ route('pages.admin.stok.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Card Form -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-box text-brand-textBlue"></i>
                                Detail Produk
                            </h2>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Nama Produk -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $product->name) }}" required placeholder="Masukkan nama produk"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Barcode (Readonly) -->
                                <div>
                                    <label for="barcode" class="block text-sm font-bold text-gray-700 mb-2">
                                        Barcode
                                    </label>
                                    <input type="text" name="barcode" id="barcode"
                                        value="{{ old('barcode', $product->barcode) }}" readonly
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none transition-all">
                                    <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-info text-brand-textBlue"></i>
                                        Barcode tidak dapat diubah
                                    </p>
                                </div>

                                <!-- Harga -->
                                <div>
                                    <label for="price" class="block text-sm font-bold text-gray-700 mb-2">
                                        Harga <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                        <input type="number" name="price" id="price" min="0" step="0.01"
                                            value="{{ old('price', $product->price) }}" required placeholder="0"
                                            class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Kategori -->
                                <div>
                                    <label for="product_category_id" class="block text-sm font-bold text-gray-700 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <select name="product_category_id" id="product_category_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Satuan -->
                                <div>
                                    <label for="product_unit_id" class="block text-sm font-bold text-gray-700 mb-2">
                                        Satuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="product_unit_id" id="product_unit_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('product_unit_id', $product->product_unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Foto Produk (FilePond) -->
                            <div>
                                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">
                                    Foto Produk
                                </label>
                                <input type="file" name="image" id="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    @if($product->image) data-existing-image="{{ asset('storage/' . $product->image) }}" @endif>
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info text-brand-textBlue"></i>
                                    Format: JPG, PNG, WebP. Maksimal 2MB. Rasio 4:3. Biarkan kosong jika tidak ingin mengubah foto.
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-8 py-3 bg-brand-dark text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98] flex items-center gap-2">
                            Simpan Perubahan
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

@vite('resources/js/pages/admin/edit-produk.js')
