<x-layouts.app title="Add Account - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/adduser.css') }}">

    <section class="adduser-section">
        <div class="form-card">
            <h2>Add User</h2>
            <form id="addUserForm" method="POST" action="{{ route('addaccount.store') }}">
                @csrf

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Username" required>
                </div>
                
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="name" placeholder="Masukkan nama user" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                
                <div class="input-group">
                    <label for="roles">Role</label>
                    <select id="roles" name="roles" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="superuser">Superuser</option>
                        <option value="admin">Admin</option>
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
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->roles }}</td>
                            <td>
                                <form action="{{ route('addaccount.destroy', $user->username) }}" method="POST">
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
