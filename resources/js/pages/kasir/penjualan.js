window.toggleMobileMenu = function() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (!sidebar || !overlay) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
};

window.addEventListener('resize', () => {
    const sidebar = document.getElementById('detail-sidebar');
    if (sidebar) {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('translate-x-full');
        } else {
            sidebar.classList.add('translate-x-full');
        }
    }
});

window.cetakUlangStruk = function(transaction) {
    if (!transaction) return;

    const items = transaction.items.map(i => ({
        id: i.product_id,
        nama: i.product ? i.product.name : 'Produk',
        harga: Number(i.unit_price),
        jumlah: Number(i.quantity)
    }));

    const total = Number(transaction.total_price);
    const subtotal = Math.round(total / 1.11);
    const tax = total - subtotal;
    const method = transaction.payment_method;

    // Untuk transaksi lama, kembalian dan uang diterima mungkin tidak tersimpan di DB jika bukan tunai, atau jika field tersebut tidak ada di model Transaction.
    // Kita set default (received = total, kembalian = 0) untuk kemudahan cetak ulang.
    const received = total;
    const kembalian = 0;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/kasir/cetak-struk';
    form.target = '_blank';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    const data = {
        items: JSON.stringify(items),
        method: method,
        received: received,
        kembalian: kembalian,
        subtotal: subtotal,
        tax: tax,
        total: total
    };

    for (const key in data) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};

window.showCustomAlert = function (options) {
    const modal = document.getElementById('custom-alert-modal');
    if (!modal) return;

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

window.closeCustomAlert = function () {
    const modal = document.getElementById('custom-alert-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.batalkanTransaksi = function(transaction) {
    if (!transaction) return;

    window.showCustomAlert({
        title: 'Batalkan Transaksi?',
        message: `Apakah Anda yakin ingin membatalkan transaksi ${transaction.transaction_code}?`,
        type: 'warning',
        showCancel: true,
        cancelText: 'Kembali',
        confirmText: 'Ya, Batalkan',
        confirmColorClass: 'bg-red-500 hover:bg-red-600',
        onConfirm: () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/kasir/penjualan/cancel/${transaction.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.showCustomAlert({
                        title: 'Berhasil',
                        message: data.message,
                        type: 'info',
                        onConfirm: () => window.location.reload()
                    });
                } else {
                    window.showCustomAlert({
                        title: 'Gagal',
                        message: data.message || 'Gagal membatalkan transaksi.',
                        type: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showCustomAlert({
                    title: 'Error Jaringan',
                    message: 'Terjadi kesalahan sistem.',
                    type: 'error'
                });
            });
        }
    });
};
