@props(['role' => 'kasir', 'active' => 'dashboard'])

@php
    $menus = [];
    if ($role === 'kasir') {
        $menus = [
            [
                'name' => 'Dashboard', 
                'id' => 'dashboard', 
                'url' => route('dashboard.kasir'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>'
            ],
            [
                'name' => 'Penjualan', 
                'id' => 'penjualan', 
                'url' => route('penjualan.kasir'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            ],
        ];
    } elseif ($role === 'admin') {
        $menus = [
            [
                'name' => 'Dashboard', 
                'id' => 'dashboard', 
                'url' => route('dashboard.admin'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>'
            ],
            [
                'name' => 'Kelola Stok', 
                'id' => 'stok', 
                'url' => route('stok.admin'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>'
            ],
            [
                'name' => 'Penjualan', 
                'id' => 'penjualan', 
                'url' => route('penjualan.admin'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            ],
            [
                'name' => 'Keuangan', 
                'id' => 'keuangan', 
                'url' => route('keuangan.admin'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>'
            ],
            [
                'name' => 'Kelola Harga', 
                'id' => 'kelola-harga', 
                'url' => route('kelola-harga.admin'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>'
            ],
        ];
    } elseif ($role === 'general manager') {
        $menus = [
            [
                'name' => 'Dashboard', 
                'id' => 'dashboard', 
                'url' => '#', 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>'
            ],
            [
                'name' => 'Laporan', 
                'id' => 'laporan', 
                'url' => '#', 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
            ],
        ];
    } elseif ($role === 'owner') {
         $menus = [
            [
                'name' => 'Dashboard', 
                'id' => 'dashboard', 
                'url' => route('pages.owner.dashboard'), 
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>'
            ],
        ];
    }
@endphp

<div class="hidden lg:flex w-64 bg-slate-100 h-full flex-col border-r border-slate-200 shrink-0">
    {{-- Header / Brand --}}
    <div class="p-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#0f172a] rounded-full flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 leading-tight">Niki Frozen</h2>
                <p class="text-sm font-medium text-slate-500 capitalize">{{ $role }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        @foreach ($menus as $menu)
            <a href="{{ $menu['url'] }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-semibold text-sm
               @if($active === $menu['id']) 
                   bg-[#dbeafe] text-blue-700
               @else 
                   text-slate-600 hover:bg-slate-200/50 hover:text-slate-900
               @endif">
                {!! $menu['icon'] !!}
                {{ $menu['name'] }}
            </a>
        @endforeach
    </nav>

    {{-- Footer / Profile --}}
    <div class="p-4 border-t border-slate-200">
        <div class="bg-white rounded-xl p-3 flex items-center gap-3 mb-3 border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">Budi Santoso</p>
                <p class="text-[11px] font-semibold text-blue-600 bg-blue-50 inline-block px-2 py-0.5 rounded-full mt-0.5">Shift Pagi</p>
            </div>
        </div>

        <button class="w-full bg-[#0f172a] hover:bg-slate-800 text-white flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm transition-colors shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </button>
    </div>
</div>
