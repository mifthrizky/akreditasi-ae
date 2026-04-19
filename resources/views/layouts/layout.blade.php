<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'IABEE' }}</title>
    <link rel="shortcut icon" sizes="114x114" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js for radar charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>

<body class="flex h-screen bg-slate-50 font-sans antialiased overflow-hidden">

    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        @include('components.header', ['title' => 'Evaluasi Kurikulum '])

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>
