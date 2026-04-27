@extends('layouts.layout')

@section('content')
    <div class="flex flex-col h-full overflow-hidden p-2 sm:p-4 md:p-8">
        <div class="mb-6 flex-none flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Kelola Izin Akses Halaman</h1>
                <p class="text-slate-600 mt-1">Tentukan role mana saja yang dapat mengakses setiap halaman</p>
            </div>
            <form method="POST" action="{{ route('admin.permissions.reset') }}"
                onsubmit="return confirm('Reset semua izin akses ke default (Admin akses semua)?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-3 py-2 text-sm font-semibold bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors focus:ring-2 focus:ring-red-500 focus:outline-none">
                    Reset
                </button>
            </form>
        </div>

        @if (session('success'))
            <div
                class="mb-4 flex-none p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search --}}
        <form method="GET" class="mb-6 flex-none flex gap-2">
            <input type="text" name="search" placeholder="Cari halaman..." value="{{ request('search') }}"
                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="px-4 py-2 bg-blue-700 font-bold text-white rounded-lg text-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('admin.permissions.index') }}"
                    class="px-4 py-2 bg-slate-200 text-slate-800 font-bold rounded-lg text-sm hover:bg-slate-300 transition-colors focus:ring-2 focus:ring-slate-400 focus:outline-none">
                    Reset
                </a>
            @endif
        </form>

        {{-- Permissions Table --}}
        @php
            $grouped = [];
            foreach ($routePermissions as $perm) {
                $section = $perm->section ?? 'Other';
                if (!isset($grouped[$section])) {
                    $grouped[$section] = [];
                }
                $grouped[$section][] = $perm;
            }
        @endphp

        <!-- Scrollable Table Container -->
        <div class="flex-1 bg-white rounded-xl border border-slate-300 shadow-sm overflow-hidden flex flex-col min-h-0">
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-300 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-900 tracking-wide">Halaman</th>
                            @foreach ($availableRoles as $role)
                                <th class="px-4 py-4 text-center font-bold text-slate-900">
                                    <span
                                        class="inline-block px-3 py-1 bg-blue-100 text-blue-900 border border-blue-200 rounded-md text-xs font-bold uppercase tracking-wider">
                                        {{ $role }}
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($grouped as $section => $perms)
                            <!-- Category Header Row -->
                            <tr class="bg-slate-100 border-t-2 border-slate-300">
                                <td colspan="{{ count($availableRoles) + 1 }}"
                                    class="px-6 py-3 font-bold text-slate-800 uppercase tracking-wider text-xs">
                                    {{ $section }}
                                </td>
                            </tr>
                            <!-- Pages Rows -->
                            @foreach ($perms as $perm)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $perm->page_label }}</div>
                                    </td>
                                    @foreach ($availableRoles as $role)
                                        <td class="px-4 py-3 text-center align-middle">
                                            <label
                                                class="inline-flex items-center cursor-pointer m-0 p-2 hover:bg-slate-100 rounded focus-within:ring-2 focus-within:ring-green-500 transition-colors">
                                                <input type="checkbox" data-route="{{ $perm->route_name }}"
                                                    data-role="{{ $role }}"
                                                    {{ $perm->hasRole($role) ? 'checked' : '' }}
                                                    class="toggle-role w-5 h-5 rounded border-slate-300 text-green-600 focus:ring-green-500 cursor-pointer"
                                                    onchange="togglePermission(this)"
                                                    aria-label="Izinkan {{ $role }} akses {{ $perm->page_label }}">
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach

                        @if (count($grouped) === 0)
                            <tr>
                                <td colspan="{{ count($availableRoles) + 1 }}"
                                    class="px-6 py-12 text-center text-slate-500 font-medium">
                                    Tidak ada halaman yang ditemukan.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function togglePermission(checkbox) {
            const routeName = checkbox.dataset.route;
            const role = checkbox.dataset.role;
            const enabled = checkbox.checked;

            fetch(`/admin/permissions/${routeName}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        role,
                        enabled
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        checkbox.checked = !enabled;
                        alert('Terjadi kesalahan saat menyimpan');
                    }
                })
                .catch(() => {
                    checkbox.checked = !enabled;
                    alert('Gagal menyimpan izin');
                });
        }
    </script>
@endsection
