import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/kasir/dashboard.js',
                'resources/js/pages/kasir/penjualan.js',
                'resources/js/pages/admin/barang-masuk.js',
                'resources/js/pages/admin/tambah-produk.js',
                'resources/js/pages/admin/edit-produk.js',
                'resources/js/pages/general-manager/dashboard.js',
                'resources/js/pages/general-manager/kelola-akun.js'
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1',
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
