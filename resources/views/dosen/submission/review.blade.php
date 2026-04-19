@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $kriteria->nama }}</h1>
                <p class="text-slate-600 mt-1 text-base">Program Studi: <span class="font-semibold">{{ $prodi->nama }}</span>
                </p>
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

        <!-- Status & Score -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Status Submission</p>
                @if ($submission->status === 'draft')
                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full mt-2">
                        Draft
                    </span>
                @elseif ($submission->status === 'submitted')
                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mt-2">
                        Menunggu Review
                    </span>
                @elseif ($submission->status === 'diterima')
                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full mt-2">
                        ✓ Diterima
                    </span>
                @elseif ($submission->status === 'revisi')
                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full mt-2">
                        ⚠ Perlu Revisi
                    </span>
                @elseif ($submission->status === 'ditolak')
                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full mt-2">
                        ✕ Ditolak
                    </span>
                @endif
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Skor</p>
                <p class="text-3xl font-bold mt-2">
                    @php
                        $color = $skor >= 80 ? 'green' : ($skor >= 50 ? 'yellow' : 'red');
                        $colorClass = 'text-' . $color . '-600';
                    @endphp
                    <span class="{{ $colorClass }}">{{ round($skor, 2) }}%</span>
                </p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Bobot Kriteria</p>
                <p class="text-2xl font-bold text-orange-600 mt-2">{{ $kriteria->bobot }}%</p>
            </div>
        </div>

        <!-- Kriteria Description -->
        @if ($kriteria->deskripsi)
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Deskripsi Kriteria</h3>
                <p class="text-blue-800 text-sm">{{ $kriteria->deskripsi }}</p>
            </div>
        @endif

        <!-- Validator Feedback (if exists) -->
        @if ($submission->validasi)
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div
                    class="bg-gradient-to-r
                    @if ($submission->validasi->status === 'disetujui') from-green-50 to-green-100 @elseif ($submission->validasi->status === 'revisi') from-yellow-50 to-yellow-100 @else from-red-50 to-red-100 @endif
                    px-6 py-4 border-b border-slate-200">
                    <h3
                        class="text-lg font-semibold
                    @if ($submission->validasi->status === 'disetujui') text-green-900 @elseif ($submission->validasi->status === 'revisi') text-yellow-900 @else text-red-900 @endif">
                        Hasil Validasi
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Validator Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-200">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold">VALIDATOR</p>
                            <p class="text-sm font-medium text-slate-900 mt-1">{{ $submission->validasi->user->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold">TANGGAL REVIEW</p>
                            <p class="text-sm font-medium text-slate-900 mt-1">
                                {{ \Carbon\Carbon::parse($submission->validasi->validated_at)->format('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold">STATUS VALIDASI</p>
                            @if ($submission->validasi->status === 'disetujui')
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full mt-1">
                                    ✓ Disetujui
                                </span>
                            @elseif ($submission->validasi->status === 'revisi')
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full mt-1">
                                    ⚠ Revisi
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full mt-1">
                                    ✕ Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Komentar -->
                    @if ($submission->validasi->komentar)
                        <div>
                            <p class="text-sm font-semibold text-slate-900 mb-2">Catatan dari Validator</p>
                            <div class="bg-slate-50 rounded border border-slate-200 p-4">
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $submission->validasi->komentar }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-slate-50 rounded-lg border border-slate-200 p-4 text-center">
                <p class="text-slate-600 text-sm">Belum ada hasil validasi. Submission masih dalam antrian review.</p>
            </div>
        @endif

        <!-- Form Revisi (jika status revisi) -->
        @if ($submission->status === 'revisi' || $submission->status === 'draft')
            <form action="{{ route('dosen.submission.store', [$prodi->prodi_id, $kriteria->kriteria_id]) }}" method="POST"
                class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Template Pengisian</h3>
                    <p class="text-sm text-slate-600">
                        @if ($submission->status === 'revisi')
                            Perbaiki item yang ditandai oleh validator, kemudian kirim ulang.
                        @else
                            Lengkapi template di bawah ini.
                        @endif
                    </p>
                </div>

                <!-- Template Items -->
                @if ($templateItems->count() > 0)
                    <div class="space-y-4">
                        @foreach ($templateItems as $template)
                            @php
                                $submissionItem = $submissionItems->get($template->template_id);
                            @endphp
                            <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
                                <!-- Header -->
                                <div class="mb-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <label class="block">
                                            <span
                                                class="text-sm font-semibold text-slate-900">{{ $template->label }}</span>
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
                                            name="template_{{ $template->template_id }}"
                                            accept=".pdf,.docx,.xlsx,.doc,.xls" class="hidden"
                                            onchange="document.getElementById('file_name_{{ $template->template_id }}').textContent = this.files[0]?.name || 'Pilih file'">
                                        <label for="template_{{ $template->template_id }}" class="cursor-pointer">
                                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-slate-900">Klik untuk upload atau drag & drop
                                            </p>
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
                                        <p class="text-xs text-slate-600 mt-1">Minimum: {{ $template->nilai_min_numerik }}
                                        </p>
                                    @endif
                                @elseif ($template->tipe === 'narasi')
                                    <textarea name="template_{{ $template->template_id }}" {{ $template->wajib ? 'required' : '' }} rows="4"
                                        placeholder="Masukkan narasi/penjelasan"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $submissionItem?->nilai_teks ?? '' }}</textarea>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-200">
                        <button type="submit" name="action" value="save"
                            class="flex-1 px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V3">
                                </path>
                            </svg>
                            Simpan Sebagai Draft
                        </button>
                        @if ($submission->status === 'revisi')
                            <button type="submit" name="action" value="submit"
                                class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                Kirim Ulang untuk Validasi
                            </button>
                        @else
                            <button type="submit" name="action" value="submit"
                                class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                Submit untuk Validasi
                            </button>
                        @endif
                    </div>
                @endif
            </form>
        @endif

        <!-- Read-only view (jika status diterima atau ditolak) -->
        @if ($submission->status === 'diterima' || $submission->status === 'ditolak')
            <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Jawaban Submission</h3>

                @if ($templateItems->count() > 0)
                    <div class="space-y-4">
                        @foreach ($templateItems as $template)
                            @php
                                $submissionItem = $submissionItems->get($template->template_id);
                            @endphp
                            <div class="pb-4 border-b border-slate-200 last:border-0 last:pb-0">
                                <p class="text-sm font-semibold text-slate-900 mb-2">{{ $template->label }}</p>

                                @if ($template->tipe === 'checklist')
                                    <p class="text-sm text-slate-600">
                                        @if ($submissionItem?->nilai_checklist)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                                ✓ Sudah disiapkan
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">
                                                ✗ Belum disiapkan
                                            </span>
                                        @endif
                                    </p>
                                @elseif ($template->tipe === 'upload')
                                    @if ($submissionItem?->nilai_teks)
                                        <a href="{{ asset('storage/' . $submissionItem->nilai_teks) }}" target="_blank"
                                            class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            📄 {{ basename($submissionItem->nilai_teks) }}
                                        </a>
                                    @else
                                        <p class="text-sm text-slate-500">Belum ada file</p>
                                    @endif
                                @elseif ($template->tipe === 'numerik')
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $submissionItem?->nilai_numerik ?? '-' }}</p>
                                @elseif ($template->tipe === 'narasi')
                                    <p class="text-sm text-slate-700 whitespace-pre-wrap">
                                        {{ $submissionItem?->nilai_teks ?? '-' }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

@endsection
