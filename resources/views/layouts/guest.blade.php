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
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md rounded-[2rem] bg-white p-8 shadow-[0_24px_80px_-40px_rgba(15,23,42,0.15)]">
            <div class="mb-8 text-center">
                <a href="/" class="inline-flex items-center gap-3 text-slate-900">
                    <x-application-logo class="h-12 w-12" />
                    <span class="text-2xl font-semibold">{{ config('app.name', 'Luna Boutique') }}</span>
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>

</html>
