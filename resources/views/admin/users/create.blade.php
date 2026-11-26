<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna - EVENTRA</title>
</head>
<body>
    <h1>Tambah Pengguna Baru</h1>
    <p><a href="{{ route('admin.users.index') }}">Kembali ke Daftar Pengguna</a></p>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <p>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>
        <p>
            <label>Role:</label><br>
            <select name="role" required>
                @foreach ($daftarRole as $role)
                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Password (min. 8 karakter):</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <label>Konfirmasi Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </p>
        <button type="submit">Simpan Pengguna</button>
    </form>
</body>
</html>