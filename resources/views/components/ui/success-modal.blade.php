<div id="success-modal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeSuccessModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div
            class="bg-[#f8fafc] rounded-3xl shadow-2xl w-full max-w-sm flex flex-col items-center p-8 animate-in zoom-in duration-200">

            {{-- Icon --}}
            <div class="w-20 h-20 bg-[#e0e7ff] rounded-2xl flex items-center justify-center mb-6">
                <div class="w-10 h-10 bg-[#0f172a] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            {{-- Text --}}
            <h2 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">Transaksi Berhasil!</h2>
            <p class="text-[13px] text-slate-500 mb-8 text-center">Data penjualan telah tercatat di sistem.</p>

            {{-- Detail Box --}}
            <div class="w-full bg-white border border-slate-200 rounded-xl p-5 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-medium text-slate-500">Metode</span>
                    <span id="success-metode" class="text-sm font-bold text-slate-800">Tunai</span>
                </div>
                <div class="w-full border-t border-slate-100 mb-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-500">Kembalian</span>
                    <span id="success-kembalian" class="text-lg font-black text-[#0f172a]">Rp 25.000</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="w-full flex gap-3">
                <button onclick="cetakStruk()"
                    class="flex-1 py-3 px-4 rounded-xl border border-slate-300 text-slate-700 font-bold text-[13px] hover:bg-slate-100 transition-colors flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Struk
                </button>
                <button onclick="closeSuccessModal()"
                    class="flex-[1.2] py-3 px-4 rounded-xl bg-[#0f172a] text-white font-bold text-[13px] hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Tutup & Baru
                </button>
            </div>
        </div>
    </div>
</div>
