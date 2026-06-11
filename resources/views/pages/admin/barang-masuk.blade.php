<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Barang Masuk - Niki Frozen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { dark: '#0f172a', blue: '#e0f2fe', textBlue: '#0284c7' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800 bg-[#f8fafc]" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar -->
    <x-sidebar role="admin" active="stok" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full bg-gray-50">
        
        <!-- Header -->
        <header class="px-6 lg:px-8 py-6 shrink-0 flex items-center gap-4">
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('stok.admin') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-brand-textBlue hover:border-brand-textBlue transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Input Barang Masuk</h1>
                    <p class="text-gray-500 mt-1 text-sm">Catat barang masuk dengan tanggal kadaluarsa</p>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="px-6 lg:px-8 pb-8 flex-1 overflow-y-auto">
            <div class="max-w-2xl">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl flex items-center gap-3">
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
                <form action="{{ route('barang-masuk.store') }}" method="POST" class="space-y-6">
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
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
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
                                <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" required
                                    placeholder="Masukkan jumlah barang"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all">
                            </div>

                            <!-- Tanggal Kadaluarsa (Manual Input) -->
                            <div>
                                <label for="expiry_date" class="block text-sm font-bold text-gray-700 mb-2">
                                    Tanggal Kadaluarsa <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" required readonly
                                        placeholder="Pilih tanggal kadaluarsa"
                                        class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand-textBlue transition-all bg-white cursor-pointer">
                                    <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
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
                        <button type="submit" class="px-8 py-3 bg-brand-dark text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98] flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i>
                            Simpan Barang Masuk
                        </button>
                        <a href="{{ route('stok.admin') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </main>

    @vite('resources/js/pages/admin/barang-masuk.js')
</body>
</html>
