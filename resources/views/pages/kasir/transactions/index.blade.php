<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Riwayat Penjualan - Niki Frozen</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 h-screen overflow-hidden text-slate-800">
    <div class="flex h-full w-full relative">
        
        {{-- Sidebar Component --}}
        <x-sidebar role="kasir" active="penjualan" />

        {{-- Mobile Header --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 z-40 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <button onclick="toggleMobileMenu()" class="p-2 -ml-2 rounded-lg hover:bg-slate-100 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="font-bold text-lg text-slate-800">Riwayat Penjualan</h1>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="flex-1 h-full flex flex-col pt-16 lg:pt-0 overflow-hidden relative">
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 no-scrollbar bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">
                
                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Niki Frozen - Condongcatur</h1>
                        <p class="text-slate-500 font-medium">Riwayat Penjualan</p>
                    </div>
                    <div class="relative w-full md:w-80">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" placeholder="Cari No. Referensi / Nama Kasir" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium placeholder-slate-400">
                    </div>
                </div>

                {{-- Filters --}}
                <div class="flex flex-wrap items-center gap-2 mb-6">
                    <button class="px-5 py-2 bg-[#dbeafe] text-[#1e40af] border border-[#bfdbfe] rounded-xl text-sm font-bold shadow-sm">Semua</button>
                    <button class="px-5 py-2 bg-white text-slate-600 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 shadow-sm">Berhasil</button>
                    <button class="px-5 py-2 bg-white text-slate-600 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 shadow-sm">Dibatalkan/Refund</button>
                </div>

                {{-- Table --}}
                <div class="w-full border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase tracking-wider font-bold">
                                    <th class="p-4 py-3">Waktu</th>
                                    <th class="p-4 py-3">No. Referensi</th>
                                    <th class="p-4 py-3">Total Belanja</th>
                                    <th class="p-4 py-3">Metode</th>
                                    <th class="p-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($transactions as $index => $trx)
                                <tr class="transaction-row hover:bg-slate-50 cursor-pointer transition-colors {{ $index === 0 ? 'bg-blue-100' : '' }}" onclick="openDetailSidebar(this, '{{ $trx['no_ref'] }}', '{{ $trx['status'] }}')">
                                    <td class="p-4 font-medium text-slate-700">{{ $trx['waktu'] }}</td>
                                    <td class="p-4 font-semibold text-blue-600">{{ $trx['no_ref'] }}</td>
                                    <td class="p-4 font-bold text-slate-900">Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                                    <td class="p-4 font-medium text-slate-600">{{ $trx['metode'] }}</td>
                                    <td class="p-4">
                                        @if($trx['status'] === 'Selesai')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700">
                                            Selesai
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-100 text-red-700">
                                            Batal
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        {{-- Right Sidebar Detail --}}
        <aside id="detail-sidebar" class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl lg:shadow-none border-l border-slate-200 transform translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 z-50 flex flex-col">
            
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-800 text-base">Detail Transaksi</h2>
                <button onclick="closeDetailSidebar()" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 no-scrollbar">
                {{-- Header Receipt --}}
                <div class="text-center mb-6 pb-6 border-b border-slate-200 border-dashed">
                    <h3 id="detail-ref" class="text-xl font-bold text-slate-900">POS-8921-A</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">14 Nov 2023 • 14:32 • Kasir: Budi</p>
                </div>

                {{-- Items --}}
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-snug">Fiesta Chicken Nugget 500g</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">2 x Rp 45.000</p>
                        </div>
                        <p class="text-sm font-bold text-slate-900 whitespace-nowrap">Rp 90.000</p>
                    </div>
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-snug">Champ Sosis Sapi 375g</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">1 x Rp 32.500</p>
                        </div>
                        <p class="text-sm font-bold text-slate-900 whitespace-nowrap">Rp 32.500</p>
                    </div>
                </div>

                <div class="w-full border-t border-slate-200 mb-6"></div>

                {{-- Summary --}}
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                        <span>Subtotal</span>
                        <span>Rp 122.500</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                        <span>Pajak (11%)</span>
                        <span>Rp 13.475</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 mt-3 border-t border-slate-200 border-dashed">
                        <span class="text-lg font-black text-slate-900">Total</span>
                        <span class="text-lg font-black text-slate-900">Rp 135.975</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-3">
                        <span class="font-medium text-slate-500">Metode Pembayaran</span>
                        <span class="font-bold text-slate-800">QRIS</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-6 border-t border-slate-200 bg-slate-50 space-y-3 shrink-0">
                <button class="w-full py-3.5 px-4 rounded-xl border-2 border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Ulang Struk
                </button>
                <button id="btn-refund" class="w-full py-3.5 px-4 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Batalkan Transaksi / Refund
                </button>
            </div>
        </aside>

        {{-- Mobile Menu Overlay --}}
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity" onclick="toggleMobileMenu()"></div>

        {{-- Mobile Sidebar Container (Appends the x-sidebar inside) --}}
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white z-50 transform -translate-x-full lg:hidden transition-transform duration-300 shadow-2xl flex flex-col">
            <x-sidebar role="kasir" active="penjualan" />
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function openDetailSidebar(rowElement, ref, status) {
            // Remove active state from all rows
            document.querySelectorAll('.transaction-row').forEach(row => {
                row.classList.remove('bg-blue-100');
            });
            // Add active state to clicked row
            if (rowElement) {
                rowElement.classList.add('bg-blue-100');
            }

            const sidebar = document.getElementById('detail-sidebar');
            document.getElementById('detail-ref').textContent = ref;
            
            const btnRefund = document.getElementById('btn-refund');
            if (status === 'Batal') {
                btnRefund.classList.add('opacity-50', 'cursor-not-allowed');
                btnRefund.disabled = true;
            } else {
                btnRefund.classList.remove('opacity-50', 'cursor-not-allowed');
                btnRefund.disabled = false;
            }

            if (window.innerWidth < 1024) {
                sidebar.classList.remove('translate-x-full');
            }
        }

        function closeDetailSidebar() {
            const sidebar = document.getElementById('detail-sidebar');
            sidebar.classList.add('translate-x-full');
        }

        // Initialize state on mobile
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('detail-sidebar');
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('translate-x-full');
            } else {
                sidebar.classList.add('translate-x-full');
            }
        });
    </script>
</body>
</html>
