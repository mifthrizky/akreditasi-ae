@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Laporan Kesiapan {{ $prodi->nama }}</h1>
                <p class="text-slate-600 mt-1 text-base">Kode Prodi: <span class="font-semibold">{{ $prodi->kode }}</span>
                </p>
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

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Overall Score Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-8 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-2">SKOR KESELURUHAN</p>
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-5xl font-bold
                        @if ($overallStatus['status'] === 'passed') text-green-600 @else text-red-600 @endif">
                            {{ $overallStatus['total_score_percent'] }}%
                        </span>
                        <span class="text-lg text-blue-700 font-medium">
                            / 100%
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    @if ($overallStatus['status'] === 'passed')
                        <div class="inline-flex px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold">
                            ✓ Memenuhi Standar IABEE
                        </div>
                    @else
                        <div class="inline-flex px-4 py-2 bg-red-100 text-red-800 rounded-lg font-semibold">
                            ⚠ Belum Memenuhi Standar
                        </div>
                    @endif
                    <p class="text-sm text-blue-700 mt-2">Minimum: {{ $overallStatus['minimum_required'] }}%</p>
                </div>
            </div>
        </div>

        <!-- Radar Chart -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Grafik Radar - Skor per Karakteristik</h2>
            <div style="position: relative; height: 400px;">
                <canvas id="radarChart"></canvas>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $totalSubmissions = count($scores);
                $completedSubmissions = count(array_filter($scores, fn($s) => $s >= 80));
                $partialSubmissions = count(array_filter($scores, fn($s) => $s >= 50 && $s < 80));
                $criticalSubmissions = count(array_filter($scores, fn($s) => $s < 50));
            @endphp

            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Kriteria</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalSubmissions }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Baik (≥80%)</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $completedSubmissions }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Sebagian (50-80%)</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $partialSubmissions }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Kritis (<50%)< /p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $criticalSubmissions }}</p>
            </div>
        </div>

        <!-- Gap Analysis by Kriteria -->
        @if (count($scores) > 0)
            @if (count($gaps) > 0)
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Analisis Kesenjangan (Gap)</h2>
                        <p class="text-sm text-slate-600 mt-1">Karakteristik yang belum sepenuhnya terpenuhi</p>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach ($gaps as $gap)
                            <div class="border border-slate-200 rounded-lg p-4">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-slate-900">{{ $gap['kriteria_nama'] }}</h3>
                                        <p class="text-xs text-slate-600 mt-1">Kode: {{ $gap['kriteria_kode'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <span
                                                class="text-2xl font-bold
                                        @if ($gap['severity'] === 'green') text-green-600 @elseif ($gap['severity'] === 'yellow') text-yellow-600 @else text-red-600 @endif">
                                                {{ $gap['skor_percent'] }}%
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded
                                        @if ($gap['severity'] === 'green') bg-green-100 text-green-800 @elseif ($gap['severity'] === 'yellow') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $gap['status_label'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recommendation -->
                                <div class="bg-slate-50 rounded p-3 mb-3">
                                    <p class="text-xs font-semibold text-slate-700 mb-1">REKOMENDASI</p>
                                    <p class="text-sm text-slate-700">{{ $gap['recommendation'] }}</p>
                                </div>

                                <!-- Unfilled Items -->
                                @if ($gap['unfilled_count'] > 0)
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700 mb-2">
                                            Item yang Belum Lengkap ({{ $gap['unfilled_count'] }})
                                        </p>
                                        <ul class="space-y-2">
                                            @foreach ($gap['unfilled_items'] as $item)
                                                <li class="flex items-start gap-2 text-sm">
                                                    <span class="text-red-500 font-bold mt-0.5">•</span>
                                                    <div>
                                                        <p class="text-slate-900 font-medium">{{ $item['label'] }}</p>
                                                        @if ($item['hint'])
                                                            <p class="text-xs text-slate-600">{{ $item['hint'] }}</p>
                                                        @endif
                                                        <p class="text-xs text-slate-500 mt-1">Tipe:
                                                            {{ ucfirst($item['type']) }} | Bobot: {{ $item['bobot'] }}%
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-green-50 rounded-lg border border-green-200 p-6 text-center">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-green-900 mb-1">Semua Kriteria Terpenuhi</h3>
                    <p class="text-green-800">Tidak ada kesenjangan yang terdeteksi.</p>
                </div>
            @endif
        @else
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6 text-center">
                <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-yellow-900 mb-1">Laporan Belum Tersedia</h3>
                <p class="text-yellow-800">Menunggu submission disetujui validator terlebih dahulu. Laporan akan otomatis
                    ditampilkan setelah ada submission yang diterima.</p>
            </div>
        @endif

        <!-- Generate PDF Laporan -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Laporan Resmi PDF</h2>
            <p class="text-sm text-slate-600 mb-4">
                Generate laporan PDF lengkap dengan grafik radar dan analisis kesenjangan yang dapat diserahkan ke IABEE.
            </p>

            <form action="{{ route('dosen.prodi.laporan.store', $prodi->prodi_id) }}" method="POST"
                class="flex flex-col sm:flex-row gap-3">
                @csrf
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-6-4m6 4l6-4"></path>
                    </svg>
                    Generate Laporan PDF
                </button>
            </form>
        </div>

        <!-- Recent PDFs -->
        @if ($recentLaporans && $recentLaporans->count() > 0)
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Laporan yang Dibuat Sebelumnya</h3>
                </div>

                <div class="divide-y divide-slate-200">
                    @foreach ($recentLaporans as $laporan)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-900">
                                    {{ \Carbon\Carbon::parse($laporan->generated_at)->format('d F Y H:i') }}
                                </p>
                                <p class="text-sm text-slate-600 mt-1">
                                    Skor: <span class="font-semibold">{{ $laporan->skor_total }}%</span>
                                    | Dibuat oleh: <span class="font-semibold">{{ $laporan->user->nama }}</span>
                                </p>
                            </div>
                            <a href="{{ asset('storage/' . $laporan->path_pdf) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded hover:bg-blue-200 transition-colors gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Chart.js Configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = {!! $chartDataJson !!};

            const ctx = document.getElementById('radarChart').getContext('2d');
            const radarChart = new Chart(ctx, {
                type: 'radar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 20,
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(229, 231, 235, 0.5)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.r + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

@endsection
