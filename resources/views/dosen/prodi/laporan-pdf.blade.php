<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Kesiapan Akreditasi IABEE</title>
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */

        @page {
            /* Ini memaksa mesin PDF memberi jarak 40px di atas/bawah dan 50px kiri/kanan di SETIAP HALAMAN */
            margin: 40px 50px;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #333333;
            font-size: 12px;
        }

        body {
            /* Gunakan font yang aman untuk PDF */
            font-family: Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #333333;
            font-size: 12px;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 14px;
            color: #6b7280;
        }

        .meta-info {
            background-color: #f3f4f6;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }

        .meta-info p {
            margin: 4px 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 1px solid #d1d5db;
            text-transform: uppercase;
        }

        /* Perbaikan Score Card: Warna Solid, bukan Gradient */
        .score-card {
            background-color: #2563eb;
            color: #ffffff;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            text-align: center;
        }

        .score-card .score-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .score-card .score {
            font-size: 42px;
            font-weight: bold;
            margin: 10px 0;
        }

        .score-card .status {
            font-size: 14px;
            font-weight: bold;
            background-color: #ffffff;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
        }

        .status-pass {
            color: #10b981;
        }

        .status-fail {
            color: #ef4444;
        }

        /* Perbaikan Grid Summary menggunakan Table agar stabil di DomPDF */
        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .summary-grid td {
            width: 25%;
            padding: 15px 10px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .summary-grid .value {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }

        .summary-grid .label {
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
        }

        .kriteria-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .kriteria-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }

        .kriteria-table td {
            padding: 10px;
            border: 1px solid #d1d5db;
        }

        /* Perbaikan Score Bar: Buang Flexbox, gunakan block dan line-height */
        .score-bar {
            width: 100%;
            height: 18px;
            background-color: #e5e7eb;
            border-radius: 2px;
            position: relative;
        }

        .score-bar-fill {
            height: 100%;
            display: block;
            text-align: center;
            line-height: 18px;
            /* Sama dengan height score-bar */
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        .score-green {
            background-color: #10b981;
        }

        .score-yellow {
            background-color: #f59e0b;
        }

        .score-red {
            background-color: #ef4444;
        }

        .recommendation {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin-bottom: 15px;
            border-right: 1px solid #e5e7eb;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .gap-item {
            background-color: #f9fafb;
            padding: 10px;
            margin-bottom: 8px;
            border-left: 3px solid #ef4444;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN KESIAPAN AKREDITASI IABEE 2026</h1>
            <p>Sistem Pemeriksa Pedoman Kurikulum</p>
        </div>

        <div class="meta-info">
            <p><strong>Program Studi:</strong> {{ $prodi->nama }} ({{ $prodi->kode }})</p>
            <p><strong>Jurusan:</strong> {{ $prodi->jurusan }}</p>
            <p><strong>Tanggal Laporan:</strong> {{ $generated_at }}</p>
            <p><strong>Total Karakteristik:</strong> {{ count($scores) }}</p>
        </div>

        <div class="score-card">
            <div class="score-label">Skor Keseluruhan</div>
            <div class="score">{{ round($total_score, 2) }}%</div>
            <div class="status">
                @if ($overall_status['status'] === 'passed')
                    <span class="status-pass">&#10003; Memenuhi Standar IABEE (>=
                        {{ $overall_status['minimum_required'] }}%)</span>
                @else
                    <span class="status-fail">&#9888; Belum Memenuhi Standar IABEE (&lt;
                        {{ $overall_status['minimum_required'] }}%)</span>
                @endif
            </div>
        </div>

        <div class="section-title">Ringkasan Statistik</div>
        <table class="summary-grid">
            @php
                $totalSubmissions = count($scores);
                $completed = count(array_filter($scores, fn($s) => $s >= 80));
                $partial = count(array_filter($scores, fn($s) => $s >= 50 && $s < 80));
                $critical = count(array_filter($scores, fn($s) => $s < 50));
            @endphp
            <tr>
                <td style="background-color: #d1fae5;">
                    <div class="label">Baik (>= 80%)</div>
                    <div class="value" style="color: #059669;">{{ $completed }}</div>
                </td>
                <td style="background-color: #fef3c7;">
                    <div class="label">Sebagian (50-80%)</div>
                    <div class="value" style="color: #d97706;">{{ $partial }}</div>
                </td>
                <td style="background-color: #fee2e2;">
                    <div class="label">Kritis (&lt; 50%)</div>
                    <div class="value" style="color: #dc2626;">{{ $critical }}</div>
                </td>
                <td style="background-color: #e0e7ff;">
                    <div class="label">Total Kriteria</div>
                    <div class="value" style="color: #4f46e5;">{{ $totalSubmissions }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">Skor per Karakteristik</div>
        <table class="kriteria-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Karakteristik</th>
                    <th style="width: 15%; text-align: center;">Skor</th>
                    <th style="width: 40%;">Visualisasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scores as $kriteriaId => $skor)
                    @php
                        $kriteriaNama = $kriteriaMap[$kriteriaId] ?? 'N/A';
                        $colorClass = $skor >= 80 ? 'score-green' : ($skor >= 50 ? 'score-yellow' : 'score-red');
                    @endphp
                    <tr>
                        <td>{{ $kriteriaNama }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ round($skor, 2) }}%</td>
                        <td>
                            <div class="score-bar">
                                <div class="score-bar-fill {{ $colorClass }}"
                                    style="width: {{ min($skor, 100) }}%;">
                                    {{ round($skor, 0) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (count($gaps) > 0)
            <div class="page-break"></div>
            <div class="section-title">Analisis Kesenjangan (Gap)</div>
            <p style="margin-bottom: 15px;">Daftar di bawah menunjukkan karakteristik yang belum sepenuhnya terpenuhi
                dan rekomendasi perbaikan.</p>

            @foreach ($gaps as $gap)
                @if ($gap['unfilled_count'] > 0)
                    <div class="recommendation">
                        <strong>{{ $gap['kriteria_nama'] }} ({{ round($gap['skor_percent'], 2) }}%)</strong><br>
                        {{ $gap['recommendation'] }}
                    </div>

                    @foreach ($gap['unfilled_items'] as $item)
                        <div class="gap-item">
                            <strong>&bull; {{ $item['label'] }}</strong><br>
                            <span style="color: #6b7280;">Tipe: {{ ucfirst($item['type']) }} | Bobot:
                                {{ $item['bobot'] }}%</span>
                            @if ($item['hint'])
                                <br><span style="color: #6b7280;">Panduan: {{ $item['hint'] }}</span>
                            @endif
                        </div>
                    @endforeach
                    <br>
                @endif
            @endforeach
        @endif

        <div class="footer">
            <p>Laporan ini dibuat secara otomatis oleh Sistem Pedoman Kurikulum Akreditasi IABEE 2026</p>
        </div>
    </div>
</body>

</html>
