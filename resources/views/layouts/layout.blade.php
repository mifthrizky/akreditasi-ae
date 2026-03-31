<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'IABEE' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-screen bg-slate-50 font-sans antialiased overflow-hidden">

    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        @include('components.header', ['title' => $headerTitle ?? 'Ringkasan Sistem'])

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>