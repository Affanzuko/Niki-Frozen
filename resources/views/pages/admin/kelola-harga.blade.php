@extends('layouts.app')

@section('title', 'Kelola Harga - Niki Frozen')

@section('body_data')
x-data="{
    sidebarOpen: false,
    editingProduct: null,
    editPrice: '',
    openEdit(id, price) {
        this.editingProduct = id;
        this.editPrice = price;
    },
    closeEdit() {
        this.editingProduct = null;
        this.editPrice = '';
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
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">

        <!-- Header -->
        <header class="px-6 lg:px-8 py-6 shrink-0 flex items-center gap-4">
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Niki Frozen - Condongcatur</h1>
                <p class="text-gray-500 mt-1 text-sm">Kelola Harga Produk</p>
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

            <!-- Info Banner -->
            <div
                class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-5 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-blue-500"></i>
                <p class="text-sm font-medium">Klik tombol <strong>Edit</strong> pada produk untuk mengubah harga jual.
                    Harga yang diperbarui akan langsung tampil di kasir.</p>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                <th class="px-6 py-4 text-left">Produk</th>
                                <th class="px-6 py-4">Barcode</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Satuan</th>
                                <th class="px-6 py-4">Harga Jual</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 text-left">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-cube text-gray-400"></i>
                                            </div>
                                            <span
                                                class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 tabular-nums font-mono text-xs">
                                        {{ $product->barcode ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->unit->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <!-- Normal display -->
                                        <div x-show="editingProduct !== {{ $product->id }}">
                                            <span class="font-bold text-gray-900 tabular-nums">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</span>
                                        </div>
                                        <!-- Edit mode (inline) -->
                                        <div x-show="editingProduct === {{ $product->id }}" x-cloak>
                                            <form action="{{ route('pages.admin.kelola-harga.update', $product->id) }}"
                                                method="POST" class="flex items-center gap-2 justify-center">
                                                @csrf
                                                @method('PUT')
                                                <div class="relative">
                                                    <span
                                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                                    <input type="number" name="price" x-model="editPrice"
                                                        min="0" step="100" required
                                                        class="w-36 pl-9 pr-3 py-2 border border-blue-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-brand-textBlue tabular-nums font-bold text-right">
                                                </div>
                                                <button type="submit"
                                                    class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-colors shadow-sm">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </button>
                                                <button type="button" @click="closeEdit()"
                                                    class="w-8 h-8 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-lg flex items-center justify-center transition-colors">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button x-show="editingProduct !== {{ $product->id }}"
                                            @click="openEdit({{ $product->id }}, {{ $product->price }})"
                                            class="px-3 py-1.5 bg-brand-blue text-brand-textBlue text-xs font-bold rounded-lg hover:bg-blue-200 transition-colors inline-flex items-center gap-1.5">
                                            <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                            Edit Harga
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                        <p class="font-medium">Belum ada produk</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Footer -->
                <div class="px-6 py-3 border-t border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <span class="text-xs font-medium text-gray-500">Total {{ $products->count() }} produk</span>
                </div>
            </div>
        </div>

    </main>
@endsection
