<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - EVENTRA</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
        <a href="{{ route('admin.users.index') }}">Kelola Pengguna</a> |
        <a href="{{ route('admin.concerts.index') }}">Moderasi Konser</a> |
        <a href="{{ route('admin.orders.index') }}">Monitoring Transaksi</a> |
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
    <hr>

    <h1>Dashboard Statistik Global</h1>

    <ul>
        <li>Total Pengguna: <strong>{{ $totalPengguna }}</strong></li>
        <li>Konser Aktif (Published): <strong>{{ $konserAktif }}</strong></li>
        <li>Total Tiket Terjual: <strong>{{ $tiketTerjual }}</strong></li>
        <li>Total Perputaran Dana: <strong>Rp {{ number_format($perputaranDana, 0, ',', '.') }}</strong></li>
    </ul>

    <h2>Konser Menunggu Moderasi (Draft)</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Judul Konser</th>
                <th>Penyelenggara</th>
                <th>Kota</th>
                <th>Tanggal Acara</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($konserMenungguModerasi as $konser)
                <tr>
                    <td>{{ $konser->title }}</td>
                    <td>{{ $konser->user->name ?? '-' }}</td>
                    <td>{{ $konser->city }}</td>
                    <td>{{ $konser->event_date }}</td>
                    <td><a href="{{ route('admin.concerts.show', $konser) }}">Tinjau</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada konser draft yang menunggu moderasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transaksi Terbaru</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Customer</th>
                <th>Konser</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksiTerbaru as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $order->concert->title ?? '-' }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ $order->status }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}">Detail</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>