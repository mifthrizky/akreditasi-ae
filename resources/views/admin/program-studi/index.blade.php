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
                <h1 class="text-3xl font-bold text-slate-900">Daftar Program Studi</h1>
                <p class="text-slate-600 mt-1 text-base">Kelola semua program studi di institusi</p>
            </div>
            <button onclick="openModal('createModal')"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Program Studi
            </button>
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

        <!-- Table Container -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            @if ($programs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Kode</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Nama Program Studi</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Jurusan</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $program)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-900 font-medium">{{ $program->kode }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $program->nama }}</td>
                                    <td class="px-6 py-4 text-slate-700">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($program->jurusan === 'ME') bg-blue-100 text-blue-800
                                        @elseif ($program->jurusan === 'AE')
                                            bg-green-100 text-green-800
                                        @elseif ($program->jurusan === 'DE')
                                            bg-yellow-100 text-yellow-800 
                                        @else
                                            bg-red-100 text-red-800 @endif
                                    ">
                                            {{ ucfirst($program->jurusan) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button
                                                class="btn-edit-program inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none"
                                                data-id="{{ $program->prodi_id }}" data-kode="{{ $program->kode }}"
                                                data-nama="{{ $program->nama }}" data-jurusan="{{ $program->jurusan }}">
                                                Edit
                                            </button>

                                            <button
                                                class="btn-delete-program inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none"
                                                data-id="{{ $program->prodi_id }}" data-nama="{{ $program->nama }}">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Menampilkan <span class="font-medium">{{ $programs->firstItem() ?? 0 }}</span> hingga
                        <span class="font-medium">{{ $programs->lastItem() ?? 0 }}</span> dari
                        <span class="font-medium">{{ $programs->total() ?? 0 }}</span> hasil
                    </div>

                    <div class="flex">
                        {{ $programs->render('vendor.pagination.custom') }}
                    </div>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada program studi</h3>
                    <p class="text-slate-600 mb-4">Mulai dengan membuat program studi baru</p>
                    <button onclick="openModal('createModal')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Tambah Program Studi Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div id="createModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeModal('createModal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Tambah Program Studi Baru</h3>
                <button onclick="closeModal('createModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="createForm" action="{{ route('admin.program-studi.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="create_kode" class="block text-sm font-medium text-slate-700 mb-1">Kode Program
                            Studi</label>
                        <input type="text" id="create_kode" name="kode" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Program
                            Studi</label>
                        <input type="text" id="create_nama" name="nama" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_jurusan" class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                        <select id="create_jurusan" name="jurusan" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="" disabled selected>Pilih Jurusan</option>
                            <option value="ME">ME</option>
                            <option value="FE">FE</option>
                            <option value="DE">DE</option>
                            <option value="AE">AE</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('createModal')"
                        class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeModal('editModal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Edit Program Studi</h3>
                <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_kode" class="block text-sm font-medium text-slate-700 mb-1">Kode Program
                            Studi</label>
                        <input type="text" id="edit_kode" name="kode" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Program
                            Studi</label>
                        <input type="text" id="edit_nama" name="nama" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="edit_jurusan" class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                        <select id="edit_jurusan" name="jurusan" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="" disabled selected>Pilih Jurusan</option>
                            <option value="ME">ME</option>
                            <option value="FE">FE</option>
                            <option value="DE">DE</option>
                            <option value="AE">AE</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('editModal')"
                        class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }

        function openEditModal(programId, kode, nama, jurusan) {
            document.getElementById('edit_kode').value = kode;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jurusan').value = jurusan;
            document.getElementById('editForm').action = `/program-studi/${programId}`;
            openModal('editModal');
        }

        // Gunakan Event Delegation
        document.addEventListener('click', function(e) {
            // Tombol Edit
            const btnEdit = e.target.closest('.btn-edit-program');
            if (btnEdit) {
                openEditModal(btnEdit.dataset.id, btnEdit.dataset.kode, btnEdit.dataset.nama, btnEdit.dataset
                    .jurusan);
                return;
            }

            // Tombol Hapus
            const btnDelete = e.target.closest('.btn-delete-program');
            if (btnDelete) {
                if (typeof showDeleteConfirmation === 'function') {
                    showDeleteConfirmation(btnDelete.dataset.nama).then((result) => {
                        if (result.isConfirmed) {
                            if (typeof showLoadingAlert === 'function') showLoadingAlert('Menghapus...');
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/program-studi/${btnDelete.dataset.id}`;
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
                    Swal.fire('Error', 'Fungsi hapus belum dimuat. Cek file sweetalerts.js', 'error');
                }
            }
        });

        // Handle Form Submit
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'createForm') {
                e.preventDefault();
                if (typeof showCreateConfirmation === 'function') {
                    showCreateConfirmation().then((res) => {
                        if (res.isConfirmed) {
                            if (typeof showLoadingAlert === 'function') showLoadingAlert('Menambahkan...');
                            e.target.submit();
                        }
                    });
                }
            }
            if (e.target.id === 'editForm') {
                e.preventDefault();
                if (typeof showEditConfirmation === 'function') {
                    showEditConfirmation().then((res) => {
                        if (res.isConfirmed) {
                            if (typeof showLoadingAlert === 'function') showLoadingAlert('Menyimpan...');
                            e.target.submit();
                        }
                    });
                }
            }
        });

        // Tutup Modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['createModal', 'editModal'].forEach(id => closeModal(id));
            }
        });
    </script>
@endsection
