<x-layouts.app title="Add User - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/adduser.css') }}">

    <section class="adduser-section">
        <div class="form-card">
            <h2>Add User</h2>
            <form id="addUserForm" method="POST" action="{{ route('adduser.store') }}">
                @csrf

                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama user" required>
                </div>

                <div class="input-group">
                    <label for="userid">User ID</label>
                    <input type="text" id="userid" name="userid" placeholder="Masukkan User ID" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="input-group">
                    <label for="rules">Rules</label>
                    <select id="rules" name="rules" required>
                        <option value="">-- Pilih Rules --</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Admin KTT">Admin KTT</option>
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-simpan">Simpan</button>
                    <button type="reset" class="btn-hapus">Hapus</button>
                </div>
            </form>
        </div>

        <div class="user-table-container">
            <h3>Daftar User</h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>User ID</th>
                        <th>Rules</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->userid }}</td>
                            <td>{{ $user->rules }}</td>
                            <td>
                                <form action="{{ route('adduser.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-data">Belum ada user yang ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
