@extends('layouts.layout')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="text-sm font-medium text-slate-500 mb-1">Total Capaian Pembelajaran (CPL)</div>
        <div class="text-3xl font-bold text-slate-900">12</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="text-sm font-medium text-slate-500 mb-1">Mata Kuliah Terpetakan</div>
        <div class="text-3xl font-bold text-slate-900">45</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="text-sm font-medium text-slate-500 mb-1">Status Evaluasi Semester Ini</div>
        <div class="text-3xl font-bold text-green-600">85%</div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Aktivitas Terbaru</h2>
    <div class="text-slate-500 text-sm text-center py-10 border-2 border-dashed border-slate-200 rounded-lg">
        Tabel data dinamis akan dirender di sini setelah arsitektur database selesai.
    </div>
</div>
@endsection