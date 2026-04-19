@extends('layouts.layout')

@section('content')

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Program Studi Saya</h1>
            <p class="text-slate-600 mt-1 text-base">Pilih program studi untuk melihat daftar kriteria akreditasi</p>
        </div>

        <!-- Program Studi Cards -->
        @if ($prodis->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($prodis as $prodi)
                    <a href="{{ route('dosen.prodi.kriteria', $prodi->prodi_id) }}"
                        class="bg-white rounded-lg border border-slate-200 p-6 hover:shadow-lg hover:border-blue-300 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">Kode</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $prodi->kode }}</p>
                            </div>
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>

                        </div>
                        <h3 class="font-semibold text-slate-900 mb-2">{{ $prodi->nama }}</h3>
                        <p class="text-sm text-slate-600 mb-4">{{ $prodi->jurusan }}</p>
                        <div class="flex items-center text-blue-600 font-medium text-sm">
                            Lihat Kriteria
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg border border-slate-200 p-12 text-center shadow-sm">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m0 0h6M6 12a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak ada program studi yang ditugaskan</h3>
                <p class="text-slate-600">Hubungi administrator untuk ditugaskan ke program studi</p>
            </div>
        @endif
    </div>

@endsection
