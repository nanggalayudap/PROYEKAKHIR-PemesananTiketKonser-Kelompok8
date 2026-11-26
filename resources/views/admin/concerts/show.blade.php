<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Moderasi Konser - EVENTRA</title>
</head>
<body>
    <h1>Detail Konser: {{ $concert->title }}</h1>
    <p><a href="{{ route('admin.concerts.index') }}">Kembali ke Daftar Konser</a></p>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <ul>
        <li>Penyelenggara: {{ $concert->user->name ?? '-' }}</li>
        <li>Line-up: {{ $concert->lineup }}</li>
        <li>Venue: {{ $concert->venue }}, {{ $concert->city }}</li>
        <li>Jadwal: {{ $concert->event_date }}</li>
        <li>Status Saat Ini: <strong>{{ $concert->status }}</strong></li>
        <li>Deskripsi: {{ $concert->description }}</li>
    </ul>

    @if ($concert->banner)
        <p><img src="{{ asset('storage/' . $concert->banner) }}" alt="Banner Konser" width="300"></p>
    @endif

    <h3>Kategori Tiket</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Sisa Kuota</th>
                <th>Maks / Order</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($concert->ticketTypes as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>Rp {{ number_format($type->price, 0, ',', '.') }}</td>
                    <td>{{ $type->quota }}</td>
                    <td>{{ $type->max_per_order }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada kategori tiket.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Ubah Status Moderasi</h3>
    <form method="POST" action="{{ route('admin.concerts.update-status', $concert) }}">
        @csrf
        @method('PATCH')
        <select name="status" required>
            <option value="draft" {{ $concert->status === 'draft' ? 'selected' : '' }}>draft</option>
            <option value="published" {{ $concert->status === 'published' ? 'selected' : '' }}>published (Setujui Tayang)</option>
            <option value="closed" {{ $concert->status === 'closed' ? 'selected' : '' }}>closed (Tutup Event)</option>
        </select>
        <button type="submit">Simpan Status</button>
    </form>
</body>
</html>