<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'IABEE' }}</title>
    <link rel="shortcut icon" sizes="114x114" href="/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js for radar charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>

<body class="flex h-screen bg-slate-50 font-sans antialiased overflow-hidden relative">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" 
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden"
        onclick="toggleSidebar()"></div>

    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden w-full">

        @include('components.header', ['title' => 'Evaluasi Panduan Pedoman Kurikulum '])

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    
    @stack('scripts')
</body>

</html>
