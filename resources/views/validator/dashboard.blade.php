@extends('layouts.layout')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Validator</h1>
                <p class="text-slate-700 mt-1 text-base">
                    Prioritaskan review submission terbaru dan pantau hasil validasi Anda.
                </p>
            </div>
            <a href="{{ route('validator.antrian.index') }}"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Lihat Semua Antrian
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Menunggu Review</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $waitingReview }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Selesai Hari Ini</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $completedToday }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Disetujui</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalApproved }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Revisi/Ditolak</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $totalReturned }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Riwayat Validasi Terbaru</h2>
                <p class="text-sm text-slate-600 mt-1">Menampilkan 5 hasil validasi terakhir yang Anda proses.</p>
            </div>

            @if ($recentValidations->count() > 0)
                <div class="divide-y divide-slate-200">
                    @foreach ($recentValidations as $validation)
                        @php
                            $badgeClass = match ($validation->status) {
                                'disetujui' => 'bg-green-100 text-green-800',
                                'revisi' => 'bg-amber-100 text-amber-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <div
                            class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $validation->prodi_nama }}</p>
                                <p class="text-sm text-slate-600">{{ $validation->kriteria_nama }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ \Carbon\Carbon::parse($validation->validated_at)->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                    {{ ucfirst($validation->status) }}
                                </span>
                                <a href="{{ route('validator.antrian.show', $validation->submission_id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-10 text-center">
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Belum ada riwayat validasi</h3>
                    <p class="text-slate-600">Riwayat akan tampil setelah Anda memproses submission.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
