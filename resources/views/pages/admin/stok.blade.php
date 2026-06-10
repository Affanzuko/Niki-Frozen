<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Stok Admin - Niki Frozen</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
                            blue: '#e0f2fe',
                            textBlue: '#0284c7',
                        }
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

<body class="flex h-screen overflow-hidden text-gray-800 bg-[#f8fafc]" 
    x-data="{ 
        stokFilter: 'Semua Barang',
        activeDropdown: null,
        sidebarOpen: false,
        toggleDropdown(id) {
            this.activeDropdown = this.activeDropdown === id ? null : id;
        }
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

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
            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mb-4 items-center">
                <button @click="stokFilter = 'Semua Barang'" :class="stokFilter === 'Semua Barang' ? 'bg-brand-blue text-brand-textBlue font-semibold border border-brand-blue ring-1 ring-brand-blue' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'" class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Semua Barang</button>
                
                <button @click="stokFilter = 'Status Kedaluwarsa'" :class="stokFilter === 'Status Kedaluwarsa' ? 'bg-red-50 text-red-700 font-semibold border border-red-200 ring-1 ring-red-200' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'" class="px-4 py-2 text-sm rounded-lg shadow-sm transition-all">Status Kedaluwarsa</button>
                
                <div class="flex-1"></div>
                
                <button class="px-4 py-2 bg-brand-dark text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Input Barang Masuk
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex-1 overflow-hidden flex flex-col">
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 sticky top-0 z-10">
                                <th class="px-6 py-4 text-left">Product</th>
                                <th class="px-6 py-4">Barcode</th>
                                <th class="px-6 py-4">Stock Level</th>
                                <th class="px-6 py-4">Expiry Date</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium">
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 text-left flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                        <img src="https://images.unsplash.com/photo-1603048297172-c92544798d5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">Premium Short Ribs 500g</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 tabular-nums">8991234567890</td>
                                <td class="px-6 py-4 text-gray-900 tabular-nums">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>45 / 50</span>
                                        <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full w-[90%]"></div></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-[#e2e8f0] text-gray-700">12 Oct 2024</span>
                                </td>
                                <td class="px-6 py-4 text-right relative">
                                    <button @click.stop="toggleDropdown(1)" class="w-8 h-8 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div x-show="activeDropdown === 1" @click.away="activeDropdown = null" x-cloak x-transition class="absolute right-6 top-10 w-40 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-1 text-left">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-pen-to-square w-4 mr-1 text-gray-400"></i> Edit Detail</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-regular fa-trash-can w-4 mr-1"></i> Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 2 (Kedaluwarsa) -->
                            <tr class="hover:bg-red-50/30 transition-colors group" :class="stokFilter === 'Status Kedaluwarsa' ? 'bg-red-50/10' : ''">
                                <td class="px-6 py-4 text-left flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center shrink-0">
                                        <img src="https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Product" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-gray-900 font-bold group-hover:text-brand-textBlue transition-colors">Norwegian Salmon Fillet 200g</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 tabular-nums">8990987654321</td>
                                <td class="px-6 py-4 text-gray-900 tabular-nums">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>45 / 50</span>
                                        <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full w-[90%]"></div></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i> 26 Sep 2024
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right relative">
                                    <button @click.stop="toggleDropdown(2)" class="w-8 h-8 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div x-show="activeDropdown === 2" @click.away="activeDropdown = null" x-cloak x-transition class="absolute right-6 top-10 w-40 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-1 text-left">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-pen-to-square w-4 mr-1 text-gray-400"></i> Edit Detail</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-regular fa-trash-can w-4 mr-1"></i> Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Footer Pagination -->
                <div class="px-6 py-3 border-t border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <span class="text-xs font-medium text-gray-500">Showing 1-2 of 42 items</span>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition-all shadow-sm active:scale-95"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                        <button class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all shadow-sm active:scale-95"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>
</html>
