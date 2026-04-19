@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Antrian Review Submission</h1>
                <p class="text-slate-600 mt-1 text-base">Daftar submission yang menunggu validasi</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}"
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

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Total Antrian</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $submissions->total() }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Halaman</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $submissions->currentPage() }} /
                    {{ $submissions->lastPage() }}</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <p class="text-sm text-slate-600 font-medium">Per Halaman</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">10 item</p>
            </div>
        </div>

        <!-- Antrian List (Table Format + Filter) -->
        @if ($submissions->count() > 0)
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <!-- Filter Section -->
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Filter</h3>
                    <form action="{{ route('validator.antrian') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Filter Prodi -->
                            <div>
                                <label for="prodi_id" class="block text-sm font-medium text-slate-700 mb-1">Program
                                    Studi</label>
                                <select id="prodi_id" name="prodi_id"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Semua Prodi --</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->prodi_id }}"
                                            {{ $prodi_id == $prodi->prodi_id ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Kriteria -->
                            <div>
                                <label for="kriteria_id"
                                    class="block text-sm font-medium text-slate-700 mb-1">Kriteria</label>
                                <select id="kriteria_id" name="kriteria_id"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Semua Kriteria --</option>
                                    @foreach ($kriterias as $kriteria)
                                        <option value="{{ $kriteria->kriteria_id }}"
                                            {{ $kriteria_id == $kriteria->kriteria_id ? 'selected' : '' }}>
                                            {{ $kriteria->kode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    Filter
                                </button>
                                <a href="{{ route('validator.antrian') }}"
                                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition-colors text-center">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <!-- Table Header -->
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Program Studi
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Kriteria
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Disubmit Oleh
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Tanggal Submit
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Item
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($submissions as $submission)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <!-- Program Studi -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">
                                                {{ $submission->prodi->kode }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Kriteria -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <p class="font-semibold text-slate-900">{{ $submission->kriteria->kode }}</p>
                                        </div>
                                    </td>

                                    <!-- Disubmit Oleh -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <p class="font-medium text-slate-900">{{ $submission->user->nama }}</p>
                                            <p class="text-xs text-slate-600">{{ $submission->user->email }}</p>
                                        </div>
                                    </td>

                                    <!-- Tanggal Submit -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <p class="font-medium text-slate-900">
                                                {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-slate-600">
                                                {{ \Carbon\Carbon::parse($submission->submitted_at)->format('H:i') }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Item Count -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex px-3 py-1 text-sm font-semibold bg-slate-100 text-slate-800 rounded-full">
                                            {{ $submission->items->count() }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                            Menunggu Review
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('validator.review', $submission->submission_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Menampilkan <span class="font-medium">{{ $submissions->firstItem() ?? 0 }}</span> hingga
                        <span class="font-medium">{{ $submissions->lastItem() ?? 0 }}</span> dari
                        <span class="font-medium">{{ $submissions->total() ?? 0 }}</span> hasil
                    </div>

                    <div class="flex">
                        {{ $submissions->render('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        @else
            <div class="bg-slate-50 rounded-lg border border-slate-200 p-12 text-center shadow-sm">
                <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada submission menunggu review</h3>
                <p class="text-slate-600">Semua submission sudah di-review atau belum ada yang disubmit</p>
            </div>
        @endif
    </div>

@endsection
