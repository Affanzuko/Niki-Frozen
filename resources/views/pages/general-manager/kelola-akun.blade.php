@extends('layouts.app')

@section('title', 'Kelola Akun - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar --}}
    <x-sidebar role="general-manager" active="kelola-akun" />

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-6 relative">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-6 flex-shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Kelola Akun</h1>
                    <p class="text-sm text-slate-500">Sistem Manajemen untuk Mengelola Akun General Manager, Admin, dan
                        Kasir.</p>
                </div>
                <a href="{{ route('pages.general-manager.tambah-akun') }}"
                    class="px-5 py-2.5 bg-[#0f172a] text-white rounded-lg text-sm font-bold hover:bg-slate-800 transition-colors flex items-center gap-2 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Tambah Akun
                </a>
            </div>

            <div class="flex-1 flex flex-col overflow-hidden border border-slate-200 rounded-xl bg-white shadow-sm">
                {{-- Search & Filter --}}
                <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row gap-4 shrink-0 bg-white">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" placeholder="Search accounts..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="relative min-w-[160px]">
                        <select onchange="filterRoleSelect(this.value)"
                            class="w-full appearance-none pl-10 pr-8 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 outline-none focus:border-blue-500 bg-white cursor-pointer transition-colors shadow-sm">
                            <option value="Semua Role">Semua Role</option>
                            <option value="GM">GM</option>
                            <option value="Admin cabang">Admin Cabang</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="flex-1 overflow-y-auto no-scrollbar">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <colgroup>
                                <col class="w-[25%]">
                                <col class="w-[15%]">
                                <col class="w-[25%]">
                                <col class="w-[20%]">
                                <col class="w-[15%]">
                            </colgroup>
                            <thead>
                                <tr
                                    class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    <th class="p-4 pl-6 text-center whitespace-nowrap">Nama Karyawan</th>
                                    <th class="p-4 text-center whitespace-nowrap">Username</th>
                                    <th class="p-4 text-center whitespace-nowrap">Role</th>
                                    <th class="p-4 text-center whitespace-nowrap">Cabang</th>
                                    <th class="p-4 text-center pr-6 whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Admin cabang">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Budi
                                        Siregar</td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">budi.admin1</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">Admin</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko satu</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Admin cabang">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Yanti Asoy
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">yanti.admin2</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">Admin</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko dua</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Kasir">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Ahmad
                                        Lutfi</td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">lutfi.kasir1a</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">Kasir</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko satu</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Kasir">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Ririn Sue
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">ririn.kasir1b</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">Kasir</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko satu</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Kasir">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Panji
                                        Gumilang</td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">panji.kasir2a</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">Kasir</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko dua</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="akun-row border-b border-slate-100 hover:bg-slate-50 transition-colors"
                                    data-role="Kasir">
                                    <td class="p-4 pl-6 text-center font-medium text-slate-800 whitespace-nowrap">Markonah
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">marko.kasir2b</td>
                                    <td class="p-4 text-center whitespace-nowrap"><span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">Kasir</span>
                                    </td>
                                    <td class="p-4 text-center text-slate-600 whitespace-nowrap">Toko dua</td>
                                    <td class="p-4 pr-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pages.general-manager.edit-akun', 1) }}"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></a>
                                            <button
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun ini?')) alert('Akun berhasil dihapus.')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all"><svg
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        @vite('resources/js/pages/general-manager/kelola-akun.js')
    @endpush
@endsection
