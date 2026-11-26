@php
    $map = [
        'pending'              => ['Menunggu pembayaran', 'bg-amber-100 text-amber-800'],
        'waiting_verification' => ['Menunggu verifikasi', 'bg-blue-100 text-blue-800'],
        'paid'                 => ['Lunas', 'bg-green-100 text-green-800'],
        'rejected'             => ['Ditolak', 'bg-red-100 text-red-800'],
        'expired'              => ['Kedaluwarsa', 'bg-stone-200 text-stone-700'],
    ];
    [$label, $class] = $map[$status] ?? [$status, 'bg-stone-200 text-stone-700'];
@endphp
<span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">{{ $label }}</span>
