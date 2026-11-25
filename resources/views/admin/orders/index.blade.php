<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Transaksi - EVENTRA</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
        <a href="{{ route('admin.users.index') }}">Kelola Pengguna</a> |
        <a href="{{ route('admin.concerts.index') }}">Moderasi Konser</a> |
        <a href="{{ route('admin.orders.index') }}">Monitoring Transaksi</a>
    </nav>
    <hr>

    <h1>Monitoring Transaksi Global</h1>

    <form method="GET" action="{{ route('admin.orders.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan...">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>pending</option>
            <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>waiting_verification</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>paid</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>rejected</option>
        </select>
        <button type="submit">Filter</button>
        <a href="{{ route('admin.orders.index') }}">Reset</a>
    </form>
    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Customer</th>
                <th>Konser</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Waktu Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarPesanan as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $order->concert->title ?? '-' }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><strong>{{ $order->status }}</strong></td>
                    <td>{{ $order->created_at }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}">Detail Audit</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada transaksi tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $daftarPesanan->withQueryString()->links() }}
    </div>
</body>
</html>