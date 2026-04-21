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
                <h1 class="text-3xl font-bold text-slate-900">Konfigurasi Template</h1>
                <p class="text-slate-600 mt-1 text-base">Kelola template item untuk: <span
                        class="font-semibold">{{ $kriteria->nama }}</span></p>
                <p class="text-slate-500 text-sm mt-1">Bobot: {{ $kriteria->bobot }}%</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.kriteria.index') }}"
                    class="inline-flex items-center px-4 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <button id="btnAddTemplate"
                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Template Item
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
            @if ($templateItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Urutan</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Tipe</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Label</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Hint</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Bobot</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Wajib</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templateItems as $item)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-900 font-medium">{{ $item->urutan }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->tipe == 'checklist')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ☑ Checklist
                                            </span>
                                        @elseif ($item->tipe == 'upload')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                📤 Upload
                                            </span>
                                        @elseif ($item->tipe == 'numerik')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🔢 Numerik
                                            </span>
                                        @elseif ($item->tipe == 'narasi')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                📝 Narasi
                                            </span>
                                        @elseif ($item->tipe == 'select')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                                ▼ Select
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->label }}</td>
                                    <td class="px-6 py-4 text-slate-700 text-xs max-w-xs truncate"
                                        title="{{ $item->hint }}">
                                        {{ $item->hint ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->bobot ? $item->bobot . '%' : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->wajib)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                                Ya
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded">
                                                Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button
                                                class="btn-edit-template inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors focus:outline-none"
                                                data-id="{{ $item->template_id }}" data-tipe="{{ $item->tipe }}"
                                                data-label="{{ $item->label }}" data-hint="{{ $item->hint }}"
                                                data-bobot="{{ $item->bobot }}" data-urutan="{{ $item->urutan }}"
                                                data-wajib="{{ $item->wajib }}"
                                                data-nilai_min_numerik="{{ $item->nilai_min_numerik }}">
                                                Edit
                                            </button>

                                            <button
                                                class="btn-delete-template inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors focus:outline-none"
                                                data-id="{{ $item->template_id }}" data-label="{{ $item->label }}">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada template item</h3>
                    <p class="text-slate-600 mb-4">Mulai dengan menambahkan template item pertama</p>
                    <button id="btnAddTemplateEmpty"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        Tambah Template Item Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- CREATE/EDIT TEMPLATE ITEM MODAL -->
    <div id="createTemplateModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto"
        onclick="if(event.target === this) closeModal('createTemplateModal')">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full my-8" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 id="modalTitle" class="text-lg font-semibold text-slate-900">Tambah Template Item</h3>
                <button onclick="closeModal('createTemplateModal')"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="formTemplate" method="POST" action="{{ route('admin.template-items.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="kriteria_id" value="{{ $kriteria->kriteria_id }}">
                <input type="hidden" id="remainingBobot" value="0">
                <div class="space-y-4">
                    <div id="bobotInfoBox" class="p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <p class="text-sm text-blue-700">
                            <span class="font-medium">Bobot Kriteria:</span> {{ $kriteria->bobot }}%
                        </p>
                        <p class="text-sm text-blue-700">
                            <span class="font-medium">Bobot Terpakai:</span> <span id="usedBobotDisplay">0</span>%
                        </p>
                        <p class="text-sm text-blue-700 font-semibold">
                            <span class="font-medium">Sisa Bobot:</span> <span
                                id="remainingBobotDisplay">{{ $kriteria->bobot }}</span>%
                        </p>
                    </div>
                    <div>
                        <label for="template_tipe" class="block text-sm font-medium text-slate-700 mb-1">Tipe Template
                            <span class="text-red-500">*</span></label>
                        <select id="template_tipe" name="tipe" required onchange="updateTipeInfo()"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="checklist">☑ Checklist</option>
                            <option value="upload">📤 Upload</option>
                            <option value="numerik">🔢 Numerik</option>
                            <option value="narasi">📝 Narasi</option>
                            <option value="select">▼ Select</option>
                        </select>
                    </div>
                    <div>
                        <label for="template_label" class="block text-sm font-medium text-slate-700 mb-1">Label <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="template_label" name="label" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="template_hint"
                            class="block text-sm font-medium text-slate-700 mb-1">Hint/Petunjuk</label>
                        <textarea id="template_hint" name="hint"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="2" placeholder="Opsional - petunjuk pengisian"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="template_bobot" class="block text-sm font-medium text-slate-700 mb-1">Bobot
                                (%)</label>
                            <input type="number" id="template_bobot" name="bobot" step="0.01" min="0"
                                max="100"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="0" />
                        </div>
                        <div>
                            <label for="template_urutan" class="block text-sm font-medium text-slate-700 mb-1">Urutan
                                <span class="text-red-500">*</span></label>
                            <input type="number" id="template_urutan" name="urutan" min="1" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>
                    </div>
                    <div id="nilaiMinNumerikGroup" class="hidden">
                        <label for="template_nilai_min_numerik"
                            class="block text-sm font-medium text-slate-700 mb-1">Nilai Minimum (Numerik)</label>
                        <input type="number" id="template_nilai_min_numerik" name="nilai_min_numerik" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="0" />
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="wajib" value="0">
                        <input type="checkbox" id="template_wajib" name="wajib" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500" />
                        <label for="template_wajib" class="ml-2 text-sm font-medium text-slate-700">Wajib diisi</label>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('createTemplateModal')"
                        class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Constants
        const KRITERIA_BOBOT = {{ $kriteria->bobot }};
        const EXISTING_ITEMS = @json($templateItems->map(fn($item) => ['id' => $item->template_id, 'bobot' => $item->bobot])->toArray());

        // Helper functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function calculateUsedBobot(excludeId = null) {
            return EXISTING_ITEMS
                .filter(item => excludeId === null || item.id !== parseInt(excludeId))
                .reduce((sum, item) => sum + parseFloat(item.bobot || 0), 0);
        }

        function updateBobotDisplay(excludeId = null) {
            const usedBobot = calculateUsedBobot(excludeId);
            const remainingBobot = KRITERIA_BOBOT - usedBobot;

            document.getElementById('usedBobotDisplay').textContent = usedBobot.toFixed(2);
            document.getElementById('remainingBobotDisplay').textContent = remainingBobot.toFixed(2);
            document.getElementById('remainingBobot').value = remainingBobot.toFixed(2);
            document.getElementById('bobotInfoBox').classList.remove('hidden');
        }

        function updateTipeInfo() {
            const tipe = document.getElementById('template_tipe').value;
            const nilaiMinGroup = document.getElementById('nilaiMinNumerikGroup');

            if (tipe === 'numerik') {
                nilaiMinGroup.classList.remove('hidden');
            } else {
                nilaiMinGroup.classList.add('hidden');
                document.getElementById('template_nilai_min_numerik').value = '';
            }
        }

        // Edit Template Item
        document.querySelectorAll('.btn-edit-template').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const tipe = this.getAttribute('data-tipe');
                const label = this.getAttribute('data-label');
                const hint = this.getAttribute('data-hint');
                const bobot = this.getAttribute('data-bobot');
                const urutan = this.getAttribute('data-urutan');
                const wajib = this.getAttribute('data-wajib');
                const nilaiMinNumerik = this.getAttribute('data-nilai_min_numerik');

                document.getElementById('modalTitle').textContent = 'Edit Template Item';
                document.getElementById('formTemplate').action = `{{ url('/template-items') }}/${id}`;
                document.getElementById('formTemplate').querySelector('input[name="_method"]').value =
                    'PUT';
                document.getElementById('template_tipe').value = tipe;
                document.getElementById('template_label').value = label;
                document.getElementById('template_hint').value = hint;
                document.getElementById('template_bobot').value = bobot;
                document.getElementById('template_urutan').value = urutan;
                document.getElementById('template_wajib').checked = wajib == 1;
                document.getElementById('template_nilai_min_numerik').value = nilaiMinNumerik;

                updateTipeInfo();
                updateBobotDisplay(id); // Exclude current item from calculation
                openModal('createTemplateModal');
            });
        });

        // Delete Template Item
        document.querySelectorAll('.btn-delete-template').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const label = this.getAttribute('data-label');

                showDeleteConfirmation(label).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingAlert('Menghapus...');
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('/template-items') }}/${id}`;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            ?.content || '';
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Form submission
        let isSubmitting = false;
        document.getElementById('formTemplate').addEventListener('submit', function(e) {
            e.preventDefault();

            if (isSubmitting) return;

            const bobot = parseFloat(document.getElementById('template_bobot').value) || 0;
            const remainingBob = parseFloat(document.getElementById('remainingBobot').value) || 0;

            // Validate bobot
            if (bobot > remainingBob) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Bobot Melebihi Batas!',
                    html: `Bobot template item <strong>${bobot}%</strong> tidak boleh melebihi sisa bobot <strong>${remainingBob}%</strong>`,
                    confirmButtonColor: '#f59e0b',
                    heightAuto: false
                });
                return;
            }

            isSubmitting = true;

            const method = this.querySelector('input[name="_method"]').value;
            const url = this.action;
            const formData = new FormData(this);

            // Manually set checkbox value
            const isWajib = document.getElementById('template_wajib').checked;
            formData.set('wajib', isWajib ? 1 : 0);

            // console.log('Submitting form:', {
            //     method,
            //     url,
            //     formData: Object.fromEntries(formData)
            // });

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                heightAuto: false,
                didOpen: (modal) => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formData)
            }).then(response => {
                console.log('Response status:', response.status);
                if (response.ok) {
                    console.log('Success, showing success alert');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan',
                        confirmButtonColor: '#3b82f6',
                        heightAuto: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        isSubmitting = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: text || 'Terjadi kesalahan saat menyimpan data',
                            confirmButtonColor: '#ef4444',
                            heightAuto: false
                        });
                    });
                }
            }).catch(error => {
                isSubmitting = false;
                console.error('Fetch error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Network!',
                    text: error.message,
                    confirmButtonColor: '#ef4444',
                    heightAuto: false
                });
            });
        });

        // Reset form when modal closes
        const modal = document.getElementById('createTemplateModal');
        const observer = new MutationObserver(() => {
            if (modal.classList.contains('hidden')) {
                document.getElementById('formTemplate').reset();
                document.getElementById('modalTitle').textContent = 'Tambah Template Item';
                document.getElementById('formTemplate').querySelector('input[name="_method"]').value = 'POST';
                document.getElementById('formTemplate').action = "{{ route('admin.template-items.store') }}";
                updateTipeInfo();
                document.getElementById('bobotInfoBox').classList.add('hidden');
            }
        });
        observer.observe(modal, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Add button click handlers
        document.getElementById('btnAddTemplate').addEventListener('click', function() {
            updateBobotDisplay();
            openModal('createTemplateModal');
        });

        if (document.getElementById('btnAddTemplateEmpty')) {
            document.getElementById('btnAddTemplateEmpty').addEventListener('click', function() {
                updateBobotDisplay();
                openModal('createTemplateModal');
            });
        }
    </script>

@endsection
