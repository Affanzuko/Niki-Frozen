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
                        <p class="text-slate-500 font-medium">Kelola Harga Produk</p>
                    </div>
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
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex-1 overflow-hidden flex flex-col min-h-0" x-data="{
                syncScroll(e) {
                    $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                }
            }">
                <!-- Header Table (Static) -->
                <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[15%]">
                            <col class="w-[10%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4 py-3 text-left">Produk</th>
                                <th class="p-4 py-3 text-left">Barcode</th>
                                <th class="p-4 py-3 text-left">Kategori</th>
                                <th class="p-4 py-3 text-center">Satuan</th>
                                <th class="p-4 py-3 text-center">Harga Jual</th>
                                <th class="p-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Body Table (Scrollable) -->
                <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                    <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[15%]">
                            <col class="w-[10%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
                            @forelse ($products as $product)
                                <tr class="hover:bg-slate-50 transition-colors group cursor-pointer">
                                    <td class="p-4 text-left truncate">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-cube text-slate-400"></i>
                                            </div>
                                            <span
                                                class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors truncate">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-slate-500 tabular-nums">
                                        {{ $product->barcode ?? '-' }}</td>
                                    <td class="p-4 text-slate-600 truncate">{{ $product->category->name ?? '-' }}</td>
                                    <td class="p-4 text-slate-600 text-center truncate">{{ $product->unit->name ?? '-' }}</td>
                                    <td class="p-4 text-center">
                                        <!-- Normal display -->
                                        <div x-show="editingProduct !== {{ $product->id }}">
                                            <span class="font-bold text-slate-900 tabular-nums">Rp
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
                                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                                                    <input type="number" name="price" x-model="editPrice"
                                                        min="0" step="100" required
                                                        class="w-36 pl-9 pr-3 py-2 border border-blue-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 tabular-nums font-bold text-right">
                                                </div>
                                                <button type="submit"
                                                    class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-colors shadow-sm">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </button>
                                                <button type="button" @click="closeEdit()"
                                                    class="w-8 h-8 bg-slate-200 hover:bg-slate-300 text-slate-600 rounded-lg flex items-center justify-center transition-colors">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button x-show="editingProduct !== {{ $product->id }}"
                                            @click="openEdit({{ $product->id }}, {{ $product->price }})"
                                            class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold rounded-lg hover:bg-blue-100 transition-colors inline-flex items-center gap-1.5">
                                            <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                            Edit Harga
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 py-12 text-center text-slate-400">
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
@endsection
