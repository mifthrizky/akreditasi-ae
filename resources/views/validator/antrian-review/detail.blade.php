@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Review Submission</h1>
                <p class="text-slate-600 mt-1 text-base">Validasi dokumen submission dari dosen</p>
            </div>
            <div>
                <a href="{{ route('validator.antrian') }}"
                    class="inline-flex items-center px-4 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Antrian
                </a>
            </div>
        </div>

        <!-- Submission Header Info -->
        <div class="bg-linear-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-1">PROGRAM STUDI</p>
                    <p class="text-sm font-bold text-slate-900">{{ $submission->prodi->nama }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $submission->prodi->kode }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-1">KRITERIA</p>
                    <p class="text-lg font-bold text-slate-900">{{ $submission->kriteria->kode }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $submission->kriteria->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-1">DISUBMIT OLEH</p>
                    <p class="text-lg font-bold text-slate-900">{{ $submission->user->nama }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $submission->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-1">TANGGAL SUBMIT</p>
                    <p class="text-lg font-bold text-slate-900">
                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d F Y') }}
                    </p>
                    <p class="text-xs text-slate-600 mt-1">
                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Submission Answers -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Jawaban Submission</h2>
            </div>

            <div class="p-6 space-y-6">
                @if ($submission->items->count() > 0)
                    @foreach ($submission->items as $item)
                        @php
                            $template = $item->templateItem;
                        @endphp
                        <div class="pb-6 border-b border-slate-200 last:border-0 last:pb-0">
                            <!-- Item Label & Type -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $template->label }}</p>
                                    @if ($template->hint)
                                        <p class="text-sm text-slate-600 mt-1">{{ $template->hint }}</p>
                                    @endif
                                </div>
                                <span class="text-xs font-medium px-2.5 py-1 bg-slate-100 text-slate-700 rounded">
                                    {{ ucfirst($template->tipe) }}
                                </span>
                            </div>

                            <!-- Item Answer by Type -->
                            @if ($template->tipe === 'checklist')
                                <div class="flex items-center gap-2">
                                    @if ($item->nilai_checklist)
                                        <span
                                            class="inline-flex px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded">
                                            ✓ Sudah disiapkan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded">
                                            ✗ Belum disiapkan
                                        </span>
                                    @endif
                                </div>
                            @elseif ($template->tipe === 'upload')
                                @if ($item->nilai_teks)
                                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <p class="font-medium text-slate-900">{{ basename($item->nilai_teks) }}
                                                    </p>
                                                    <p class="text-xs text-slate-600 mt-1">File sudah diupload</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $item->nilai_teks) }}" target="_blank"
                                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">Tidak ada file yang diupload</p>
                                @endif
                            @elseif ($template->tipe === 'numerik')
                                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                    <p class="text-2xl font-bold text-slate-900">{{ $item->nilai_numerik ?? '-' }}</p>
                                    @if ($template->nilai_min_numerik)
                                        <p class="text-xs text-slate-600 mt-1">Minimum: {{ $template->nilai_min_numerik }}
                                        </p>
                                    @endif
                                </div>
                            @elseif ($template->tipe === 'narasi')
                                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $item->nilai_teks ?? '-' }}
                                    </p>
                                </div>
                            @endif

                            <!-- Required indicator -->
                            @if ($template->wajib)
                                <p class="text-xs text-amber-600 mt-2 font-medium">* Item wajib diisi</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-slate-500 text-center py-8">Tidak ada item submission</p>
                @endif
            </div>
        </div>

        <!-- Validasi Form -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Form Validasi</h2>
            </div>

            <form action="{{ route('validator.validasi.store', $submission->submission_id) }}" method="POST"
                class="p-6 space-y-6">
                @csrf

                <!-- Status Selection -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-3">Keputusan Validasi</label>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="radio" id="status_disetujui" name="status" value="disetujui"
                                class="w-5 h-5 text-green-600 border-slate-300 focus:ring-green-500"
                                onchange="toggleKomentar()">
                            <label for="status_disetujui" class="ml-3 text-sm font-medium text-slate-900 cursor-pointer">
                                <span class="text-green-700">✓ Disetujui</span> - Submission sesuai dan dapat diterima
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="status_revisi" name="status" value="revisi"
                                class="w-5 h-5 text-yellow-600 border-slate-300 focus:ring-yellow-500"
                                onchange="toggleKomentar()">
                            <label for="status_revisi" class="ml-3 text-sm font-medium text-slate-900 cursor-pointer">
                                <span class="text-yellow-700">⚠ Revisi</span> - Perlu diperbaiki dan disubmit ulang
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="status_ditolak" name="status" value="ditolak"
                                class="w-5 h-5 text-red-600 border-slate-300 focus:ring-red-500"
                                onchange="toggleKomentar()">
                            <label for="status_ditolak" class="ml-3 text-sm font-medium text-slate-900 cursor-pointer">
                                <span class="text-red-700">✕ Ditolak</span> - Tidak relevan, perlu pengisian ulang
                            </label>
                        </div>
                    </div>
                    @error('status')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Komentar (required for revisi/ditolak) -->
                <div>
                    <label for="komentar" class="block text-sm font-semibold text-slate-900 mb-2">
                        Catatan/Komentar
                        <span id="komentar_required" class="text-red-600 font-bold ml-1">*</span>
                    </label>
                    <textarea id="komentar" name="komentar" rows="5" placeholder="Berikan catatan atau penjelasan untuk dosen..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('komentar') }}</textarea>
                    <p class="text-xs text-slate-600 mt-1">
                        Catatan wajib diisi untuk status "Revisi" atau "Ditolak"
                    </p>
                    @error('komentar')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-slate-200">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Kirim Validasi
                    </button>
                    <a href="{{ route('validator.antrian') }}"
                        class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleKomentar() {
            const status = document.querySelector('input[name="status"]:checked').value;
            const komentarRequired = document.getElementById('komentar_required');
            const komentarField = document.getElementById('komentar');

            if (status === 'revisi' || status === 'ditolak') {
                komentarField.classList.add('border-red-300', 'focus:ring-red-500');
                komentarField.classList.remove('border-slate-300', 'focus:ring-blue-500');
            } else {
                komentarField.classList.remove('border-red-300', 'focus:ring-red-500');
                komentarField.classList.add('border-slate-300', 'focus:ring-blue-500');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedStatus = document.querySelector('input[name="status"]:checked');
            if (selectedStatus) {
                toggleKomentar();
            }
        });
    </script>

@endsection
