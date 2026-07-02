@extends('layouts.app')

@section('title', 'Kelola Akun - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar --}}
    <x-sidebar role="general-manager" active="kelola-akun" />

    {{-- Main Content Area --}}
    <main class="flex-1 h-full flex flex-col overflow-hidden relative transition-all duration-300">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Kelola Akun</h1>
                    <p class="text-sm text-slate-500 font-medium">Sistem Manajemen untuk Mengelola Akun General Manager, Admin, dan Kasir.</p>
                </div>
                <div class="relative w-full md:w-80">
                    <form method="GET" action="{{ route('pages.general-manager.kelola-akun') }}" class="w-full">
                        @if(request('role'))
                            <input type="hidden" name="role" value="{{ request('role') }}">
                        @endif
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Username / Nama"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium placeholder-slate-400">
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative shadow-sm shrink-0" role="alert">
                    <span class="block sm:inline text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-2 mb-6 shrink-0">
                <a href="{{ route('pages.general-manager.kelola-akun', ['search' => request('search'), 'role' => 'Semua Role']) }}"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors {{ request('role', 'Semua Role') == 'Semua Role' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">Semua Role</a>
                <a href="{{ route('pages.general-manager.kelola-akun', ['search' => request('search'), 'role' => 'general_manager']) }}"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors {{ request('role') == 'general_manager' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">GM</a>
                <a href="{{ route('pages.general-manager.kelola-akun', ['search' => request('search'), 'role' => 'admin']) }}"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors {{ request('role') == 'admin' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">Admin Cabang</a>
                <a href="{{ route('pages.general-manager.kelola-akun', ['search' => request('search'), 'role' => 'cashier']) }}"
                    class="px-5 py-2 border rounded-xl text-sm font-bold shadow-sm transition-colors {{ request('role') == 'cashier' ? 'bg-[#dbeafe] text-[#1e40af] border-[#bfdbfe]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">Kasir</a>

                <div class="flex-1"></div>

                <a href="{{ route('pages.general-manager.tambah-akun') }}"
                    class="px-5 py-2 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Akun
                </a>
            </div>

            {{-- Table --}}
            <div class="w-full border border-slate-200 rounded-xl overflow-hidden bg-white flex-1 flex flex-col min-h-0"
                x-data="{
                    syncScroll(e) {
                        $refs.headerScroll.scrollLeft = e.target.scrollLeft;
                    }
                }">
                <!-- Header Table (Static) -->
                <div class="bg-slate-50 border-b border-slate-200 pr-[6px] overflow-hidden" x-ref="headerScroll">
                    <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[25%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <thead>
                            <tr class="text-xs text-slate-500 uppercase tracking-wider font-bold">
                                <th class="p-4 py-4 pl-12 text-center whitespace-nowrap">Nama Karyawan</th>
                                <th class="p-4 py-4 text-center whitespace-nowrap">Username</th>
                                <th class="p-4 py-4 text-center whitespace-nowrap">Role</th>
                                <th class="p-4 py-4 text-center whitespace-nowrap">Cabang</th>
                                <th class="p-4 py-4 pr-12 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Body Table (Scrollable) -->
                <div class="flex-1 overflow-auto relative min-h-0 bg-white" @scroll="syncScroll">
                    <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
                        <colgroup>
                            <col class="w-[25%]">
                            <col class="w-[15%]">
                            <col class="w-[25%]">
                            <col class="w-[20%]">
                            <col class="w-[15%]">
                        </colgroup>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 pl-12 text-center font-medium text-slate-800 whitespace-nowrap truncate">{{ $user->name }}</td>
                                <td class="p-4 text-center font-semibold text-slate-600 whitespace-nowrap truncate">{{ $user->username }}</td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($user->role === 'general_manager')
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-[11px] font-bold uppercase border shadow-sm bg-purple-100 text-purple-700 border-purple-200" style="background-color: #f3e8ff; color: #7e22ce; border-color: #e9d5ff;">General Manager</span>
                                    @elseif($user->role === 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-[11px] font-bold uppercase border shadow-sm bg-blue-50 text-blue-700 border-blue-200">Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-[11px] font-bold uppercase border shadow-sm bg-slate-50 text-slate-600 border-slate-200">Kasir</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center text-slate-600 whitespace-nowrap truncate">{{ $user->branch ? $user->branch->name : 'Semua Cabang' }}</td>
                                <td class="p-4 pr-12 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pages.general-manager.edit-akun', $user->id) }}" class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('pages.general-manager.hapus-akun', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 py-8 text-center text-slate-500 font-medium">Tidak ada data akun</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50 shrink-0">
                        {{ $users->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @push('scripts')
        @vite('resources/js/pages/general-manager/kelola-akun.js')
    @endpush
@endsection
