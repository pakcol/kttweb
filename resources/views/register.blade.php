<x-layouts.app title="Add Account - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/adduser.css') }}">

    <section class="adduser-section">
        <div class="form-card">
            <h2>Add User</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form id="addUserForm" method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Username" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="name" placeholder="Masukkan nama user" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="roles">Role</label>
                    <select id="roles" name="roles" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="superuser" {{ old('roles') == 'superuser' ? 'selected' : '' }}>Superuser</option>
                        <option value="admin" {{ old('roles') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('roles')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-simpan">Simpan</button>
                    <button type="reset" class="btn-hapus">Reset</button>
                </div>
            </form>
        </div>

        <div class="user-table-container">
            <h3>Daftar User</h3>
            @php
                // Ambil data user dari database
                $users = App\Models\User::all();
            @endphp
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
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->roles }}</td>
                            <td>
                                <form action="{{ route('register.destroy', $user->username) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
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