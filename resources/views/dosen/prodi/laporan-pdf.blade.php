<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kesiapan Akreditasi IABEE</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            color: #6b7280;
        }

        .meta-info {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 6px;
            font-size: 12px;
        }

        .meta-info p {
            margin: 5px 0;
        }

        .meta-info strong {
            color: #1f2937;
        }

        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .score-card {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .score-card .score {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }

        .score-card .status {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status-pass {
            color: #10b981;
        }

        .status-fail {
            color: #ef4444;
        }

        .kriteria-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .kriteria-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e5e7eb;
        }

        .kriteria-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .kriteria-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .score-bar {
            width: 100%;
            height: 20px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .score-bar-fill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        .score-green {
            background: #10b981;
        }

        .score-yellow {
            background: #f59e0b;
        }

        .score-red {
            background: #ef4444;
        }

        .gap-item {
            background: #f9fafb;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #ef4444;
            font-size: 12px;
        }

        .gap-item-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .gap-item-text {
            color: #6b7280;
            font-size: 11px;
        }

        .recommendation {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .recommendation-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }

        .recommendation-text {
            color: #78350f;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
        }

        .summary-cell .value {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0;
        }

        .summary-cell .label {
            color: #6b7280;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN KESIAPAN AKREDITASI IABEE 2026</h1>
            <p>Sistem Pedoman Kurikulum</p>
        </div>

        <!-- Meta Info -->
        <div class="meta-info">
            <p><strong>Program Studi:</strong> {{ $prodi->nama }} ({{ $prodi->kode }})</p>
            <p><strong>Jurusan:</strong> {{ $prodi->jurusan }}</p>
            <p><strong>Tanggal Laporan:</strong> {{ $generated_at }}</p>
            <p><strong>Total Karakteristik:</strong> {{ count($scores) }}</p>
        </div>

        <!-- Overall Score -->
        <div class="section">
            <div class="score-card">
                <div>Skor Keseluruhan</div>
                <div class="score">{{ round($total_score, 2) }}%</div>
                <div class="status">
                    @if ($overall_status['status'] === 'passed')
                        <span class="status-pass">✓ Memenuhi Standar IABEE (≥
                            {{ $overall_status['minimum_required'] }}%)</span>
                    @else
                        <span class="status-fail">⚠ Belum Memenuhi Standar IABEE (<
                                {{ $overall_status['minimum_required'] }}%)</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="section">
            <div class="section-title">Ringkasan Statistik</div>
            <div class="summary-grid">
                @php
                    $totalSubmissions = count($scores);
                    $completed = count(array_filter($scores, fn($s) => $s >= 80));
                    $partial = count(array_filter($scores, fn($s) => $s >= 50 && $s < 80));
                    $critical = count(array_filter($scores, fn($s) => $s < 50));
                @endphp

                <div class="summary-cell" style="background: #d1fae5;">
                    <div class="label">Baik (≥80%)</div>
                    <div class="value" style="color: #059669;">{{ $completed }}</div>
                </div>
                <div class="summary-cell" style="background: #fef3c7;">
                    <div class="label">Sebagian (50-80%)</div>
                    <div class="value" style="color: #f59e0b;">{{ $partial }}</div>
                </div>
                <div class="summary-cell" style="background: #fee2e2;">
                    <div class="label">Kritis (<50%)< /div>
                            <div class="value" style="color: #dc2626;">{{ $critical }}</div>
                    </div>
                    <div class="summary-cell" style="background: #e0e7ff;">
                        <div class="label">Total Kriteria</div>
                        <div class="value" style="color: #4f46e5;">{{ $totalSubmissions }}</div>
                    </div>
                </div>
            </div>

            <!-- Scores Table -->
            <div class="section">
                <div class="section-title">Skor per Karakteristik</div>
                <table class="kriteria-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Karakteristik</th>
                            <th style="width: 20%;">Skor</th>
                            <th style="width: 40%;">Visualisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scores as $kriteriaId => $skor)
                            @php
                                $kriteria = $kriterias
                                    ->flatMap(fn($k) => $k->children)
                                    ->firstWhere('kriteria_id', $kriteriaId);
                                $colorClass =
                                    $skor >= 80 ? 'score-green' : ($skor >= 50 ? 'score-yellow' : 'score-red');
                            @endphp
                            <tr>
                                <td>{{ $kriteria?->nama ?? 'N/A' }}</td>
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
            </div>

            <!-- Gap Analysis -->
            @if (count($gaps) > 0)
                <div class="section page-break">
                    <div class="section-title">Analisis Kesenjangan (Gap)</div>
                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 15px;">
                        Daftar di bawah menunjukkan karakteristik yang belum sepenuhnya terpenuhi dan rekomendasi
                        perbaikan.
                    </p>

                    @foreach ($gaps as $gap)
                        @if ($gap['unfilled_count'] > 0)
                            <div class="recommendation">
                                <div class="recommendation-title">{{ $gap['kriteria_nama'] }}
                                    ({{ round($gap['skor_percent'], 2) }}%)
                                </div>
                                <div class="recommendation-text">{{ $gap['recommendation'] }}</div>
                            </div>

                            @foreach ($gap['unfilled_items'] as $item)
                                <div class="gap-item">
                                    <div class="gap-item-title">• {{ $item['label'] }}</div>
                                    <div class="gap-item-text">
                                        Tipe: {{ ucfirst($item['type']) }} | Bobot: {{ $item['bobot'] }}%
                                        @if ($item['hint'])
                                            <br />Panduan: {{ $item['hint'] }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div style="margin-bottom: 20px; border-bottom: 1px solid #e5e7eb;"></div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Recommendations -->
            <div class="section">
                <div class="section-title">Rekomendasi Umum</div>
                @if ($overall_status['status'] === 'passed')
                    <p style="font-size: 12px; line-height: 1.8; margin-bottom: 10px;">
                        <strong>✓ Prodi telah memenuhi standar minimum IABEE 2026.</strong>
                    </p>
                    <p style="font-size: 12px; line-height: 1.8;">
                        Pertahankan dan tingkatkan kualitas dokumentasi kurikulum. Fokus pada karakteristik yang masih
                        memiliki skor di bawah 90% untuk mencapai keunggulan yang lebih tinggi.
                    </p>
                @else
                    <p style="font-size: 12px; line-height: 1.8; margin-bottom: 10px;">
                        <strong>⚠ Prodi belum memenuhi standar minimum IABEE 2026.</strong>
                    </p>
                    <p style="font-size: 12px; line-height: 1.8;">
                        Segera lakukan perbaikan pada karakteristik yang memiliki kesenjangan. Prioritaskan item-item
                        yang
                        ditandai sebagai "Kritis" untuk mencapai standar minimum yang diperlukan.
                    </p>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Laporan ini dibuat secara otomatis oleh Sistem Pedoman Kurikulum Akreditasi IABEE 2026</p>
                <p style="margin-top: 5px;">© 2026 - Semua hak dilindungi</p>
            </div>
        </div>
</body>

</html>
