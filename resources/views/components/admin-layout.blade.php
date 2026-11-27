@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' — ' : '' }}Admin · Eventra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">

    {{-- Top bar --}}
    <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-stone-200 bg-white px-6 shadow-sm">
        <div class="flex items-center gap-4">
            {{-- Mobile hamburger --}}
            <button class="lg:hidden text-stone-500 hover:text-stone-800" x-data @click="$dispatch('toggle-sidebar')">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-teal-800 tracking-tight">Eventra <span class="text-xs font-medium text-stone-400 ml-1">Admin</span></a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="hidden sm:block text-sm text-stone-600 hover:text-teal-700">{{ Auth::user()->name }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-md border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50 hover:text-stone-800">Keluar</button>
            </form>
        </div>
    </header>

    <div class="flex" x-data="{ open: window.innerWidth >= 1024 }" @toggle-sidebar.window="open = !open">

        {{-- Sidebar --}}
        <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 top-14 z-20 flex w-60 flex-col border-r border-stone-200 bg-white pt-4 pb-8 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">
            <nav class="flex-1 space-y-0.5 px-3">
                @php
                    $navItems = [
                        ['label' => 'Dashboard',              'route' => 'admin.dashboard',      'match' => 'admin.dashboard'],
                        ['label' => 'Kelola Pengguna',        'route' => 'admin.users.index',    'match' => 'admin.users.*'],
                        ['label' => 'Moderasi Konser',        'route' => 'admin.concerts.index', 'match' => 'admin.concerts.*'],
                        ['label' => 'Monitoring Transaksi',   'route' => 'admin.orders.index',   'match' => 'admin.orders.*'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs($item['match'])
                                    ? 'bg-teal-50 text-teal-800'
                                    : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto px-3 pt-4 border-t border-stone-100">
                <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-stone-500 hover:text-teal-700">
                    &larr; Kembali ke situs publik
                </a>
            </div>
        </aside>

        {{-- Overlay for mobile --}}
        <div x-show="open" @click="open = false" class="fixed inset-0 z-10 bg-black/30 lg:hidden" x-cloak></div>

        {{-- Main content --}}
        <main class="flex-1 overflow-auto px-4 py-8 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
