<div class="space-y-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Detail Riwayat Validasi</h3>
            <p class="text-sm text-slate-600 mt-0.5">
                {{ $auditLog->getActionLabel() }} pada {{ optional($auditLog->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Program Studi</div>
            <div class="mt-1 text-sm font-medium text-slate-900">{{ optional($submission->prodi)->nama ?? '-' }}</div>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Sub-Kriteria</div>
            <div class="mt-1 text-sm font-medium text-slate-900">
                {{ optional($submission->kriteria)->kode ?? '-' }}
                <span class="text-slate-600 font-normal">{{ optional($submission->kriteria)->nama ? ' - ' . $submission->kriteria->nama : '' }}</span>
            </div>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Dosen</div>
            <div class="mt-1 text-sm font-medium text-slate-900">{{ optional($submission->user)->nama ?? '-' }}</div>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Validator</div>
            <div class="mt-1 text-sm font-medium text-slate-900">{{ optional($auditLog->user)->nama ?? (optional($auditLog->user)->email ?? '-') }}</div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="px-4 py-3 border-b border-slate-200">
            <h4 class="text-sm font-semibold text-slate-900">Perubahan</h4>
        </div>

        <div class="p-4">
            @php
                $fields = $auditLog->changed_fields ?? [];
                $old = $auditLog->old_values ?? [];
                $new = $auditLog->new_values ?? [];
            @endphp

            @if(empty($fields))
                <div class="text-sm text-slate-600">Tidak ada detail perubahan yang tersimpan.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="text-left py-2 pr-4">Field</th>
                                <th class="text-left py-2 pr-4">Sebelum</th>
                                <th class="text-left py-2">Sesudah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($fields as $field)
                                @php
                                    $oldVal = data_get($old, $field);
                                    $newVal = data_get($new, $field);
                                    $fmt = function ($v) {
                                        if (is_null($v)) return '-';
                                        if (is_bool($v)) return $v ? 'true' : 'false';
                                        if (is_array($v)) return json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                        return (string) $v;
                                    };
                                @endphp
                                <tr>
                                    <td class="py-3 pr-4 font-medium text-slate-900 align-top">{{ $field }}</td>
                                    <td class="py-3 pr-4 align-top">
                                        <pre class="whitespace-pre-wrap break-words text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-md p-2">{{ $fmt($oldVal) }}</pre>
                                    </td>
                                    <td class="py-3 align-top">
                                        <pre class="whitespace-pre-wrap break-words text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-md p-2">{{ $fmt($newVal) }}</pre>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
