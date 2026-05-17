@extends('layouts.layout')

@section('content')
    <div class="flex flex-col h-full overflow-hidden p-2 sm:p-4 md:p-8">
        {{-- Header --}}
        <div class="mb-6 flex-none">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-bold text-lg">DANGER ZONE - Super Admin Only</h3>
                        <p class="text-red-700 mt-1">
                            Fitur ini akan menghapus <strong>SELURUH DATA SUBMISSION</strong> dari program studi yang
                            dipilih.
                            Data yang dihapus: submissions, dokumen, validasi, dan laporan.
                            <strong>Master data prodi TIDAK akan dihapus.</strong>
                        </p>
                    </div>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-slate-900">Hapus Data Program Studi</h1>
            <p class="text-slate-600 mt-1">Pilih program studi untuk menghapus seluruh data submission-nya</p>
        </div>

        @if (session('success'))
            <div class="mb-4 flex-none p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                <strong>✓ Berhasil:</strong> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 flex-none p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <strong>✗ Error:</strong> {{ session('error') }}
            </div>
        @endif

        {{-- Prodi Selection --}}
        <div class="bg-white rounded-xl border border-slate-300 shadow-sm p-6 mb-6">
            <label class="block text-sm font-bold text-slate-900 mb-2">Pilih Program Studi</label>
            <select id="prodiSelect"
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Pilih Program Studi --</option>
                @foreach ($prodis as $prodi)
                    <option value="{{ $prodi->prodi_id }}" data-nama="{{ $prodi->nama }}">
                        {{ $prodi->nama }} ({{ $prodi->kode }})
                    </option>
                @endforeach
            </select>

            <button id="btnPreview" disabled
                class="mt-4 px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors">
                Preview Data yang Akan Dihapus
            </button>
        </div>

        {{-- Preview Modal --}}
        <div id="previewModal"
            class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-2xl font-bold text-slate-900">Preview Penghapusan Data</h2>
                    <p class="text-slate-600 mt-1">Program Studi: <span id="modalProdiName" class="font-bold"></span></p>
                </div>

                <div class="p-6">
                    {{-- Blocking Alert (if has pending review) --}}
                    <div id="blockingAlert" class="hidden bg-red-50 border-2 border-red-300 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-red-900 font-bold text-lg">❌ TIDAK DAPAT DIHAPUS</h3>
                                <p class="text-red-800 mt-2" id="blockingReason"></p>
                                <div class="mt-3">
                                    <p class="text-red-800 font-semibold mb-2">Submission yang belum direview:</p>
                                    <ul id="pendingList" class="list-disc list-inside text-sm text-red-700 space-y-1"></ul>
                                </div>
                                <p class="text-red-700 text-sm mt-3">
                                    Silakan review semua submission terlebih dahulu atau ubah statusnya sebelum menghapus
                                    data prodi.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Alert (if has recent updates) --}}
                    <div id="warningAlert" class="hidden bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-yellow-900 font-bold">PERHATIAN: Data Baru Diupdate</h3>
                                <p class="text-yellow-800 mt-2" id="warningMessage"></p>
                                <div class="mt-3">
                                    <p class="text-yellow-800 font-semibold mb-2">Submission yang baru diupdate:</p>
                                    <ul id="recentUpdateList"
                                        class="list-disc list-inside text-sm text-yellow-700 space-y-1"></ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Normal Info (if can delete) --}}
                    <div id="normalInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-blue-800 font-medium">
                            ℹ️ Data berikut akan <strong>DIHAPUS PERMANEN</strong> dan tidak dapat dikembalikan. Backup akan
                            dibuat secara otomatis.
                        </p>
                    </div>

                    {{-- Summary Table --}}
                    <table class="w-full text-sm border-collapse mb-6">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-slate-900">Item</th>
                                <th class="px-4 py-3 text-right font-bold text-slate-900">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr>
                                <td class="px-4 py-3">Submissions</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewSubmissions">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Submission Items</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewItems">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Dokumen Files</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewDokumen">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Laporan PDFs</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewLaporan">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Validasi Records</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewValidasi">-</td>
                            </tr>
                            <tr class="bg-slate-50 font-bold">
                                <td class="px-4 py-3">Total Records</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewTotalRecords">-</td>
                            </tr>
                            <tr class="bg-slate-50 font-bold">
                                <td class="px-4 py-3">Total Disk Space</td>
                                <td class="px-4 py-3 text-right font-mono" id="previewTotalSize">-</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Confirmation Form (only shown if can delete) --}}
                    <form id="deleteForm" method="POST" action="" class="hidden">
                        @csrf
                        @method('DELETE')

                        <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4 mb-4">
                            <label class="block text-sm font-bold text-red-900 mb-2">
                                Ketik nama program studi untuk konfirmasi:
                            </label>
                            <input type="text" name="confirmation_text" id="confirmationInput"
                                class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none"
                                placeholder="Ketik nama program studi di sini" required>
                            <p class="text-xs text-red-700 mt-2">
                                Ketik: <strong id="confirmationHint"></strong>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-start cursor-pointer mb-3">
                                <input type="checkbox" id="confirmCheckbox1"
                                    class="mt-1 w-5 h-5 rounded border-slate-300 text-red-600 focus:ring-red-500">
                                <span class="ml-3 text-sm text-slate-700">
                                    Saya memahami bahwa data yang dihapus <strong>tidak dapat dikembalikan</strong> dan
                                    backup akan dibuat secara otomatis.
                                </span>
                            </label>

                            {{-- Extra checkbox for warning case --}}
                            <label id="confirmCheckbox2Container" class="hidden flex items-start cursor-pointer">
                                <input type="checkbox" id="confirmCheckbox2"
                                    class="mt-1 w-5 h-5 rounded border-slate-300 text-red-600 focus:ring-red-500">
                                <span class="ml-3 text-sm text-slate-700">
                                    Saya tetap ingin menghapus data meskipun ada submission yang baru diupdate.
                                </span>
                            </label>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" id="btnCancelModal"
                                class="flex-1 px-6 py-3 bg-slate-200 text-slate-800 font-bold rounded-lg hover:bg-slate-300 transition-colors">
                                Batal
                            </button>
                            <button type="submit" id="btnConfirmDelete" disabled
                                class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors">
                                Hapus Data
                            </button>
                        </div>
                    </form>

                    {{-- Close button for blocking case --}}
                    <div id="blockingCloseButton" class="hidden">
                        <button type="button" id="btnCloseBlocking"
                            class="w-full px-6 py-3 bg-slate-600 text-white font-bold rounded-lg hover:bg-slate-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let selectedProdiId = null;
            let selectedProdiName = null;
            let hasRecentUpdates = false;

            // Enable preview button when prodi selected
            document.getElementById('prodiSelect').addEventListener('change', function() {
                selectedProdiId = this.value;
                selectedProdiName = this.options[this.selectedIndex].dataset.nama;
                console.log('Selected Prodi ID:', selectedProdiId);
                console.log('Selected Prodi Name:', selectedProdiName);
                console.log('Button should be enabled:', !!selectedProdiId);
                document.getElementById('btnPreview').disabled = !selectedProdiId;
            });

            // Show preview modal
            document.getElementById('btnPreview').addEventListener('click', async function() {
                if (!selectedProdiId) return;

                try {
                    const response = await fetch('{{ route('admin.prodi-data-cleanup.preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            prodi_id: selectedProdiId
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        const data = result.data;

                        // Populate summary table
                        document.getElementById('modalProdiName').textContent = data.prodi.nama;
                        document.getElementById('previewSubmissions').textContent = data.submission_count +
                            ' records';
                        document.getElementById('previewItems').textContent = data.item_count + ' records';
                        document.getElementById('previewDokumen').textContent = data.dokumen_count + ' files (' +
                            data.dokumen_size_mb + ' MB)';
                        document.getElementById('previewLaporan').textContent = data.laporan_count + ' files (' +
                            data.laporan_size_mb + ' MB)';
                        document.getElementById('previewValidasi').textContent = data.validasi_count + ' records';
                        document.getElementById('previewTotalRecords').textContent = data.total_records +
                            ' records';
                        document.getElementById('previewTotalSize').textContent = data.total_size_mb + ' MB';

                        // Hide all alerts first
                        document.getElementById('blockingAlert').classList.add('hidden');
                        document.getElementById('warningAlert').classList.add('hidden');
                        document.getElementById('normalInfo').classList.add('hidden');
                        document.getElementById('deleteForm').classList.add('hidden');
                        document.getElementById('blockingCloseButton').classList.add('hidden');
                        document.getElementById('confirmCheckbox2Container').classList.add('hidden');

                        // Show appropriate alert based on validation
                        if (!data.can_delete) {
                            // BLOCKING: Cannot delete
                            document.getElementById('blockingAlert').classList.remove('hidden');
                            document.getElementById('blockingReason').textContent = data.blocking_reason;
                            document.getElementById('blockingCloseButton').classList.remove('hidden');

                            // Populate pending list
                            const pendingList = document.getElementById('pendingList');
                            pendingList.innerHTML = '';
                            data.pending_details.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent =
                                    `${item.kriteria_kode} - ${item.kriteria_nama} (${item.status}) - ${item.days_ago}`;
                                pendingList.appendChild(li);
                            });

                            hasRecentUpdates = false;
                        } else {
                            // Can delete
                            if (data.has_recent_updates) {
                                // WARNING: Recent updates
                                document.getElementById('warningAlert').classList.remove('hidden');
                                document.getElementById('warningMessage').textContent = data.warnings[0];

                                // Populate recent update list
                                const recentList = document.getElementById('recentUpdateList');
                                recentList.innerHTML = '';
                                data.recent_update_details.forEach(item => {
                                    const li = document.createElement('li');
                                    li.textContent =
                                        `${item.kriteria_kode} - ${item.kriteria_nama} - ${item.days_ago} oleh ${item.updated_by}`;
                                    recentList.appendChild(li);
                                });

                                // Show extra checkbox
                                document.getElementById('confirmCheckbox2Container').classList.remove('hidden');
                                hasRecentUpdates = true;
                            } else {
                                // NORMAL: No issues
                                document.getElementById('normalInfo').classList.remove('hidden');
                                hasRecentUpdates = false;
                            }

                            // Show delete form
                            document.getElementById('deleteForm').classList.remove('hidden');
                            document.getElementById('confirmationHint').textContent = data.prodi.nama;
                            document.getElementById('deleteForm').action =
                                `/admin/prodi-data-cleanup/${selectedProdiId}`;
                        }

                        // Show modal
                        document.getElementById('previewModal').classList.remove('hidden');
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (error) {
                    alert('Gagal mengambil data: ' + error.message);
                }
            });

            // Close modal
            document.getElementById('btnCancelModal').addEventListener('click', closeModal);
            document.getElementById('btnCloseBlocking').addEventListener('click', closeModal);

            function closeModal() {
                document.getElementById('previewModal').classList.add('hidden');
                resetForm();
            }

            // Enable delete button when all confirmations are valid
            const confirmationInput = document.getElementById('confirmationInput');
            const confirmCheckbox1 = document.getElementById('confirmCheckbox1');
            const confirmCheckbox2 = document.getElementById('confirmCheckbox2');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');

            function checkConfirmation() {
                const textMatch = confirmationInput.value.trim().toLowerCase() === selectedProdiName.toLowerCase();
                const checkbox1Checked = confirmCheckbox1.checked;
                const checkbox2Checked = hasRecentUpdates ? confirmCheckbox2.checked : true;

                btnConfirmDelete.disabled = !(textMatch && checkbox1Checked && checkbox2Checked);
            }

            confirmationInput.addEventListener('input', checkConfirmation);
            confirmCheckbox1.addEventListener('change', checkConfirmation);
            confirmCheckbox2.addEventListener('change', checkConfirmation);

            function resetForm() {
                document.getElementById('confirmationInput').value = '';
                document.getElementById('confirmCheckbox1').checked = false;
                document.getElementById('confirmCheckbox2').checked = false;
                btnConfirmDelete.disabled = true;
                hasRecentUpdates = false;
            }
        </script>
    @endpush
@endsection
