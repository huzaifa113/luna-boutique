<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Luna Boutique') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white/80 border-b border-slate-200/70 backdrop-blur-xl shadow-sm">
            <div class="site-container py-6">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="site-container py-10">
        {{ $slot }}
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>

</html>
