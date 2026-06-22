window.cart = [];
window.currentTotal = 0;
window.inputUangDiterima = '0';
window.lastTransactionData = null;

// Custom Alert Logic
window.showCustomAlert = function(options) {
    const modal = document.getElementById('custom-alert-modal');
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
    document.getElementById('custom-alert-modal').classList.add('hidden');
};

// Mobile Cart Toggle Logic
window.toggleMobileCart = function() {
    const panel = document.getElementById('cart-panel');
    const overlay = document.getElementById('mobile-cart-overlay');

    if (panel.classList.contains('translate-x-full')) {
        panel.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    } else {
        panel.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
};

window.addToCart = function(id, nama, harga) {
    const existing = window.cart.find(item => item.id === id);
    if (existing) {
        existing.jumlah++;
    } else {
        window.cart.push({
            id,
            nama,
            harga,
            jumlah: 1
        });
    }
    window.renderCart();

    const badge = document.getElementById('mobile-cart-badge');
    badge.classList.add('scale-150');
    setTimeout(() => badge.classList.remove('scale-150'), 200);
};

window.removeFromCart = function(id) {
    window.cart = window.cart.filter(item => item.id !== id);
    window.renderCart();
};

window.clearCart = function() {
    if (window.cart.length > 0) {
        window.showCustomAlert({
            title: 'Kosongkan Keranjang?',
            message: 'Semua item belanjaan akan dihapus secara permanen.',
            type: 'warning',
            showCancel: true,
            cancelText: 'Kembali',
            confirmText: 'Ya, Kosongkan',
            confirmColorClass: 'bg-red-500 hover:bg-red-600',
            onConfirm: () => {
                window.cart = [];
                window.renderCart();
            }
        });
    } else {
        window.showCustomAlert({
            title: 'Keranjang Kosong',
            message: 'Keranjang masih kosong, belum ada item yang ditambahkan.',
            type: 'warning',
            confirmText: 'Mengerti'
        });
    }
};

window.updateQty = function(id, delta) {
    const item = window.cart.find(i => i.id === id);
    if (item) {
        item.jumlah += delta;
        if (item.jumlah <= 0) window.removeFromCart(id);
        else window.renderCart();
    }
};

window.renderCart = function() {
    const container = document.getElementById('cart-items');

    const totalItems = window.cart.reduce((sum, item) => sum + item.jumlah, 0);
    document.getElementById('mobile-cart-badge').textContent = totalItems;
    document.getElementById('cart-item-count').textContent = totalItems;

    if (window.cart.length === 0) {
        container.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-3">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-sm font-medium">Keranjang masih kosong</p>
            </div>`;
    } else {
        container.innerHTML = window.cart.map(item => `
        <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex flex-col gap-2">
            <div class="flex justify-between items-start gap-2">
                <p class="text-sm font-bold text-slate-800 leading-snug">${item.nama}</p>
                <p class="text-sm font-bold text-slate-900 shrink-0">Rp ${(item.harga * item.jumlah).toLocaleString('id-ID')}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[11px] font-medium text-slate-500">Rp ${item.harga.toLocaleString('id-ID')} / item</p>
                <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200 shadow-sm">
                    <button onclick="updateQty(${item.id}, -1)" class="w-7 h-6 flex items-center justify-center text-slate-600 hover:bg-white rounded-md transition-colors font-medium active:scale-95">−</button>
                    <span class="w-8 text-center text-xs font-bold text-slate-800">${item.jumlah}</span>
                    <button onclick="updateQty(${item.id}, 1)" class="w-7 h-6 flex items-center justify-center text-slate-600 hover:bg-white rounded-md transition-colors font-medium active:scale-95">+</button>
                </div>
            </div>
        </div>
    `).join('');
    }

    const subtotal = window.cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
    const tax = Math.round(subtotal * 0.11);
    const total = subtotal + tax;

    document.getElementById('cart-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('cart-tax').textContent = 'Rp ' + tax.toLocaleString('id-ID');
    document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
};

// Category Filter & Live Search
window.currentCategory = 'all';

window.filterCategory = function(category) {
    window.currentCategory = category;

    document.querySelectorAll('.category-btn').forEach(btn => {
        if (btn.getAttribute('data-category') === category) {
            btn.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
            btn.classList.add('bg-[#0f172a]', 'text-white', 'shadow-sm');
        } else {
            btn.classList.remove('bg-[#0f172a]', 'text-white', 'shadow-sm');
            btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        }
    });

    window.applyFilters();
};

document.addEventListener('DOMContentLoaded', () => {
    const searchProduk = document.getElementById('search-produk');
    if (searchProduk) {
        searchProduk.addEventListener('input', function(e) {
            window.applyFilters();
        });
    }
});

window.applyFilters = function() {
    const searchProduk = document.getElementById('search-produk');
    if (!searchProduk) return;
    
    const keyword = searchProduk.value.toLowerCase();
    const cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const barcode = card.getAttribute('data-barcode') || '';
        const category = card.getAttribute('data-category') || '';

        const matchesKeyword = name.includes(keyword) || barcode.includes(keyword);
        const matchesCategory = window.currentCategory === 'all' || category === window.currentCategory;

        if (matchesKeyword && matchesCategory) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
};

// Payment
window.processPayment = async function(method) {
    if (window.cart.length === 0) {
        window.showCustomAlert({
            title: 'Keranjang Kosong',
            message: 'Pilih produk terlebih dahulu sebelum melanjutkan ke pembayaran.',
            type: 'warning',
            confirmText: 'Mengerti'
        });
        return;
    }

    if (method === 'qris') {
        const btnQris = document.querySelector('button[onclick="processPayment(\'qris\')"]');
        let originalText = '';
        if (btnQris) {
            originalText = btnQris.innerHTML;
            btnQris.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Tunggu...';
            btnQris.disabled = true;
        }

        try {

            const response = await fetch('/kasir/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    items: window.cart,
                    method: 'qris',
                    cashier_session_id: 1,
                    branch_id: 1
                })
            });

            const result = await response.json();

            if (result.snap_token) {
                window.snap.pay(result.snap_token, {
                    onSuccess: async function(res) {
                        try {
                            await fetch('/kasir/checkout/success', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                        } catch (e) {
                            console.error('Failed to update status locally', e);
                        }
                        
                        window.saveTransactionData('qris', null, null);
                        window.clearCartAfterPayment();
                        window.showSuccessModal('QRIS', null);
                    },
                    onPending: function(res) {
                        window.showCustomAlert({
                            title: 'Menunggu',
                            message: 'Menunggu pelanggan memindai QRIS.',
                            type: 'warning'
                        });
                    },
                    onError: function(res) {
                        window.showCustomAlert({
                            title: 'Transaksi Gagal',
                            message: 'Pembayaran QRIS gagal diproses.',
                            type: 'error'
                        });
                    },
                    onClose: function() {
                        window.showCustomAlert({
                            title: 'Dibatalkan',
                            message: 'Halaman pembayaran QRIS ditutup oleh kasir.',
                            type: 'error'
                        });
                    }
                });
            } else {
                window.showCustomAlert({
                    title: 'Gagal',
                    message: 'Gagal mendapatkan token: ' + (result.message || 'Error internal'),
                    type: 'error'
                });
            }

        } catch (error) {
            console.error(error);
            window.showCustomAlert({
                title: 'Error Jaringan',
                message: 'Gagal mengirim data transaksi ke server.',
                type: 'error'
            });
        } finally {
            if (btnQris) {
                btnQris.innerHTML = originalText;
                btnQris.disabled = false;
            }
        }
    }
};

// Cash Modal Logic
window.openCashModal = function() {
    if (window.cart.length === 0) {
        window.showCustomAlert({
            title: 'Keranjang Kosong',
            message: 'Pilih produk terlebih dahulu sebelum melanjutkan ke pembayaran.',
            type: 'warning',
            confirmText: 'Mengerti'
        });
        return;
    }

    const subtotal = window.cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
    const tax = Math.round(subtotal * 0.11);
    window.currentTotal = subtotal + tax;

    window.inputUangDiterima = '';
    window.updateModalUI();

    setTimeout(() => {
        document.getElementById('modal-uang-diterima').focus();
    }, 100);

    document.getElementById('modal-total-tagihan').textContent = 'Rp ' + window.currentTotal.toLocaleString('id-ID');

    const quickAmountsDiv = document.getElementById('modal-quick-amounts');
    let quickHTML =
        `<button onclick="setQuickAmount(${window.currentTotal})" class="px-4 py-2 bg-[#dbeafe] text-[#1e40af] border border-[#bfdbfe] rounded-lg text-xs font-semibold whitespace-nowrap hover:bg-blue-200 transition-colors shadow-sm active:scale-95">Rp ${window.currentTotal.toLocaleString('id-ID')} (Pas)</button>`;

    const amount1 = Math.ceil(window.currentTotal / 50000) * 50000;
    const amount2 = Math.ceil(window.currentTotal / 100000) * 100000;

    if (amount1 > window.currentTotal) {
        quickHTML +=
            `<button onclick="setQuickAmount(${amount1})" class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold whitespace-nowrap hover:bg-slate-100 transition-colors shadow-sm active:scale-95">Rp ${amount1.toLocaleString('id-ID')}</button>`;
    }
    if (amount2 > amount1 && amount2 > window.currentTotal) {
        quickHTML +=
            `<button onclick="setQuickAmount(${amount2})" class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold whitespace-nowrap hover:bg-slate-100 transition-colors shadow-sm active:scale-95">Rp ${amount2.toLocaleString('id-ID')}</button>`;
    }

    quickAmountsDiv.innerHTML = quickHTML;

    document.getElementById('cash-modal').classList.remove('hidden');
};

window.closeCashModal = function() {
    document.getElementById('cash-modal').classList.add('hidden');
};

window.handleUangDiterimaInput = function(e) {
    let rawValue = e.target.value.replace(/\D/g, '');
    if (rawValue.length > 12) {
        rawValue = rawValue.substring(0, 12);
    }
    window.inputUangDiterima = rawValue;

    const inputField = document.getElementById('modal-uang-diterima');
    const numericValue = parseInt(window.inputUangDiterima) || 0;

    inputField.value = window.inputUangDiterima === '' ? '' : numericValue.toLocaleString('id-ID');

    window.recalcKembalian();
};

window.numpadPress = function(val) {
    if (window.inputUangDiterima === '' || window.inputUangDiterima === '0') {
        if (val !== '000' && val !== '0') window.inputUangDiterima = val;
    } else {
        window.inputUangDiterima += val;
    }
    if (window.inputUangDiterima.length > 12) {
        window.inputUangDiterima = window.inputUangDiterima.substring(0, 12);
    }
    window.updateModalUI();
};

window.numpadBackspace = function() {
    if (window.inputUangDiterima.length > 1) {
        window.inputUangDiterima = window.inputUangDiterima.slice(0, -1);
    } else {
        window.inputUangDiterima = '';
    }
    window.updateModalUI();
};

window.setQuickAmount = function(amount) {
    window.inputUangDiterima = amount.toString();
    window.updateModalUI();
};

window.updateModalUI = function() {
    const inputField = document.getElementById('modal-uang-diterima');
    const numericValue = parseInt(window.inputUangDiterima) || 0;

    inputField.value = window.inputUangDiterima === '' ? '' : numericValue.toLocaleString('id-ID');
    window.recalcKembalian();

    inputField.focus();
};

window.recalcKembalian = function() {
    const numericValue = parseInt(window.inputUangDiterima) || 0;
    const kembalian = numericValue - window.currentTotal;
    const kembalianEl = document.getElementById('modal-kembalian');
    const btnSubmit = document.getElementById('btn-submit-cash');

    if (kembalian >= 0 && window.inputUangDiterima !== '') {
        kembalianEl.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        kembalianEl.classList.remove('text-red-500');
        kembalianEl.classList.add('text-[#0f172a]');
        btnSubmit.disabled = false;
    } else {
        kembalianEl.textContent = '- Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
        kembalianEl.classList.remove('text-[#0f172a]');
        kembalianEl.classList.add('text-red-500');
        btnSubmit.disabled = true;
    }
};

window.submitCashPayment = async function() {
    const numericValue = parseInt(window.inputUangDiterima) || 0;
    if (numericValue < window.currentTotal) return;

    const kembalian = numericValue - window.currentTotal;

    // Disable button to prevent double-submit
    const btnSubmit = document.getElementById('btn-submit-cash');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Memproses...';
    btnSubmit.disabled = true;

    try {
        const response = await fetch('/kasir/checkout/cash', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                items: window.cart,
                received: numericValue,
                cashier_session_id: 1,
                branch_id: 1
            })
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            window.saveTransactionData('tunai', numericValue, kembalian);
            window.closeCashModal();
            window.clearCartAfterPayment();
            window.showSuccessModal('Tunai', kembalian);
        } else {
            window.showCustomAlert({
                title: 'Gagal',
                message: result.message || 'Gagal memproses pembayaran tunai.',
                type: 'error'
            });
        }
    } catch (error) {
        console.error(error);
        window.showCustomAlert({
            title: 'Error Jaringan',
            message: 'Gagal mengirim data transaksi ke server.',
            type: 'error'
        });
    } finally {
        btnSubmit.innerHTML = originalText;
        btnSubmit.disabled = false;
    }
};

window.showSuccessModal = function(method, kembalian) {
    document.getElementById('success-metode').textContent = method;
    document.getElementById('success-kembalian').textContent = kembalian !== null ? 'Rp ' + kembalian
        .toLocaleString('id-ID') : '-';
    window._needsReloadAfterSuccess = true;
    document.getElementById('success-modal').classList.remove('hidden');
};

window.closeSuccessModal = function() {
    document.getElementById('success-modal').classList.add('hidden');
    // Reload halaman agar stok ter-update dari database
    if (window._needsReloadAfterSuccess) {
        window._needsReloadAfterSuccess = false;
        window.location.reload();
    }
};

window.saveTransactionData = function(method, received, kembalian) {
    const subtotal = window.cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
    const tax = Math.round(subtotal * 0.11);
    window.lastTransactionData = {
        items: JSON.stringify(window.cart),
        method: method,
        received: received,
        kembalian: kembalian,
        subtotal: subtotal,
        tax: tax,
        total: subtotal + tax
    };
};

window.cetakStruk = function() {
    if (!window.lastTransactionData) {
        window.showCustomAlert({
            title: 'Data Tidak Ditemukan',
            message: 'Tidak ada data transaksi terakhir yang bisa dicetak.',
            type: 'error'
        });
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/kasir/cetak-struk';
    form.target = '_blank';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);

    for (const key in window.lastTransactionData) {
        if (window.lastTransactionData[key] !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = window.lastTransactionData[key];
            form.appendChild(input);
        }
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};

window.clearCartAfterPayment = function() {
    window.cart = [];
    window.renderCart();
    
    const searchProduk = document.getElementById('search-produk');
    if (searchProduk) {
        searchProduk.value = '';
        searchProduk.dispatchEvent(new Event('input'));
    }

    const panel = document.getElementById('cart-panel');
    if (!panel.classList.contains('translate-x-full') && window.innerWidth < 1024) {
        window.toggleMobileCart();
    }
};
