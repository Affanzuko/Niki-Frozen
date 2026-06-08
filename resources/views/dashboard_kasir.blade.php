<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Uji Coba (Standalone)</title>

    {{-- Token CSRF Wajib untuk Fetch API --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Menggunakan Tailwind CSS langsung dari CDN untuk testing --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 p-4 md:p-8">

    <div class="max-w-7xl mx-auto space-y-4">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Kasir POS (Mode Testing)</h1>

        {{-- Layout Utama: Grid Produk & Keranjang Belanja --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Sisi Kiri: Grid Produk --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- Kotak Pencarian --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="search-produk" placeholder="Cari nama produk atau scan barcode..."
                        class="w-full h-14 pl-12 pr-4 text-sm font-medium bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm"
                        autofocus>
                </div>

                {{-- Grid Kontainer Produk Frozen Food --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="product-container">
                    @foreach ($products as $product)
                        <div onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                            data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode }}"
                            class="product-card cursor-pointer bg-white border border-slate-200 p-4 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-300 transition duration-200 flex flex-col justify-between group">
                            <div>
                                <div
                                    class="w-full h-24 bg-blue-50 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition duration-200">
                                    <span class="text-3xl">❄️</span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 line-clamp-2">{{ $product->name }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">Barcode: {{ $product->barcode }}</p>
                            </div>
                            <p class="text-sm font-black text-blue-600 mt-3">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sisi Kanan: Keranjang Belanja --}}
            <div
                class="bg-white border border-slate-200 shadow-sm rounded-2xl p-5 flex flex-col h-[calc(100vh-120px)] sticky top-4">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    🛒 Keranjang Belanja
                </h3>

                {{-- Item di dalam keranjang --}}
                <div id="cart-items" class="flex-1 overflow-y-auto space-y-3 pr-1">
                    <p class="text-sm text-slate-400 text-center py-8">Keranjang kosong</p>
                </div>

                {{-- Bagian Ringkasan & Tombol Pembayaran --}}
                <div class="border-t border-slate-200 pt-4 mt-4 space-y-3">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">Rp 0</span>
                    </div>
                    <div
                        class="flex justify-between text-base font-black text-slate-800 border-t border-dashed border-slate-200 pt-2">
                        <span>Total Akhir</span>
                        <span id="cart-total" class="text-blue-600">Rp 0</span>
                    </div>

                    {{-- Opsi Pembayaran --}}
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button onclick="processPayment('cash')"
                            class="w-full py-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition">
                            💵 TUNAI
                        </button>
                        <button onclick="processPayment('qris')"
                            class="w-full py-3 rounded-xl bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            📱 QRIS
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Library Midtrans Snap (Sandbox Environment) --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        let cart = [];

        function addToCart(id, nama, harga) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.jumlah++;
            } else {
                cart.push({
                    id,
                    nama,
                    harga,
                    jumlah: 1
                });
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function updateQty(id, delta) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.jumlah += delta;
                if (item.jumlah <= 0) removeFromCart(id);
                else renderCart();
            }
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            if (cart.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-400 text-center py-8">Keranjang kosong</p>';
            } else {
                container.innerHTML = cart.map(item => `
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex-1 min-w-0 pr-2">
                        <p class="text-xs font-bold text-slate-800 truncate">${item.nama}</p>
                        <p class="text-[11px] text-blue-600 font-bold mt-0.5">Rp ${item.harga.toLocaleString('id-ID')} × ${item.jumlah}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="updateQty(${item.id}, -1)" class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-100">−</button>
                        <span class="w-6 text-center text-xs font-black text-slate-800">${item.jumlah}</span>
                        <button onclick="updateQty(${item.id}, 1)" class="w-7 h-7 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700">+</button>
                        <button onclick="removeFromCart(${item.id})" class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 text-xs ml-1">✕</button>
                    </div>
                </div>
            `).join('');
            }

            const total = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            document.getElementById('cart-subtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Fitur Live Search Bar
        document.getElementById('search-produk').addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const barcode = card.getAttribute('data-barcode');

                if (name.includes(keyword) || barcode.includes(keyword)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Proses Pembayaran
        async function processPayment(method) {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong!');
                return;
            }

            if (method === 'qris') {
                try {
                    const btnQris = document.querySelector('button[onclick="processPayment(\'qris\')"]');
                    const originalText = btnQris.innerHTML;
                    btnQris.innerHTML = '⏳ Tunggu...';
                    btnQris.disabled = true;

                    const response = await fetch('/checkout-kasir', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            items: cart,
                            method: 'qris',
                            cashier_session_id: 1,
                            branch_id: 1
                        })
                    });

                    const result = await response.json();
                    btnQris.innerHTML = originalText;
                    btnQris.disabled = false;

                    if (result.snap_token) {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(res) {
                                alert("Pembayaran QRIS Berhasil diselesaikan!");
                                cart = [];
                                renderCart();
                            },
                            onPending: function(res) {
                                alert("Menunggu pelanggan memindai QRIS.");
                            },
                            onError: function(res) {
                                alert("Transaksi gagal diproses!");
                            },
                            onClose: function() {
                                alert('Halaman pembayaran ditutup oleh kasir.');
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan token: ' + (result.message || 'Error internal server'));
                    }

                } catch (error) {
                    console.error(error);
                    alert('Gagal mengirim data transaksi ke server backend.');
                }
            } else {
                alert('Pembayaran menggunakan uang TUNAI sukses!');
                cart = [];
                renderCart();
            }
        }
    </script>
</body>

</html>
