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
                <h1 class="text-3xl font-bold text-slate-900">Manajemen User</h1>
                <p class="text-slate-600 mt-1 text-base">Kelola semua pengguna sistem akreditasi</p>
            </div>
            <button onclick="openModal('createModal')"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User
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
            @if ($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Nama</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Email</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Role</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Prodi</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-900 font-medium">{{ $user->nama }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($user->role === 'admin') bg-blue-100 text-blue-800
                                        @elseif ($user->role === 'dosen')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-yellow-100 text-yellow-800 @endif
                                    ">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->prodis && $user->prodis->count() > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $user->prodis->first()->kode }}
                                            </span>
                                        @else
                                            <span class="text-slate-500 italic text-xs">Belum ada prodi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button
                                                class="btn-assign-prodi inline-flex items-center px-3 py-1.5 text-sm font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition-colors focus:outline-none"
                                                data-id="{{ $user->user_id }}" data-nama="{{ $user->nama }}">
                                                Assign Prodi
                                            </button>

                                            <button
                                                class="btn-edit-user inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none"
                                                data-id="{{ $user->user_id }}" data-nama="{{ $user->nama }}"
                                                data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                                Edit
                                            </button>

                                            <button
                                                class="btn-delete-user inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none"
                                                data-id="{{ $user->user_id }}" data-nama="{{ $user->nama }}">
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
                        Menampilkan <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span> hingga
                        <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span> dari
                        <span class="font-medium">{{ $users->total() ?? 0 }}</span> hasil
                    </div>

                    <div class="flex">
                        {{ $users->render('vendor.pagination.custom') }}
                    </div>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M11 10h.01M7 10h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada user</h3>
                    <p class="text-slate-600 mb-4">Mulai dengan membuat user baru</p>
                    <button onclick="openModal('createModal')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Tambah User Pertama
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
                <h3 class="text-lg font-semibold text-slate-900">Tambah User Baru</h3>
                <button onclick="closeModal('createModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="createForm" action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="create_nama" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                        <input type="text" id="create_nama" name="nama" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" id="create_email" name="email" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                        <select id="create_role" name="role" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="admin">Admin</option>
                            <option value="dosen">Dosen</option>
                            <option value="validator">Validator</option>
                        </select>
                    </div>
                    <div>
                        <label for="create_password"
                            class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" id="create_password" name="password" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="create_password_confirm"
                            class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" id="create_password_confirm" name="password_confirmation" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
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

    <!-- ASSIGN PRODI MODAL -->
    <div id="assignProdiModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeModal('assignProdiModal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Assign Program Studi ke User</h3>
                <button onclick="closeModal('assignProdiModal')"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="assignProdiForm" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-2 block">User: <span id="assignProdiUserName"
                                class="font-semibold text-slate-900"></span></label>
                    </div>
                    <div>
                        <label for="assignProdiSelect" class="block text-sm font-medium text-slate-700 mb-2">Pilih Program
                            Studi:</label>
                        <select id="assignProdiSelect" name="prodi_id" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudis ?? [] as $prodi)
                                <option value="{{ $prodi->prodi_id }}">{{ $prodi->kode }} - {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Assign
                    </button>
                    <button type="button" onclick="closeModal('assignProdiModal')"
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
                <h3 class="text-lg font-semibold text-slate-900">Edit User</h3>
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
                        <label for="edit_nama" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                        <input type="text" id="edit_nama" name="nama" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" id="edit_email" name="email" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="edit_role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                        <select id="edit_role" name="role" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="admin">Admin</option>
                            <option value="dosen">Dosen</option>
                            <option value="validator">Validator</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-slate-700 mb-1">Password
                            (Kosongkan jika tidak diubah)</label>
                        <input type="password" id="edit_password" name="password"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
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

        function openEditModal(userId, nama, email, role) {
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editForm').action = `/users/${userId}`;
            openModal('editModal');
        }

        function openAssignProdiModal(userId, nama) {
            document.getElementById('assignProdiUserName').textContent = nama;
            document.getElementById('assignProdiForm').dataset.userId = userId;
            document.getElementById('assignProdiForm').action = `/admin/users/${userId}/assign-prodi`;
            document.getElementById('assignProdiSelect').value = '';
            openModal('assignProdiModal');
        }

        // Gunakan Event Delegation
        document.addEventListener('click', function(e) {
            // Tombol Assign Prodi
            const btnAssignProdi = e.target.closest('.btn-assign-prodi');
            if (btnAssignProdi) {
                openAssignProdiModal(btnAssignProdi.dataset.id, btnAssignProdi.dataset.nama);
                return;
            }

            // Tombol Edit
            const btnEdit = e.target.closest('.btn-edit-user');
            if (btnEdit) {
                openEditModal(btnEdit.dataset.id, btnEdit.dataset.nama, btnEdit.dataset.email, btnEdit.dataset
                    .role);
                return;
            }

            // Tombol Hapus
            const btnDelete = e.target.closest('.btn-delete-user');
            if (btnDelete) {
                if (typeof deleteUserWithConfirmation === 'function') {
                    deleteUserWithConfirmation(btnDelete.dataset.id, btnDelete.dataset.nama);
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
            if (e.target.id === 'assignProdiForm') {
                e.preventDefault();
                const selectedProdi = document.getElementById('assignProdiSelect').value;
                if (!selectedProdi) {
                    Swal.fire('Peringatan', 'Silahkan pilih Program Studi', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin assign program studi ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#a855f7',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Assign',
                    cancelButtonText: 'Batal',
                    heightAuto: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (typeof showLoadingAlert === 'function') showLoadingAlert('Mengassign...');
                        e.target.submit();
                    }
                });
            }
        });

        // Tutup Modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['createModal', 'assignProdiModal', 'editModal'].forEach(id => closeModal(id));
            }
        });
    </script>
@endsection
