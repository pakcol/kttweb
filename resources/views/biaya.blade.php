<x-layouts.app title="Data Biaya - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/biaya.css') }}">

    <section class="biaya-section">
        <div class="form-container">
            <h2>Input Data Biaya</h2>

            <form action="#" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tgl">Tanggal</label>
                        <input type="date" id="tgl" name="tgl" required>
                    </div>

                    <div class="form-group">
                        <label for="jam">Jam</label>
                        <input type="time" id="jam" name="jam" required>
                    </div>

                    <div class="form-group">
                        <label for="biaya">Biaya</label>
                        <input type="text" id="biaya" name="biaya" placeholder="Masukkan jumlah biaya..." required>
                    </div>

                    <div class="form-group">
                        <label for="pembayaran">Pembayaran</label>
                        <select id="pembayaran" name="pembayaran" required>
                            <option value="">-- Pilih Pembayaran --</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <select id="keterangan" name="keterangan" required>
                            <option value="">-- Pilih Keterangan --</option>
                            <option>Biaya Telpon dan Pulsa</option>
                            <option>Biaya PDAM</option>
                            <option>Biaya Listrik</option>
                            <option>Biaya BPJS</option>
                            <option>Biaya Jamsostek</option>
                            <option>Biaya ATK</option>
                            <option>Biaya Transportasi</option>
                            <option>Biaya Perawatan</option>
                            <option>Biaya Surat Ijin/Reklame</option>
                            <option>Pajak</option>
                            <option>Gaji</option>
                            <option>Biaya Lain-lain</option>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="reset" class="btn-hapus">HAPUS</button>
                    <button type="submit" class="btn-simpan">SIMPAN</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Data Biaya</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Biaya</th>
                        <th>Pembayaran</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($biaya as $row)
                    <tr>
                        <td>{{ $row->tgl }}</td>
                        <td>{{ $row->jam }}</td>
                        <td>{{ number_format($row->biaya) }}</td>
                        <td>{{ $row->pembayaran }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td>{{ $row->username }}</td>
                        <td>
                            <button class="btn btn-edit">Edit</button>
                            <button class="btn btn-delete">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
