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
                    <form action="{{ route('pages.general-manager.store-akun') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all @error('name') border-red-500 @enderror">
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="username" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all @error('username') border-red-500 @enderror">
                            @error('username') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Password</label>
                            <input type="password" name="password" placeholder="Password (min 6 karakter)" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all @error('password') border-red-500 @enderror">
                            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Role</label>
                            <select name="role" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all @error('role') border-red-500 @enderror">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                                <option value="general_manager" {{ old('role') == 'general_manager' ? 'selected' : '' }}>General Manager</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-slate-800 mb-2">Cabang (Opsional untuk GM)</label>
                            <select name="branch_id"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all @error('branch_id') border-red-500 @enderror">
                                <option value="">Pilih Cabang (Semua Cabang)</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
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
