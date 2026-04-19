@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $kriteria->nama }}</h1>
                <p class="text-slate-600 text-sm mt-1">Kode: {{ $kriteria->kode }}</p>
            </div>
            <div>
                <a href="{{ route('dosen.prodi.kriteria', $prodi->prodi_id) }}"
                    class="inline-flex items-center px-4 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 font-medium">Status Submission</p>
                    @if ($submission->status === 'draft')
                        <span
                            class="inline-flex px-3 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full mt-1">
                            Draft
                        </span>
                    @elseif ($submission->status === 'submitted')
                        <span class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mt-1">
                            Submitted
                        </span>
                    @elseif ($submission->status === 'diterima')
                        <span
                            class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full mt-1">
                            ✓ Diterima
                        </span>
                    @elseif ($submission->status === 'revisi')
                        <span
                            class="inline-flex px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full mt-1">
                            ⚠ Revisi
                        </span>
                    @elseif ($submission->status === 'ditolak')
                        <span class="inline-flex px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full mt-1">
                            ✕ Ditolak
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-600 font-medium">Bobot Kriteria</p>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold bg-orange-100 text-orange-800 rounded mt-1">
                        {{ $kriteria->bobot }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Deskripsi Kriteria -->
        @if ($kriteria->deskripsi)
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Deskripsi</h3>
                <p class="text-blue-800 text-sm">{{ $kriteria->deskripsi }}</p>
            </div>
        @endif

        <!-- Template Items Form -->
        <form action="{{ route('dosen.submission.store', [$prodi->prodi_id, $kriteria->kriteria_id]) }}" method="POST"
            class="space-y-6" enctype="multipart/form-data">
            @csrf

            @if ($templateItems->count() > 0)
                @foreach ($templateItems as $template)
                    @php
                        $submissionItem = $submissionItems->get($template->template_id);
                    @endphp
                    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
                        <!-- Template Item Header -->
                        <div class="mb-4">
                            <div class="flex items-start justify-between mb-2">
                                <label class="block">
                                    <span class="text-sm font-semibold text-slate-900">{{ $template->label }}</span>
                                    @if ($template->wajib)
                                        <span class="text-red-600 font-bold ml-1">*</span>
                                    @endif
                                </label>
                                <span class="text-xs font-medium px-2.5 py-1 bg-slate-100 text-slate-700 rounded">
                                    {{ ucfirst($template->tipe) }}
                                </span>
                            </div>
                            @if ($template->hint)
                                <p class="text-xs text-slate-600 mt-1">{{ $template->hint }}</p>
                            @endif
                        </div>

                        <!-- Input berdasarkan tipe -->
                        @if ($template->tipe === 'checklist')
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="template_{{ $template->template_id }}"
                                    name="template_{{ $template->template_id }}" value="1"
                                    {{ $submissionItem?->nilai_checklist ? 'checked' : '' }}
                                    class="w-5 h-5 border-slate-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <label for="template_{{ $template->template_id }}" class="text-sm text-slate-600">
                                    Saya telah menyiapkan {{ strtolower($template->label) }}
                                </label>
                            </div>
                        @elseif ($template->tipe === 'upload')
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center bg-slate-50 hover:bg-slate-100 transition-colors">
                                <input type="file" id="template_{{ $template->template_id }}"
                                    name="template_{{ $template->template_id }}" accept=".pdf,.docx,.xlsx,.doc,.xls"
                                    class="hidden"
                                    onchange="document.getElementById('file_name_{{ $template->template_id }}').textContent = this.files[0]?.name || 'Pilih file'">
                                <label for="template_{{ $template->template_id }}" class="cursor-pointer">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-900">Klik untuk upload atau drag & drop</p>
                                    <p class="text-xs text-slate-600 mt-1">PDF, DOCX, XLSX maksimal 10MB</p>
                                </label>
                                <p id="file_name_{{ $template->template_id }}"
                                    class="text-xs text-blue-600 mt-2 font-semibold">
                                    {{ $submissionItem?->nilai_teks ? basename($submissionItem->nilai_teks) : 'Belum ada file' }}
                                </p>
                            </div>
                        @elseif ($template->tipe === 'numerik')
                            <input type="number" name="template_{{ $template->template_id }}"
                                value="{{ $submissionItem?->nilai_numerik ?? '' }}"
                                {{ $template->wajib ? 'required' : '' }}
                                @if ($template->nilai_min_numerik) min="{{ $template->nilai_min_numerik }}" @endif
                                placeholder="Masukkan nilai numerik"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @if ($template->nilai_min_numerik)
                                <p class="text-xs text-slate-600 mt-1">Minimum: {{ $template->nilai_min_numerik }}</p>
                            @endif
                        @elseif ($template->tipe === 'narasi')
                            <textarea name="template_{{ $template->template_id }}" {{ $template->wajib ? 'required' : '' }} rows="4"
                                placeholder="Masukkan narasi/penjelasan"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $submissionItem?->nilai_teks ?? '' }}</textarea>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-lg border border-slate-200 p-8 text-center shadow-sm">
                    <p class="text-slate-600">Belum ada template item untuk kriteria ini</p>
                </div>
            @endif

            <!-- Form Actions -->
            @if ($templateItems->count() > 0)
                <div class="flex flex-col sm:flex-row gap-3">
                    @if ($submission->status === 'ditolak')
                        <!-- Reset button for ditolak status -->
                        <button type="button" id="resetFormBtn"
                            class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors focus:outline-none"
                            onclick="if(confirm('Apakah Anda yakin ingin mereset form? Semua jawaban akan dihapus.')) { document.getElementById('resetForm').submit(); }">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset Form
                        </button>
                    @else
                        <button type="submit" name="action" value="save"
                            class="flex-1 px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V3">
                                </path>
                            </svg>
                            Simpan Sebagai Draft
                        </button>
                        <button type="submit" name="action" value="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none"
                            {{ $submission->status !== 'draft' && $submission->status !== 'revisi' ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Submit untuk Validasi
                        </button>
                    @endif
                </div>
            @endif
        </form>

        <!-- Hidden reset form (outside main form) -->
        @if ($submission->status === 'ditolak')
            <form id="resetForm"
                action="{{ route('dosen.submission.reset', [$prodi->prodi_id, $submission->submission_id]) }}"
                method="POST" style="display:none;">
                @csrf
            </form>
        @endif

        <!-- Validation Notes (jika status revisi atau ditolak) -->
        @if (($submission->status === 'revisi' || $submission->status === 'ditolak') && $submission->validasi)
            @if ($submission->status === 'revisi')
                <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-4">
                    <h3 class="text-sm font-semibold text-yellow-900 mb-2">⚠ Catatan Validator (Perlu Diperbaiki)</h3>
                    <p class="text-yellow-800 text-sm">{{ $submission->validasi->komentar }}</p>
                </div>
            @elseif ($submission->status === 'ditolak')
                <div class="bg-red-50 rounded-lg border border-red-200 p-4">
                    <h3 class="text-sm font-semibold text-red-900 mb-2">✕ Catatan Validator (Dokumen Ditolak)</h3>
                    <p class="text-red-800 text-sm mb-3">{{ $submission->validasi->komentar }}</p>
                    <p class="text-red-700 text-xs font-medium">💡 Mohon isi ulang dari awal sesuai dengan catatan di atas,
                        kemudian reset form dan mulai pengisian baru.</p>
                </div>
            @endif
        @endif
    </div>

@endsection
