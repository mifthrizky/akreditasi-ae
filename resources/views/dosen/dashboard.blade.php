@extends('layouts.layout')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Dosen</h1>
                <p class="text-slate-700 mt-1 text-base">
                    Pantau progres pengisian dokumen akreditasi pada program studi yang Anda tangani.
                </p>
            </div>
            <a href="{{ route('dosen.prodi.index') }}"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Buka Daftar Prodi
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
            </div>
        @endif

        @if ($prodiStats->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @php
                    $totalProdi = $prodiStats->count();
                    $totalSubmission = $prodiStats->sum('total_submissions');
                    $totalCompleted = $prodiStats->sum('completed_submissions');
                    $avgProgress = $totalProdi > 0 ? round($prodiStats->avg('progress_percentage')) : 0;
                @endphp
                <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-600 font-medium">Prodi Ditugaskan</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalProdi }}</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-600 font-medium">Total Submission</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalSubmission }}</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-600 font-medium">Sudah Diterima</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalCompleted }}</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                    <p class="text-sm text-slate-600 font-medium">Rata-rata Progress</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $avgProgress }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($prodiStats as $stat)
                    @php
                        $badgeClass = $stat['progress_percentage'] >= 80
                            ? 'bg-green-100 text-green-800'
                            : ($stat['progress_percentage'] >= 50
                                ? 'bg-amber-100 text-amber-800'
                                : 'bg-red-100 text-red-800');
                        $badgeText = $stat['progress_percentage'] >= 80
                            ? 'Siap'
                            : ($stat['progress_percentage'] >= 50 ? 'Berproses' : 'Butuh Percepatan');
                    @endphp
                    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <p class="text-xs font-semibold tracking-wide text-slate-500">{{ $stat['prodi']->kode }}</p>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $stat['prodi']->nama }}</h3>
                            </div>
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                {{ $badgeText }}
                            </span>
                        </div>

                        <div class="text-sm text-slate-600 mb-2">
                            {{ $stat['completed_submissions'] }}/{{ $stat['total_submissions'] }} submission diterima
                        </div>
                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mb-4">
                            <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $stat['progress_percentage'] }}%"></div>
                        </div>

                        <div class="flex items-center justify-between text-sm mb-4">
                            <span class="text-slate-600">Skor Rata-rata Final</span>
                            <span class="font-semibold text-slate-900">{{ $stat['avg_score'] }}%</span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('dosen.submission.kriteria-index', $stat['prodi']->prodi_id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Buka Kriteria
                            </a>
                            <a href="{{ route('dosen.laporan.show', $stat['prodi']->prodi_id) }}"
                                class="inline-flex items-center px-4 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                                Lihat Laporan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg border border-slate-200 p-12 text-center shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Belum ada program studi yang ditugaskan</h3>
                <p class="text-slate-600">Hubungi admin agar Anda ditambahkan ke program studi terkait.</p>
            </div>
        @endif
    </div>
@endsection
