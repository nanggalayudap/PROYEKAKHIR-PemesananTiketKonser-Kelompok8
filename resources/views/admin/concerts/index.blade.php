<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Moderasi Konser - EVENTRA</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
        <a href="{{ route('admin.users.index') }}">Kelola Pengguna</a> |
        <a href="{{ route('admin.concerts.index') }}">Moderasi Konser</a> |
        <a href="{{ route('admin.orders.index') }}">Monitoring Transaksi</a>
    </nav>
    <hr>

    <h1>Moderasi & Kurasi Konser</h1>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('admin.concerts.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kota...">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>draft</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>published</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>closed</option>
        </select>
        <button type="submit">Filter</button>
        <a href="{{ route('admin.concerts.index') }}">Reset</a>
    </form>
    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Judul Konser</th>
                <th>Organizer</th>
                <th>Kota & Venue</th>
                <th>Tanggal Acara</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarKonser as $concert)
                <tr>
                    <td>{{ $concert->title }}</td>
                    <td>{{ $concert->user->name ?? '-' }}</td>
                    <td>{{ $concert->city }} - {{ $concert->venue }}</td>
                    <td>{{ $concert->event_date }}</td>
                    <td><strong>{{ $concert->status }}</strong></td>
                    <td><a href="{{ route('admin.concerts.show', $concert) }}">Detail & Moderasi</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data konser.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $daftarKonser->withQueryString()->links() }}
    </div>
</body>
</html>