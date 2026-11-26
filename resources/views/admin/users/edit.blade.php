<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna - EVENTRA</title>
</head>
<body>
    <h1>Edit Pengguna: {{ $user->name }}</h1>
    <p><a href="{{ route('admin.users.index') }}">Kembali ke Daftar Pengguna</a></p>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <p>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </p>
        <p>
            <label>Role:</label><br>
            <select name="role" required>
                @foreach ($daftarRole as $role)
                    <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Password Baru (kosongkan jika tidak diubah):</label><br>
            <input type="password" name="password">
        </p>
        <p>
            <label>Konfirmasi Password Baru:</label><br>
            <input type="password" name="password_confirmation">
        </p>
        <button type="submit">Perbarui Pengguna</button>
    </form>
</body>
</html>