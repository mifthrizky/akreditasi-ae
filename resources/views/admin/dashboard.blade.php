@extends('layouts.layout')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-slate-600 mt-1 text-base">
                    Ringkasan progres seluruh program studi untuk kesiapan akreditasi IABEE.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    Kelola User
                </a>
                <a href="{{ route('admin.program-studi.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Kelola Prodi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Program Studi Aktif</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalProdi }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Submission</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalSubmissions }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Menunggu Validasi</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $submissionsWaiting }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total User</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Progress per Program Studi</h2>
                <p class="text-sm text-slate-600 mt-1">Persentase didasarkan pada submission berstatus diterima.</p>
            </div>

            @if ($prodiProgress->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Program Studi</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Progres</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Skor Rata-rata</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($prodiProgress as $row)
                                @php
                                    $completed = (int) $row->completed;
                                    $total = (int) $row->total_submission;
                                    $progressPercentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                                    $avgScore = $row->avg_score ? round($row->avg_score, 2) : 0;
                                    $statusLabel = $progressPercentage >= 100 ? 'Selesai' : ($progressPercentage >= 60 ? 'Berjalan' : 'Perlu Atensi');
                                    $statusClass = $progressPercentage >= 100
                                        ? 'bg-green-100 text-green-800'
                                        : ($progressPercentage >= 60 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-900">{{ $row->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $row->kode }}</p>
                                    </td>
                                    <td class="px-6 py-4 min-w-[280px]">
                                        <div class="flex items-center justify-between text-xs text-slate-600 mb-2">
                                            <span>{{ $completed }}/{{ $total }} selesai</span>
                                            <span>{{ $progressPercentage }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-2 bg-blue-600 rounded-full"
                                                style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $avgScore }}%</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Belum ada data progres</h3>
                    <p class="text-slate-600">Tambahkan program studi dan mulai submission untuk melihat ringkasan progres.</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Submission Terbaru</h2>
            </div>
            @if ($recentSubmissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Prodi</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Kriteria</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Pengirim</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Status</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($recentSubmissions as $submission)
                                @php
                                    $badgeClass = match ($submission->status) {
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'diterima' => 'bg-green-100 text-green-800',
                                        'revisi' => 'bg-amber-100 text-amber-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $submission->prodi->kode ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $submission->kriteria->kode ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $submission->user->nama ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 text-center text-slate-600">
                    Belum ada submission yang masuk.
                </div>
            @endif
        </div>
    </div>
@endsection
