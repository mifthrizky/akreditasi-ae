@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Daftar Kriteria {{ $prodi->nama }}</h1>
                <p class="text-slate-600 mt-1 text-base">Kode Prodi: <span class="font-semibold">{{ $prodi->kode }}</span>
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dosen.laporan.show', $prodi->prodi_id) }}"
                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Laporan Kesiapan
                </a>
                <a href="{{ route('dosen.prodi.index') }}"
                    class="inline-flex items-center px-4 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Summary Statistics -->
        @php
            $totalSub = $kriterias->sum(fn($k) => $k->children->count());
            $draftCount = $submissions->where('status', 'draft')->count();
            $submittedCount = $submissions->where('status', 'submitted')->count();
            $diterima = $submissions->where('status', 'diterima')->count();
            $revisi = $submissions->where('status', 'revisi')->count();
            $ditolak = $submissions->where('status', 'ditolak')->count();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Sub-Kriteria</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalSub }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Draft</p>
                <p class="text-2xl font-bold text-slate-600 mt-1">{{ $draftCount }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Submitted</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $submittedCount }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Diterima</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $diterima }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Revisi</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $revisi }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Ditolak</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $ditolak }}</p>
            </div>
        </div>

        <!-- Kriteria List -->
        <div class="space-y-4">
            @if ($kriterias->count() > 0)
                @foreach ($kriterias as $kriteria)
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                        <!-- Level 0 Kriteria (Parent) -->
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold px-3 py-1 bg-slate-200 text-slate-800 rounded">
                                            {{ $kriteria->kode }}
                                        </span>
                                        <h2 class="text-lg font-semibold text-slate-900">{{ $kriteria->nama }}</h2>
                                    </div>
                                    <p class="text-slate-600 text-sm mt-2">{{ $kriteria->deskripsi }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded">
                                        Bobot: {{ $kriteria->bobot }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Level 1 Kriteria (Children / Sub-Grup) -->
                        @if ($kriteria->children->count() > 0)
                            <div class="divide-y divide-slate-200">
                                @foreach ($kriteria->children as $subKriteria)
                                    <div class="bg-indigo-50/30 px-6 py-3 border-y border-slate-200 first:border-t-0">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-semibold px-2 py-1 bg-indigo-100 text-indigo-800 rounded">{{ $subKriteria->kode }}</span>
                                            <h3 class="font-semibold text-slate-800">{{ $subKriteria->nama }}</h3>
                                        </div>
                                    </div>

                                    <!-- Level 2 Kriteria (Sub-kriteria / Formulir) -->
                                    @if ($subKriteria->children->count() > 0)
                                        <div class="divide-y divide-slate-100 bg-white">
                                            @foreach ($subKriteria->children as $subSubKriteria)
                                                @php
                                                    $submission = $submissions->get($subSubKriteria->kriteria_id);
                                                    $status = $submission?->status ?? 'draft';
                                                @endphp
                                                <div class="px-8 py-4 hover:bg-slate-50 transition-colors pl-12 border-l-4 border-transparent hover:border-blue-500">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-3 mb-1">
                                                                <span
                                                                    class="text-xs font-mono px-2 py-1 bg-slate-100 text-slate-600 rounded">
                                                                    {{ $subSubKriteria->kode }}
                                                                </span>
                                                                <h4 class="font-medium text-slate-900">{{ $subSubKriteria->nama }}</h4>
                                                            </div>
                                                            <p class="text-slate-500 text-sm mt-1">{{ Str::limit($subSubKriteria->deskripsi, 120) }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-3 flex-shrink-0">
                                                            <!-- Status Badge -->
                                                            @if ($status === 'draft')
                                                                <span
                                                                    class="inline-flex px-3 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full border border-slate-200">
                                                                    Draft
                                                                </span>
                                                            @elseif ($status === 'submitted')
                                                                <span
                                                                    class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full border border-blue-200">
                                                                    Submitted
                                                                </span>
                                                            @elseif ($status === 'diterima')
                                                                <span
                                                                    class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full border border-green-200">
                                                                    ✓ Diterima
                                                                </span>
                                                            @elseif ($status === 'revisi')
                                                                <span
                                                                    class="inline-flex px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full border border-yellow-200">
                                                                    ⚠ Revisi
                                                                </span>
                                                            @elseif ($status === 'ditolak')
                                                                <span
                                                                    class="inline-flex px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full border border-red-200">
                                                                    ✕ Ditolak
                                                                </span>
                                                            @endif

                                                            <!-- Bobot Badge -->
                                                            <span
                                                                class="text-xs font-medium px-2 py-1 bg-slate-50 text-slate-500 rounded border border-slate-200" title="Bobot Perhitungan">
                                                                Bobot {{ $subSubKriteria->bobot }}
                                                            </span>

                                                            <!-- Action Buttons -->
                                                            <div class="flex gap-2">
                                                                @if ($submission)
                                                                    <a href="{{ route('dosen.submission.review', $submission->submission_id) }}"
                                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition-colors border border-slate-200">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                                            </path>
                                                                        </svg>
                                                                        Review
                                                                    </a>
                                                                @endif
                                                                @if ($status === 'revisi')
                                                                    <a href="{{ route('dosen.submission.show', [$prodi->prodi_id, $subSubKriteria->kriteria_id]) }}"
                                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded transition-colors">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                            </path>
                                                                        </svg>
                                                                        Perbaiki
                                                                    </a>
                                                                @elseif ($status === 'ditolak')
                                                                    <a href="{{ route('dosen.submission.show', [$prodi->prodi_id, $subSubKriteria->kriteria_id]) }}"
                                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded transition-colors">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                                            </path>
                                                                        </svg>
                                                                        Ulang
                                                                    </a>
                                                                @elseif ($status !== 'diterima' && $status !== 'submitted')
                                                                    <a href="{{ route('dosen.submission.show', [$prodi->prodi_id, $subSubKriteria->kriteria_id]) }}"
                                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded transition-colors shadow-sm">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                        </svg>
                                                                        Isi
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="px-6 py-8 text-center bg-slate-50">
                                <p class="text-slate-500 text-sm">Tidak ada sub-grup untuk kriteria utama ini</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-lg border border-slate-200 p-8 text-center shadow-sm">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada kriteria</h3>
                    <p class="text-slate-600">Silahkan hubungi administrator untuk mengatur kriteria</p>
                </div>
            @endif
        </div>
    </div>

@endsection
