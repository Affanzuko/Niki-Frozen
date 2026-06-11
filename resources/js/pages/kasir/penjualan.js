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
