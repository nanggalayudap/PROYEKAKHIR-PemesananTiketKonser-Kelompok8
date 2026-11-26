<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - EVENTRA</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
        <a href="{{ route('admin.users.index') }}">Kelola Pengguna</a> |
        <a href="{{ route('admin.concerts.index') }}">Moderasi Konser</a> |
        <a href="{{ route('admin.orders.index') }}">Monitoring Transaksi</a>
    </nav>
    <hr>

    <h1>Manajemen Pengguna & Hak Akses</h1>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <p><a href="{{ route('admin.users.create') }}">+ Tambah Pengguna Baru</a></p>

    <form method="GET" action="{{ route('admin.users.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
        <select name="role">
            <option value="">Semua Role</option>
            @foreach ($daftarRole as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Filter</button>
        <a href="{{ route('admin.users.index') }}">Reset</a>
    </form>
    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarPengguna as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}">Edit</a> |
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Data pengguna tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $daftarPengguna->withQueryString()->links() }}
    </div>
</body>
</html>