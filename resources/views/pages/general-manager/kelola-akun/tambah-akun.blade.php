@extends('layouts.app')

@section('title', 'Tambah Akun - Niki Frozen')

@section('body_class', 'bg-slate-50 text-slate-800 h-screen overflow-hidden flex flex-col lg:flex-row')

@section('content')
    {{-- Sidebar --}}
    <x-sidebar role="general-manager" active="kelola-akun" />

    {{-- Main Content Area --}}
    <main class="flex-1 flex overflow-hidden relative">
        <div class="flex-1 flex flex-col bg-white overflow-hidden p-6 relative">
            {{-- Header --}}
            <div class="mb-6 flex-shrink-0">
                <h1 class="text-2xl font-bold text-slate-800">Tambah Akun</h1>
                <p class="text-sm text-slate-500">Manage system access for Branch Admin and Kasir roles.</p>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 no-scrollbar">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm max-w-4xl">
                    <form>
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="Nama Pengguna"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Username / Email</label>
                            <input type="email" placeholder="Email@example.com"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Role</label>
                            <select
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Kasir">Kasir</option>
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Cabang</label>
                            <select
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="" disabled selected>Pilih Cabang</option>
                                <option value="Toko satu">Toko satu</option>
                                <option value="Toko dua">Toko dua</option>
                                <option value="Condongcatur">Condongcatur</option>
                            </select>
                        </div>

                        <div class="flex gap-3">
                            <button type="button"
                                class="px-6 py-2.5 bg-[#0f172a] text-white rounded-full text-sm font-bold hover:bg-slate-800 transition-colors shadow-md">Simpan
                                Akun</button>
                            <a href="{{ route('pages.general-manager.kelola-akun') }}"
                                class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-full text-sm font-bold hover:bg-slate-300 transition-colors">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
