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
<body class="min-h-screen bg-[#F6F8FC] text-[#18184A] antialiased overflow-x-hidden">
    <div id="eventra-progress" class="fixed top-0 left-0 z-50 h-0.5 w-0 bg-[#3C84D8] opacity-0 transition-all duration-200 pointer-events-none print:hidden"></div>

    <header class="sticky top-0 z-40 border-b border-[#E2E7F5] bg-white/95 backdrop-blur-sm print:hidden">
        <nav class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-2.5">
            <a href="{{ route('home') }}" data-pjax data-pjax-brand class="inline-flex items-center">
                <x-application-logo class="h-10 w-auto" />
            </a>
            <div class="flex items-center gap-5 text-sm">
                <a href="{{ route('home') }}" data-pjax class="{{ request()->routeIs('home') ? 'font-semibold text-[#23238E]' : 'text-[#646B8A]' }} transition-colors hover:text-[#3C84D8]">Beranda</a>
                <a href="{{ route('concerts.index') }}" data-pjax class="{{ request()->routeIs('concerts.*') ? 'font-semibold text-[#23238E]' : 'text-[#646B8A]' }} transition-colors hover:text-[#3C84D8]">Daftar Konser</a>
                <a href="{{ route('about') }}" data-pjax class="{{ request()->routeIs('about') ? 'font-semibold text-[#23238E]' : 'text-[#646B8A]' }} transition-colors hover:text-[#3C84D8]">Tentang Kami</a>
                <span class="hidden h-5 w-px bg-[#E2E7F5] sm:block"></span>
                @auth
                    @role('customer')
                        <a href="{{ route('tickets.index') }}" data-pjax class="{{ request()->routeIs('tickets.*') ? 'font-semibold text-[#23238E]' : 'text-[#646B8A]' }} transition-colors hover:text-[#3C84D8]">Tiket Saya</a>
                        <a href="{{ route('orders.index') }}" data-pjax class="{{ request()->routeIs('orders.*') ? 'font-semibold text-[#23238E]' : 'text-[#646B8A]' }} transition-colors hover:text-[#3C84D8]">Riwayat</a>
                    @endrole
                    <a href="{{ route('dashboard') }}" class="text-[#646B8A] transition-colors hover:text-[#3C84D8]">Dashboard</a>

                    {{-- Dropdown nama pengguna --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = ! open" class="flex items-center gap-1 font-medium text-[#18184A] hover:text-[#3C84D8]">
                            {{ auth()->user()->name }}
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 z-10 mt-2 w-44 rounded-xl border border-[#E2E7F5] bg-white py-1 shadow-lg">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-[#18184A] hover:bg-[#F6F8FC]">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-[#18184A] hover:bg-[#F6F8FC]">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-[#3C84D8] transition-colors hover:text-[#23238E]">Login</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-[#23238E] px-3.5 py-1.5 font-semibold text-white transition-colors hover:bg-[#3C84D8]">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="eventra-main" class="page-stage mx-auto max-w-6xl px-4 py-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-[#DCFCE7] bg-[#DCFCE7] px-4 py-3 text-sm font-medium text-[#15803D] print:hidden">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-xl border border-[#FFE4E6] bg-[#FFE4E6] px-4 py-3 text-sm font-medium text-[#BE123C] print:hidden">{{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
