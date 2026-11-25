<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi - EVENTRA</title>
</head>
<body>
    <h1>Detail Audit Pesanan: {{ $order->order_code }}</h1>
    <p><a href="{{ route('admin.orders.index') }}">Kembali ke Monitoring Transaksi</a></p>

    <ul>
        <li>Pembeli: {{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</li>
        <li>Konser: {{ $order->concert->title ?? '-' }}</li>
        <li>Total Tagihan: Rp {{ number_format($order->total_price, 0, ',', '.') }}</li>
        <li>Status: <strong>{{ $order->status }}</strong></li>
    </ul>

    @if ($order->payment_proof)
        <h3>Bukti Pembayaran</h3>
        <p><img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" width="300"></p>
    @endif

    <h3>Rincian Tiket Diterbitkan</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Kode Tiket</th>
                <th>Nama Pemegang</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status Check-In</th>
                <th>Waktu Check-In</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_code }}</td>
                    <td>{{ $ticket->attendee_name }}</td>
                    <td>{{ $ticket->ticketType->name ?? '-' }}</td>
                    <td>Rp {{ number_format($ticket->ticketType->price ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $ticket->is_checked_in ? 'Sudah Check-In' : 'Belum Check-In' }}</td>
                    <td>{{ $ticket->checked_in_at ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada tiket diterbitkan untuk pesanan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>