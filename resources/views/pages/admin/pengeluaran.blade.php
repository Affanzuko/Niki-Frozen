@extends('layouts.app')

@section('title', 'Kelola Pengeluaran - Niki Frozen')

@section('body_data')
    x-data="{ sidebarOpen: false }"
@endsection

@section('content')
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity></div>

    <!-- Sidebar Komponen -->
    <x-sidebar role="admin" active="pengeluaran" />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full transition-all duration-300"
          x-data="{
              isAddModalOpen: false,
              isEditModalOpen: false,
              editId: '',
              editDescription: '',
              editAmount: '',
              editDate: ''
          }">
        <div class="flex-1 flex flex-col min-h-0 p-4 lg:p-8 bg-white rounded-tl-none lg:rounded-tl-2xl shadow-sm border-l border-t lg:border-t-0 border-slate-200">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ auth()->user()->branch->name ?? 'Cabang'}}</h1>
                        <p class="text-slate-500 font-medium">Kelola Pengeluaran</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="isAddModalOpen = true"
                        class="bg-[#0f172a] hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Pengeluaran</span>
                    </button>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-xl font-medium border border-green-200 flex items-center gap-3">
                <i class="fa-solid fa-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- Tabel Data -->
            <div class="flex-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col min-h-0">
                <div class="overflow-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                                <th class="p-4 pl-12 font-bold">Tanggal</th>
                                <th class="p-4 font-bold">Deskripsi</th>
                                <th class="p-4 font-bold text-right">Jumlah (Rp)</th>
                                <th class="p-4 pr-12 font-bold text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
                            @forelse ($expenses as $expense)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-4 pl-12 text-slate-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($expense->date)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="p-4 text-slate-800">
                                        {{ $expense->description }}
                                    </td>
                                    <td class="p-4 text-right text-slate-900 font-bold tabular-nums">
                                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 pr-12 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Edit Button -->
                                            <button type="button" @click="
                                                editId = '{{ $expense->id }}';
                                                editDescription = '{{ addslashes($expense->description) }}';
                                                editAmount = '{{ $expense->amount }}';
                                                editDate = '{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}';
                                                isEditModalOpen = true;
                                                setTimeout(() => { 
                                                    const fp = document.getElementById('edit_expense_date')._flatpickr; 
                                                    if(fp) fp.setDate(editDate); 
                                                }, 50);
                                                "
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 rounded-lg transition-all">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button type="button"
                                                onclick="confirmDeleteExpense({{ $expense->id }}, '{{ addslashes($expense->description) }}')"
                                                class="p-1.5 border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition-all">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>

                                            <!-- Hidden Form for Delete -->
                                            <form id="delete-expense-form-{{ $expense->id }}" action="{{ route('pages.admin.pengeluaran.destroy', $expense->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                                                <i class="fa-solid fa-receipt text-2xl"></i>
                                            </div>
                                            <p class="font-medium">Belum ada data pengeluaran.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($expenses->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50 shrink-0">
                    {{ $expenses->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Add Expense Modal -->
        <div x-show="isAddModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <div x-show="isAddModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isAddModalOpen = false"></div>
            
            <div x-show="isAddModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Pengeluaran</h3>
                    <button type="button" @click="isAddModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('pages.admin.pengeluaran.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-4 overflow-y-auto flex-1">
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="date" required value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal"
                                    class="w-full px-4 py-2.5 pl-11 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium cursor-pointer flatpickr-date">
                                <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Deskripsi Pengeluaran <span class="text-red-500">*</span></label>
                            <input type="text" name="description" required placeholder="Contoh: Beli token listrik, Beli plastik, dll"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                        </div>
                        
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Jumlah (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-slate-500 font-bold text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" required min="0" placeholder="0"
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                        <button type="button" @click="isAddModalOpen = false" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-sm transition-colors shadow-md shadow-slate-900/10">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Expense Modal -->
        <div x-show="isEditModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <div x-show="isEditModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditModalOpen = false"></div>
            
            <div x-show="isEditModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-bold text-slate-800">Edit Pengeluaran</h3>
                    <button type="button" @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="`/admin/pengeluaran/${editId}`" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4 overflow-y-auto flex-1">
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="date" required x-model="editDate" id="edit_expense_date" placeholder="Pilih tanggal"
                                    class="w-full px-4 py-2.5 pl-11 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium cursor-pointer flatpickr-date">
                                <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Deskripsi Pengeluaran <span class="text-red-500">*</span></label>
                            <input type="text" name="description" required x-model="editDescription"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                        </div>
                        
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Jumlah (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-slate-500 font-bold text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" required min="0" x-model="editAmount"
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                        <button type="button" @click="isEditModalOpen = false" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-colors shadow-md shadow-blue-500/20">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Custom Alert Modal (for confirm delete) -->
        <div id="custom-alert-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCustomAlert()"></div>

            <!-- Modal Panel -->
            <div
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100 opacity-100">
                <div class="p-6 text-center">
                    <div id="custom-alert-icon-container" class="mx-auto flex justify-center">
                        <!-- Icon diisi via JS -->
                    </div>
                    <h3 id="custom-alert-title" class="text-xl font-black text-slate-800 mb-2"></h3>
                    <p id="custom-alert-message" class="text-sm font-medium text-slate-500 mb-8 leading-relaxed"></p>

                    <div id="custom-alert-buttons" class="flex gap-3 w-full">
                        <!-- Tombol diisi via JS -->
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        function confirmDeleteExpense(id, description) {
            window.showCustomAlert({
                title: 'Hapus Pengeluaran?',
                message: `Apakah Anda yakin ingin menghapus catatan pengeluaran "${description}"? Tindakan ini tidak dapat dibatalkan.`,
                type: 'error',
                showCancel: true,
                cancelText: 'Batal',
                confirmText: 'Ya, Hapus',
                confirmColorClass: 'bg-red-500 hover:bg-red-600',
                onConfirm: () => {
                    document.getElementById('delete-expense-form-' + id).submit();
                }
            });
        }

        // Custom Alert Logic (Re-used from stok.blade.php)
        window.showCustomAlert = function(options) {
            const modal = document.getElementById('custom-alert-modal');
            if (!modal) return;
            // set flex to display modal properly centered
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const titleEl = document.getElementById('custom-alert-title');
            const messageEl = document.getElementById('custom-alert-message');
            const iconContainer = document.getElementById('custom-alert-icon-container');
            const buttonsContainer = document.getElementById('custom-alert-buttons');

            titleEl.textContent = options.title || 'Perhatian';
            messageEl.textContent = options.message || '';

            if (options.type === 'warning') {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-amber-100 text-amber-500 mx-auto';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else if (options.type === 'error') {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-red-100 text-red-500 mx-auto';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                iconContainer.className =
                    'w-16 h-16 rounded-2xl flex items-center justify-center mb-5 bg-blue-100 text-blue-500 mx-auto';
                iconContainer.innerHTML =
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            buttonsContainer.innerHTML = '';

            if (options.showCancel) {
                const cancelBtn = document.createElement('button');
                cancelBtn.className =
                    'flex-1 py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-[13px] hover:bg-slate-50 transition-colors active:scale-95';
                cancelBtn.textContent = options.cancelText || 'Batal';
                cancelBtn.onclick = () => {
                    window.closeCustomAlert();
                    if (options.onCancel) options.onCancel();
                };
                buttonsContainer.appendChild(cancelBtn);
            }

            const confirmBtn = document.createElement('button');
            confirmBtn.className =
                `flex-1 py-3 px-4 rounded-xl text-white font-bold text-[13px] transition-colors active:scale-95 shadow-md ${options.confirmColorClass || 'bg-[#0f172a] hover:bg-slate-800'}`;
            confirmBtn.textContent = options.confirmText || 'Oke';
            confirmBtn.onclick = () => {
                window.closeCustomAlert();
                if (options.onConfirm) options.onConfirm();
            };
            buttonsContainer.appendChild(confirmBtn);
        };

        window.closeCustomAlert = function() {
            const modal = document.getElementById('custom-alert-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        // Initialize flatpickr on inputs with class 'flatpickr-date'
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof flatpickr !== 'undefined') {
                flatpickr('.flatpickr-date', {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "j F Y",
                    allowInput: false
                });
            }
        });
    </script>
@endpush
