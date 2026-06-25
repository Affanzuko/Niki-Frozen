window.filterRoleSelect = function(role) {
    // Filter table rows
    const rows = document.querySelectorAll('.akun-row');
    rows.forEach(row => {
        const rowRole = row.getAttribute('data-role');
        if (role === 'Semua Role' || rowRole === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
};
