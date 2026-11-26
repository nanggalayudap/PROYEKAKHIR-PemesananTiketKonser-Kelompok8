@php $qris = $order->concert->organizer->qris_image ?? null; @endphp
<x-eventra-layout title="Pembayaran">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-bold tracking-tight">Pembayaran pesanan {{ $order->order_code }}</h1>
        <p class="mt-1 text-stone-600">{{ $order->concert->title }}</p>

        <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm"
             x-data="{
                 end: new Date('{{ $order->expires_at->toIso8601String() }}'),
                 left: '',
                 tick() {
                     const s = Math.max(0, Math.floor((this.end - new Date()) / 1000));
                     this.left = [Math.floor(s / 3600), Math.floor(s % 3600 / 60), s % 60].map(n => String(n).padStart(2, '0')).join(':');
                 }
             }" x-init="tick(); setInterval(() => tick(), 1000)">
            Selesaikan pembayaran sebelum <strong>{{ $order->expires_at->format('d M Y, H:i') }}</strong>
            (sisa waktu <strong x-text="left"></strong>). Lewat batas ini pesanan dibatalkan otomatis.
        </div>

        <div class="mt-6 rounded-lg border border-stone-200 bg-white p-6 text-center">
            <p class="text-sm text-stone-500">Total yang harus dibayar</p>
            <p class="text-3xl font-bold text-teal-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

            @if ($qris)
                <img src="{{ asset('storage/' . $qris) }}" alt="QRIS penyelenggara" class="mx-auto mt-5 w-64 rounded border border-stone-200">
                <p class="mt-3 text-sm text-stone-600">Pindai QRIS milik penyelenggara di atas menggunakan aplikasi pembayaran pilihanmu.</p>
            @else
                <p class="mt-5 rounded bg-stone-100 p-4 text-sm text-stone-600">QRIS penyelenggara belum tersedia. Hubungi penyelenggara konser.</p>
            @endif
        </div>

        <div class="mt-6 rounded-lg border border-stone-200 bg-white p-6">
            <h2 class="font-semibold">Unggah bukti pembayaran</h2>
            <form method="POST" action="{{ route('orders.proof', $order) }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                @csrf
                <input type="file" name="payment_proof" accept="image/png,image/jpeg" required class="block w-full text-sm">
                @error('payment_proof')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
                <button class="rounded bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Kirim bukti pembayaran</button>
            </form>
        </div>

        <a href="{{ route('orders.index') }}" class="mt-6 inline-block text-sm text-teal-700 hover:underline">Bayar nanti, lihat Pesanan Saya</a>
    </div>
</x-eventra-layout>
