@extends('layouts.layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @include('validator.riwayat._detail', ['auditLog' => $auditLog, 'submission' => $submission])
        </div>
    </div>
@endsection
