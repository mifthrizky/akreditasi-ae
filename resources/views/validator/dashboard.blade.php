@extends('layouts.layout')

@section('content')
<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Validator</h1>
            <p class="text-slate-700 mt-1 text-base">Pilih Program Studi yang ditugaskan kepada Anda untuk mulai mengisi dokumen persiapan akreditasi IABEE.</p>
        </div>
    </div>

    <!-- Alert Messages (Optional) -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-700 p-4 rounded" role="alert">
            <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
        </div>
    @endif


</div>
@endsection
