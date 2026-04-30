@extends('layouts.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Riwayat Validasi</h1>
            <p class="text-slate-700 mt-1 text-base">Lihat semua riwayat penilaian dan validasi dokumen kurikulum yang pernah
                Anda lakukan.</p>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-300 p-5">
            <form action="{{ route('validator.riwayat.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                <!-- Program Studi -->
                <div>
                    <label for="prodi_id" class="block text-sm font-semibold text-slate-800 mb-1">Program Studi</label>
                    <select name="prodi_id" id="prodi_id"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->prodi_id }}" {{ $prodiId == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama }} ({{ $prodi->kode }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Keputusan -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-800 mb-1">Keputusan</label>
                    <select name="status" id="status"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Semua Keputusan</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revision" {{ $status == 'revision' ? 'selected' : '' }}>Revisi</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Tanggal Dari -->
                <div>
                    <label for="date_from" class="block text-sm font-semibold text-slate-800 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Tanggal Sampai -->
                <div>
                    <label for="date_to" class="block text-sm font-semibold text-slate-800 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('validator.riwayat.index') }}"
                        class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2 px-4 rounded-lg text-sm border border-slate-300 transition-colors focus:ring-2 focus:ring-slate-400 focus:outline-none">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-300 text-slate-800 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">Program Studi</th>
                            <th class="px-6 py-4 font-bold">Kode Sub-Kriteria</th>
                            <th class="px-6 py-4 font-bold">Nama Dosen</th>
                            <th class="px-6 py-4 font-bold">Tanggal Divalidasi</th>
                            <th class="px-6 py-4 font-bold">Keputusan</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-900 font-medium">
                                    {{ optional($log->submission->prodi)->nama }}
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    {{ optional($log->submission->kriteria)->kode }}
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    {{ optional($log->submission->user)->nama ?? 'Unknown Dosen' }}
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->action === 'approved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            Disetujui
                                        </span>
                                    @elseif($log->action === 'revision')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                            Revisi
                                        </span>
                                    @elseif($log->action === 'rejected')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-300">
                                            {{ $log->action }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('validator.riwayat.show', $log->id) }}"
                                        data-riwayat-detail-url="{{ route('validator.riwayat.show', $log->id) }}"
                                        class="js-open-riwayat-detail inline-flex items-center text-sm font-bold text-blue-700 hover:text-blue-900 focus:ring-2 focus:ring-blue-600 focus:outline-none rounded-md px-2 py-1 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-12 h-12 mb-3 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-base font-medium text-slate-700">Tidak ada riwayat validasi ditemukan
                                        </p>
                                        <p class="text-sm mt-1">Belum ada riwayat validasi atau tidak ada data yang cocok
                                            dengan filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    Menampilkan <span class="font-medium">{{ $auditLogs->firstItem() ?? 0 }}</span> hingga
                    <span class="font-medium">{{ $auditLogs->lastItem() ?? 0 }}</span> dari
                    <span class="font-medium">{{ $auditLogs->total() ?? 0 }}</span> hasil
                </div>

                <div class="flex">
                    {{ $auditLogs->render('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="riwayatDetailModal"
        class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeRiwayatDetailModal()">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                <div class="text-base font-semibold text-slate-900">Detail Riwayat</div>
                <button type="button" onclick="closeRiwayatDetailModal()"
                    class="text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div id="riwayatDetailModalBody" class="p-5 max-h-[75vh] overflow-y-auto">
                <div class="text-sm text-slate-600">Memuat...</div>
            </div>
        </div>
    </div>

    <script>
        const riwayatDetailModal = document.getElementById('riwayatDetailModal');
        const riwayatDetailModalBody = document.getElementById('riwayatDetailModalBody');

        function openRiwayatDetailModal() {
            riwayatDetailModal.classList.remove('hidden');
        }

        function closeRiwayatDetailModal() {
            riwayatDetailModal.classList.add('hidden');
            riwayatDetailModalBody.innerHTML = '<div class="text-sm text-slate-600">Memuat...</div>';
        }

        async function loadRiwayatDetail(url) {
            openRiwayatDetailModal();
            riwayatDetailModalBody.innerHTML = '<div class="text-sm text-slate-600">Memuat...</div>';

            try {
                const sep = url.includes('?') ? '&' : '?';
                const res = await fetch(url + sep + 'modal=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) {
                    throw new Error('Failed to load detail');
                }

                const html = await res.text();
                riwayatDetailModalBody.innerHTML = html;
            } catch (e) {
                riwayatDetailModalBody.innerHTML =
                    '<div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">Gagal memuat detail. Silahkan coba lagi.</div>';
            }
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('.js-open-riwayat-detail');
            if (!link) return;

            const url = link.getAttribute('data-riwayat-detail-url') || link.getAttribute('href');
            if (!url) return;

            e.preventDefault();
            loadRiwayatDetail(url);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRiwayatDetailModal();
        });
    </script>
@endsection
