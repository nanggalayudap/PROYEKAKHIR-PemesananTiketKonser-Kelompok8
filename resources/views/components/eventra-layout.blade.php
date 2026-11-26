@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}Eventra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important}</style>
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="border-b border-stone-200 bg-white print:hidden">
        <nav class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-3">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-teal-800">Eventra</a>
            <div class="flex items-center gap-5 text-sm">
                <a href="{{ route('home') }}" class="hover:text-teal-700">Beranda</a>
                <a href="{{ route('concerts.index') }}" class="hover:text-teal-700">Daftar Konser</a>
                <a href="{{ route('about') }}" class="hover:text-teal-700">Tentang Kami</a>
                <span class="hidden h-5 w-px bg-stone-300 sm:block"></span>
                @auth
                    @role('customer')
                        <a href="{{ route('tickets.index') }}" class="hover:text-teal-700">Tiket Saya</a>
                        <a href="{{ route('orders.index') }}" class="hover:text-teal-700">Riwayat</a>
                    @endrole
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-700">Dashboard</a>

                    {{-- Dropdown nama pengguna --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = ! open" class="flex items-center gap-1 font-medium hover:text-teal-700">
                            {{ auth()->user()->name }}
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 z-10 mt-2 w-44 rounded-md border border-stone-200 bg-white py-1 shadow-md">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-stone-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-stone-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:text-teal-700">Login</a>
                    <a href="{{ route('register') }}" class="rounded bg-teal-700 px-3 py-1.5 text-white hover:bg-teal-800">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('success'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 print:hidden">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 print:hidden">{{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
