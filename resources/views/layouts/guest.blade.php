@props(['maxWidth' => 'md'])

@php
    $maxWidthClass = match ($maxWidth) {
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        default => 'sm:max-w-md',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Eventra') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none !important}</style>
    </head>
    <body class="font-sans text-[#18184A] antialiased bg-[#F6F8FC] selection:bg-[#23238E] selection:text-white">
        <div class="relative min-h-screen flex flex-col justify-center items-center px-4 py-10 overflow-hidden bg-gradient-to-br from-[#FCE2C1]/40 via-[#F6F8FC] to-[#F5A4E3]/30">
            <!-- Decorative ambient background -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-[#FCE2C1]/50 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-[#F5A4E3]/40 blur-3xl"></div>
                <div class="absolute top-1/3 right-1/4 h-64 w-64 rounded-full bg-[#3C84D8]/10 blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full {{ $maxWidthClass }} flex flex-col items-center">
                <a href="{{ url('/') }}" class="mb-6 transition-transform duration-200 hover:scale-[1.03] focus:outline-none focus:ring-2 focus:ring-[#3C84D8] focus:ring-offset-4 rounded-2xl">
                    <x-application-logo class="h-24 w-auto" />
                </a>

                <div class="w-full bg-white rounded-3xl border-2 border-[#23238E] shadow-[6px_6px_0px_#23238E] px-6 py-7 sm:px-8 sm:py-8">
                    {{ $slot }}
                </div>

                <p class="mt-7 text-center text-xs font-medium text-[#646B8A]">
                    &copy; {{ date('Y') }} Eventra. Platform Pemesanan Tiket Konser.
                </p>
            </div>
        </div>
    </body>
</html>
