{{-- Mobile Header --}}
<div class="lg:hidden bg-[#0f172a] text-white p-4 flex items-center justify-between z-30 shadow-md shrink-0 w-full">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div>
            <h1 class="font-bold text-lg leading-none">Niki Frozen</h1>
            <p class="text-[10px] text-slate-300 mt-1 uppercase tracking-wider">General Manager</p>
        </div>
    </div>
</div>

{{-- Sidebar Overlay --}}
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

{{-- Sidebar --}}
<div id="sidebar-gm" class="fixed inset-y-0 left-0 transform -translate-x-full lg:relative lg:translate-x-0 z-50 w-64 bg-slate-100 h-full flex flex-col border-r border-slate-200 shrink-0 transition-transform duration-300 ease-in-out">
    {{-- Header / Brand --}}
    <div class="p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#0f172a] rounded-full flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 leading-tight">Niki Frozen</h2>
                <p class="text-sm font-medium text-slate-500 capitalize">General Manager</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-slate-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('pages.general-manager.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-semibold text-sm
           {{ isset($active) && $active === 'dashboard' ? 'bg-[#dbeafe] text-blue-700' : 'text-slate-600 hover:bg-slate-200/50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>
        
        <a href="{{ route('pages.general-manager.keuangan') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-semibold text-sm
           {{ isset($active) && $active === 'keuangan' ? 'bg-[#dbeafe] text-blue-700' : 'text-slate-600 hover:bg-slate-200/50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Keuangan
        </a>

        <a href="{{ route('pages.general-manager.kelola-akun') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-semibold text-sm
           {{ isset($active) && $active === 'kelola-akun' ? 'bg-[#dbeafe] text-blue-700' : 'text-slate-600 hover:bg-slate-200/50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Kelola Akun
        </a>
    </nav>

    {{-- Footer / Logout --}}
    <div class="p-4 border-t border-slate-200">
        <button class="w-full bg-[#0f172a] hover:bg-slate-800 text-white flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm transition-colors shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </button>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar-gm');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            // Open
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            // Close
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
</script>
