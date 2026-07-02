@props(['role' => 'kasir', 'active' => 'dashboard'])

@php
    $menus = [];
    if ($role === 'kasir') {
        $menus = [
            [
                'name' => 'Dashboard',
                'id' => 'dashboard',
                'url' => route('pages.kasir.dashboard'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            ],
            [
                'name' => 'Penjualan',
                'id' => 'penjualan',
                'url' => route('pages.kasir.penjualan'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
            ],
        ];
    } elseif ($role === 'admin') {
        $menus = [
            [
                'name' => 'Dashboard',
                'id' => 'dashboard',
                'url' => route('pages.admin.dashboard'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            ],
            [
                'name' => 'Kelola Stok',
                'id' => 'stok',
                'url' => route('pages.admin.stok'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
            ],
            [
                'name' => 'Penjualan',
                'id' => 'penjualan',
                'url' => route('pages.admin.penjualan'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
            ],
            [
                'name' => 'Keuangan',
                'id' => 'keuangan',
                'url' => route('pages.admin.keuangan'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
            ],
            [
                'name' => 'Kelola Harga',
                'id' => 'kelola-harga',
                'url' => route('pages.admin.kelola-harga'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>',
            ],
        ];
    } elseif ($role === 'general-manager') {
        $menus = [
            [
                'name' => 'Dashboard',
                'id' => 'dashboard',
                'url' => route('pages.general-manager.dashboard'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            ],
            [
                'name' => 'Keuangan',
                'id' => 'keuangan',
                'url' => route('pages.general-manager.keuangan'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
            ],
            [
                'name' => 'Stok Produk',
                'id' => 'stok',
                'url' => route('pages.general-manager.stok'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
            ],
            [
                'name' => 'Kelola Akun',
                'id' => 'kelola-akun',
                'url' => route('pages.general-manager.kelola-akun'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
            ],
        ];
    } elseif ($role === 'owner') {
        $menus = [
            [
                'name' => 'Dashboard',
                'id' => 'dashboard',
                'url' => route('pages.owner.dashboard'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            ],
            [
                'name' => 'Keuangan',
                'id' => 'keuangan',
                'url' => route('pages.owner.keuangan'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
            ],
            [
                'name' => 'Stok Produk',
                'id' => 'stok',
                'url' => route('pages.owner.stok'),
                'icon' =>
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
            ],
        ];
    }
@endphp

<div class="hidden lg:flex w-64 bg-slate-100 h-full flex-col border-r border-slate-200 shrink-0">
    {{-- Header / Brand --}}
    <div class="p-6 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/Logo nikifrozen.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 leading-tight">Niki Frozen</h2>
                <p class="text-sm font-medium text-slate-500 capitalize">{{ $role }}</p>
            </div>
        </div>
    </div>

    {{-- Realtime Clock (Menu Style) --}}
    <div class="px-4 pb-4" 
         x-data="{ 
            hours: '', 
            minutes: '', 
            showColon: true,
            date: '',
            updateTime() {
                const now = new Date();
                this.hours = String(now.getHours()).padStart(2, '0');
                this.minutes = String(now.getMinutes()).padStart(2, '0');
                this.showColon = !this.showColon;
                this.date = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short' });
            }
         }" 
         x-init="updateTime(); setInterval(() => updateTime(), 1000)">
        
        <div class="bg-[#dbeafe] text-blue-700 rounded-xl px-4 py-3 text-center">
            <div class="flex flex-col items-center">
                <div class="flex items-center text-2xl font-bold tracking-widest font-mono leading-none">
                    <span x-text="hours"></span>
                    <span class="mx-0.5 transition-opacity duration-300" :class="showColon ? 'opacity-100' : 'opacity-0'">:</span>
                    <span x-text="minutes"></span>
                </div>
                <span class="text-sm font-semibold mt-1.5" x-text="date"></span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        @foreach ($menus as $menu)
            <a href="{{ $menu['url'] }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-semibold text-sm
               @if ($active === $menu['id']) bg-[#dbeafe] text-blue-700
               @else
                   text-slate-600 hover:bg-slate-200/50 hover:text-slate-900 @endif">
                {!! $menu['icon'] !!}
                {{ $menu['name'] }}
            </a>
        @endforeach
    </nav>

    {{-- Footer / Profile --}}
    <div class="p-4 border-t border-slate-200" x-data="{
        notifications: [],
        showNotifications: false,
        get unreadCount() {
            return this.notifications.filter(n => !n.is_read).length;
        },
        async fetchNotifications() {
            try {
                const response = await fetch('{{ route('pages.admin.notifications.unread') ?? '/admin/notifications' }}');
                this.notifications = await response.json();
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            }
        },
        init() {
            this.fetchNotifications();
            // Cek notifikasi setiap 1 menit
            setInterval(() => this.fetchNotifications(), 60000);
        }
    }">
        @if(in_array($role, ['admin', 'gm']))
        {{-- Notification Dropdown --}}
        <div class="relative mb-3">
            <button @click="showNotifications = !showNotifications" @click.away="showNotifications = false" type="button" 
                class="w-full bg-white rounded-xl p-3 flex items-center justify-between border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 relative transition-colors"
                         :class="unreadCount > 0 ? 'bg-red-50 text-red-500' : 'bg-slate-50 text-slate-400'">
                        <i class="fa-solid fa-bell"></i>
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white" x-text="unreadCount"></span>
                        </template>
                    </div>
                    <div class="text-left flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800">Notifikasi</p>
                        <p class="text-[11px] font-semibold truncate" 
                           :class="unreadCount > 0 ? 'text-red-500' : 'text-slate-500'" 
                           x-text="unreadCount > 0 ? unreadCount + ' Belum dibaca' : 'Semua telah dibaca'"></p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform" :class="showNotifications ? '' : 'rotate-180'"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="showNotifications" x-transition x-cloak
                class="absolute bottom-full left-0 w-full mb-2 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50 flex flex-col max-h-[300px]">
                <div class="p-3 border-b border-slate-100 bg-slate-50 shrink-0">
                    <h4 class="text-xs font-bold text-slate-700"><i class="fa-solid fa-circle-exclamation text-red-500 mr-1"></i> Peringatan Kadaluarsa</h4>
                </div>
                <div class="divide-y divide-slate-100 overflow-y-auto overflow-x-hidden flex-1 custom-scrollbar">
                    <template x-if="notifications.length === 0">
                        <div class="p-4 text-center text-slate-500 text-xs">Aman! Tidak ada barang yang mendekati kadaluarsa.</div>
                    </template>
                    <template x-for="notif in notifications" :key="notif.id">
                        <a :href="`/admin/notifications/${notif.id}/read`" class="block p-3 transition-colors group" 
                           :class="notif.is_read ? 'hover:bg-slate-50 opacity-60' : 'hover:bg-red-50 bg-red-50/20'">
                            <p class="text-[11px] leading-snug transition-colors" 
                               :class="notif.is_read ? 'text-slate-600 font-medium' : 'text-slate-800 font-bold group-hover:text-red-700'" 
                               x-text="notif.message"></p>
                            <p class="text-[9px] mt-1 font-medium" 
                               :class="notif.is_read ? 'text-slate-400' : 'text-slate-500'" 
                               x-text="new Date(notif.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit'})"></p>
                        </a>
                    </template>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl p-3 flex items-center gap-3 mb-3 border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user() ? auth()->user()->name : 'Budi Santoso' }}</p>
                <p class="text-[11px] font-semibold text-blue-600 bg-blue-50 inline-block px-2 py-0.5 rounded-full mt-0.5 capitalize">
                    {{ auth()->user() ? str_replace('_', ' ', auth()->user()->role) : 'Shift Pagi' }}
                </p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full bg-[#0f172a] hover:bg-slate-800 text-white flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm transition-colors shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
