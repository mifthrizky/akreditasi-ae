@extends('layouts.layout')

@section('content')

    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    @vite(['resources/js/sweetalerts.js'])
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Manajemen Kriteria</h1>
                <p class="text-slate-600 mt-1 text-sm sm:text-base">Kelola hierarki kriteria akreditasi</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="openImportModal()"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Import Excel
                </button>
                <button onclick="openCreateKriteria0Modal()"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kriteria Utama
                </button>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ $message }}',
                        confirmButtonColor: '#3b82f6',
                        timer: 3000,
                        timerProgressBar: true,
                        heightAuto: false
                    });
                });
            </script>
        @endif

        @if ($message = Session::get('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ $message }}',
                        confirmButtonColor: '#ef4444',
                        heightAuto: false
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const errors = @json($errors->all());
                    const errorMessage = errors.join('<br>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonColor: '#ef4444',
                        heightAuto: false
                    });
                });
            </script>
        @endif

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            @if ($kriterias->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left font-semibold text-slate-900 text-xs sm:text-sm">
                                    Kode</th>
                                <th
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left font-semibold text-slate-900 text-xs sm:text-sm">
                                    Nama</th>
                                <th
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left font-semibold text-slate-900 text-xs sm:text-sm">
                                    Level</th>
                                <th
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left font-semibold text-slate-900 text-xs sm:text-sm">
                                    Bobot</th>
                                <th
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left font-semibold text-slate-900 text-xs sm:text-sm">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriterias as $kriteria)
                                <tr
                                    class="level-0-row border-b border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors">
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-900 font-bold">{{ $kriteria->kode }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-900 font-semibold">{{ $kriteria->nama }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Level 0
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-700 font-medium">{{ $kriteria->bobot }}%
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <button
                                                class="btn-add-sub-kriteria inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                data-id="{{ $kriteria->kriteria_id }}" data-kode="{{ $kriteria->kode }}"
                                                data-nama="{{ $kriteria->nama }}">
                                                + Sub
                                            </button>

                                            <button
                                                class="btn-edit-kriteria inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none"
                                                data-id="{{ $kriteria->kriteria_id }}" data-kode="{{ $kriteria->kode }}"
                                                data-nama="{{ $kriteria->nama }}"
                                                data-deskripsi="{{ $kriteria->deskripsi }}"
                                                data-level="{{ $kriteria->level }}" data-bobot="{{ $kriteria->bobot }}"
                                                data-urutan="{{ $kriteria->urutan }}">
                                                Edit
                                            </button>

                                            <button
                                                class="btn-delete-kriteria inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none"
                                                data-id="{{ $kriteria->kriteria_id }}" data-kode="{{ $kriteria->kode }}"
                                                data-nama="{{ $kriteria->nama }}">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Level 1 Sub-Kriteria -->
                                @if ($kriteria->children->count() > 0)
                                    @foreach ($kriteria->children as $subKriteria)
                                        <tr
                                            class="level-1-row border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 text-slate-800 pl-12 font-medium">
                                                <span class="text-slate-400">├─ </span>{{ $subKriteria->kode }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-800 font-medium">{{ $subKriteria->nama }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Level 1
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-700">{{ $subKriteria->bobot }}%</td>
                                            <td class="px-6 py-4">
                                                <div class="flex gap-2">
                                                    <button
                                                        class="btn-add-sub-kriteria inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                        data-id="{{ $subKriteria->kriteria_id }}"
                                                        data-kode="{{ $subKriteria->kode }}"
                                                        data-nama="{{ $subKriteria->nama }}">
                                                        + Sub
                                                    </button>

                                                    <button
                                                        class="btn-edit-kriteria inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                        data-id="{{ $subKriteria->kriteria_id }}"
                                                        data-kode="{{ $subKriteria->kode }}"
                                                        data-nama="{{ $subKriteria->nama }}"
                                                        data-deskripsi="{{ $subKriteria->deskripsi }}"
                                                        data-level="{{ $subKriteria->level }}"
                                                        data-bobot="{{ $subKriteria->bobot }}"
                                                        data-urutan="{{ $subKriteria->urutan }}"
                                                        data-parent_id="{{ $subKriteria->parent_id }}">
                                                        Edit
                                                    </button>

                                                    <button
                                                        class="btn-delete-kriteria inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                        data-id="{{ $subKriteria->kriteria_id }}"
                                                        data-kode="{{ $subKriteria->kode }}"
                                                        data-nama="{{ $subKriteria->nama }}">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Level 2 Sub-Kriteria -->
                                        @if ($subKriteria->children->count() > 0)
                                            @foreach ($subKriteria->children as $subKriteriaLvl2)
                                                <tr
                                                    class="level-2-row border-b border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                                                    <td class="px-6 py-4 text-slate-600 pl-20">
                                                        <span class="text-slate-300">└─ </span>{{ $subKriteriaLvl2->kode }}
                                                    </td>
                                                    <td class="px-6 py-4 text-slate-600 whitespace-normal min-w-[200px]">
                                                        {{ $subKriteriaLvl2->nama }}</td>
                                                    <td class="px-6 py-4">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            Level 2
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-slate-600">{{ $subKriteriaLvl2->bobot }}%
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex gap-2">
                                                            <button
                                                                class="btn-config-template inline-flex items-center px-3 py-1.5 text-sm font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 transition-colors focus:outline-none hover:rounded-xl"
                                                                data-id="{{ $subKriteriaLvl2->kriteria_id }}"
                                                                data-nama="{{ $subKriteriaLvl2->nama }}">
                                                                Template
                                                            </button>

                                                            <button
                                                                class="btn-edit-kriteria inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                                data-id="{{ $subKriteriaLvl2->kriteria_id }}"
                                                                data-kode="{{ $subKriteriaLvl2->kode }}"
                                                                data-nama="{{ $subKriteriaLvl2->nama }}"
                                                                data-deskripsi="{{ $subKriteriaLvl2->deskripsi }}"
                                                                data-level="{{ $subKriteriaLvl2->level }}"
                                                                data-bobot="{{ $subKriteriaLvl2->bobot }}"
                                                                data-urutan="{{ $subKriteriaLvl2->urutan }}"
                                                                data-parent_id="{{ $subKriteriaLvl2->parent_id }}">
                                                                Edit
                                                            </button>

                                                            <button
                                                                class="btn-delete-kriteria inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none hover:rounded-xl"
                                                                data-id="{{ $subKriteriaLvl2->kriteria_id }}"
                                                                data-kode="{{ $subKriteriaLvl2->kode }}"
                                                                data-nama="{{ $subKriteriaLvl2->nama }}">
                                                                Hapus
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada kriteria</h3>
                    <p class="text-slate-600 mb-4">Mulai dengan membuat kriteria utama</p>
                    <button onclick="openCreateKriteria0Modal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        Tambah Kriteria Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- CREATE/EDIT KRITERIA MODAL (Level 0) -->
    <div id="createKriteria0Modal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeModal('createKriteria0Modal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 id="modalTitle0" class="text-lg font-semibold text-slate-900">Tambah Kriteria Utama</h3>
                <button onclick="closeModal('createKriteria0Modal')"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="formKriteria0" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="kriteriaId0" name="_method" value="POST">
                <div class="space-y-4">
                    <div>
                        <label for="create_kode0" class="block text-sm font-medium text-slate-700 mb-1">Kode</label>
                        <input type="text" id="create_kode0" name="kode" placeholder="Masukkan kode kriteria"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_nama0" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                        <input type="text" id="create_nama0" name="nama" placeholder="Masukkan nama kriteria"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_deskripsi0"
                            class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea id="create_deskripsi0" name="deskripsi" placeholder="Masukkan deskripsi kriteria"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="3"></textarea>
                    </div>
                    <div>
                        <label for="create_bobot0" class="block text-sm font-medium text-slate-700 mb-1">Bobot (%)</label>
                        <input type="number" id="create_bobot0" name="bobot" step="0.01" min="0"
                            placeholder="0-100" max="100" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_urutan0" class="block text-sm font-medium text-slate-700 mb-1">Urutan</label>
                        <input type="number" id="create_urutan0" name="urutan" min="1" placeholder="Contoh: 1"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <input type="hidden" name="level" value="0">
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('createKriteria0Modal')"
                        class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CREATE/EDIT KRITERIA MODAL (Level 1 - Sub-Kriteria) -->
    <div id="createKriteria1Modal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeModal('createKriteria1Modal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 id="modalTitle1" class="text-lg font-semibold text-slate-900">Tambah Sub-Kriteria</h3>
                <button onclick="closeModal('createKriteria1Modal')"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="formKriteria1" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="kriteriaId1" name="_method" value="POST">
                <div class="space-y-4">
                    <div>
                        <label for="create_kode1" class="block text-sm font-medium text-slate-700 mb-1">Kode</label>
                        <input type="text" id="create_kode1" name="kode" placeholder="Masukkan kode sub-kriteria"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_nama1" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                        <input type="text" id="create_nama1" name="nama" placeholder="Masukkan nama sub-kriteria"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_deskripsi1"
                            class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea id="create_deskripsi1" name="deskripsi" placeholder="Masukkan deskripsi sub-kriteria"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="3"></textarea>
                    </div>
                    <div>
                        <label for="create_bobot1" class="block text-sm font-medium text-slate-700 mb-1">Bobot (%)</label>
                        <input type="number" id="create_bobot1" name="bobot" step="0.01" min="0"
                            placeholder="0-100" max="100" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_urutan1" class="block text-sm font-medium text-slate-700 mb-1">Urutan</label>
                        <input type="number" id="create_urutan1" name="urutan" min="1" placeholder="Contoh: 1"
                            required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <input type="hidden" name="level" id="input_level1" value="1">
                    <input type="hidden" id="parent_id1" name="parent_id">
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('createKriteria1Modal')"
                        class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Helper functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }

        function openCreateKriteria0Modal() {
            document.getElementById('modalTitle0').textContent = 'Tambah Kriteria Utama';
            document.getElementById('formKriteria0').reset();
            document.getElementById('formKriteria0').action = "{{ route('admin.kriteria.store') }}";
            document.getElementById('kriteriaId0').value = 'POST'; // _method
            openModal('createKriteria0Modal');
        }

        // Gunakan Event Delegation
        document.addEventListener('click', function(e) {
            // Add Sub-Kriteria
            const btnAddSub = e.target.closest('.btn-add-sub-kriteria');
            if (btnAddSub) {
                const parentId = btnAddSub.getAttribute('data-id');
                const parentKode = btnAddSub.getAttribute('data-kode');
                const parentNama = btnAddSub.getAttribute('data-nama');

                // Find the parent row
                const parentRow = btnAddSub.closest('tr');
                const isLevel0 = parentRow.classList.contains('level-0-row');
                const isLevel1 = parentRow.classList.contains('level-1-row');

                // Find all child rows
                let currentRow = parentRow.nextElementSibling;
                let childRows = [];

                while (currentRow) {
                    if (isLevel0) {
                        if (currentRow.classList.contains('level-0-row')) break; // Stop at next Level 0
                        if (currentRow.classList.contains('level-1-row')) childRows.push(
                            currentRow); // Collect Level 1s
                    } else if (isLevel1) {
                        if (currentRow.classList.contains('level-0-row') || currentRow.classList.contains(
                                'level-1-row')) break; // Stop at next Level 0 or 1
                        if (currentRow.classList.contains('level-2-row')) childRows.push(
                            currentRow); // Collect Level 2s
                    } else {
                        break;
                    }
                    currentRow = currentRow.nextElementSibling;
                }

                // Calculate next code number and urutan
                let nextCodeNum = 1;
                if (childRows.length > 0) {
                    const lastChildRow = childRows[childRows.length - 1];
                    const codeCell = lastChildRow.querySelector('td:first-child').textContent.trim();
                    const lastChildCode = codeCell.replace(/^[├─└\s]+/, '').trim();
                    const match = lastChildCode.match(/\.(\d+)$/);
                    if (match) {
                        nextCodeNum = parseInt(match[1]) + 1;
                    }
                }

                const nextCode = `${parentKode}.${String(nextCodeNum).padStart(isLevel1 ? 1 : 2, '0')}`;
                const nextUrutan = childRows.length + 1;

                document.getElementById('modalTitle1').textContent = `Tambah Sub-Kriteria dari "${parentKode}"`;
                document.getElementById('formKriteria1').reset();
                document.getElementById('formKriteria1').action = "{{ route('admin.kriteria.store') }}";
                document.getElementById('kriteriaId1').value = 'POST'; // _method
                document.getElementById('parent_id1').value = parentId;

                document.getElementById('create_kode1').value = nextCode;
                document.getElementById('create_urutan1').value = nextUrutan;
                document.getElementById('input_level1').value = isLevel1 ? 2 : 1;

                openModal('createKriteria1Modal');
                return;
            }

            // Edit Kriteria
            const btnEdit = e.target.closest('.btn-edit-kriteria');
            if (btnEdit) {
                const id = btnEdit.getAttribute('data-id');
                const kode = btnEdit.getAttribute('data-kode');
                const nama = btnEdit.getAttribute('data-nama');
                const deskripsi = btnEdit.getAttribute('data-deskripsi') || '';
                const level = btnEdit.getAttribute('data-level');
                const bobot = btnEdit.getAttribute('data-bobot');
                const urutan = btnEdit.getAttribute('data-urutan');
                const parentId = btnEdit.getAttribute('data-parent_id') || '';

                if (level == 0) {
                    document.getElementById('modalTitle0').textContent = 'Edit Kriteria Utama';
                    document.getElementById('formKriteria0').action = `{{ url('/admin/kriteria') }}/${id}`;
                    document.getElementById('kriteriaId0').value = 'PUT'; // _method

                    document.getElementById('create_kode0').value = kode;
                    document.getElementById('create_nama0').value = nama;
                    document.getElementById('create_deskripsi0').value = deskripsi;
                    document.getElementById('create_bobot0').value = bobot;
                    document.getElementById('create_urutan0').value = urutan;

                    openModal('createKriteria0Modal');
                } else {
                    document.getElementById('modalTitle1').textContent = 'Edit Sub-Kriteria';
                    document.getElementById('formKriteria1').action = `{{ url('/admin/kriteria') }}/${id}`;
                    document.getElementById('kriteriaId1').value = 'PUT'; // _method

                    document.getElementById('create_kode1').value = kode;
                    document.getElementById('create_nama1').value = nama;
                    document.getElementById('create_deskripsi1').value = deskripsi;
                    document.getElementById('create_bobot1').value = bobot;
                    document.getElementById('create_urutan1').value = urutan;
                    document.getElementById('parent_id1').value = parentId;
                    document.getElementById('input_level1').value = level;

                    openModal('createKriteria1Modal');
                }
                return;
            }

            // Delete Kriteria
            const btnDelete = e.target.closest('.btn-delete-kriteria');
            if (btnDelete) {
                const id = btnDelete.getAttribute('data-id');
                const nama = btnDelete.getAttribute('data-nama');

                if (typeof showDeleteConfirmation === 'function') {
                    showDeleteConfirmation(nama).then((result) => {
                        if (result.isConfirmed) {
                            if (typeof showLoadingAlert === 'function') showLoadingAlert('Menghapus...');
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('/admin/kriteria') }}/${id}`;

                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                                '';
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                } else {
                    // Fallback
                    if (confirm(`Apakah Anda yakin ingin menghapus kriteria ${nama}?`)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('/admin/kriteria') }}/${id}`;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
                return;
            }

            // Config Template
            const btnConfig = e.target.closest('.btn-config-template');
            if (btnConfig) {
                const id = btnConfig.getAttribute('data-id');
                window.location.href = `{{ url('/admin/kriteria') }}/${id}/template`;
                return;
            }
        });

        // Handle Form Submit
        function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const method = form.querySelector('input[name="_method"]').value;

            if (typeof showLoadingAlert === 'function') {
                showLoadingAlert(method === 'PUT' ? 'Menyimpan...' : 'Menambahkan...');
            }

            if (method === 'PUT') {
                const formData = new FormData(form);
                const url = form.action;

                fetch(url, {
                    method: 'POST', // Native POST carrying _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        if (typeof showSuccessAlert === 'function') {
                            showSuccessAlert('Berhasil!', 'Kriteria berhasil diperbarui').then(() => {
                                location.reload();
                            });
                        } else {
                            location.reload();
                        }
                    } else {
                        // Coba untuk fallback submit normal jika fetch gagal
                        form.submit();
                    }
                }).catch(() => {
                    form.submit();
                });
            } else {
                form.submit();
            }
        }

        document.getElementById('formKriteria0')?.addEventListener('submit', handleFormSubmit);
        document.getElementById('formKriteria1')?.addEventListener('submit', handleFormSubmit);

        // Import Modal Functions
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('importModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });

        // Tutup Modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['createKriteria0Modal', 'createKriteria1Modal', 'importModal'].forEach(id => {
                    const modal = document.getElementById(id);
                    if (modal && !modal.classList.contains('hidden')) {
                        if (id === 'importModal') {
                            closeImportModal();
                        } else {
                            closeModal(id);
                        }
                    }
                });
            }
        });
    </script>

    <!-- Import Modal -->
    <div id="importModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeImportModal()">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Import Data Kriteria</h3>
                <button onclick="closeImportModal()"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-500 mb-4">Upload file Excel sesuai template yang telah disediakan.</p>

                <!-- Download Template Button -->
                <a href="{{ route('admin.kriteria.download-template') }}"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 focus:outline-none transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Template Excel
                </a>

                <!-- Import Form -->
                <form action="{{ route('admin.kriteria.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih File Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer"/>
                        <p class="mt-1 text-xs text-slate-500">Format: .xlsx, .xls, .csv (Max: 2MB)</p>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                            Import Data
                        </button>
                        <button type="button" onclick="closeImportModal()"
                            class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
