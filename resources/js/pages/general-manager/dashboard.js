window.goToKeuanganDetail = function() {
    const select = document.getElementById('pilih-cabang');
    const cabang = select ? select.value : 'Condongcatur';
    window.location.href = `/gm/keuangan/${cabang}`;
};
