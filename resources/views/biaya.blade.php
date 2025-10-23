<x-layouts.app title="Data Biaya - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/biaya.css') }}">

    <section class="biaya-section">
        <div class="form-container">
            <h2>Input Data Biaya</h2>

            <form action="#" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" required>
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
                        <th>Keterangan</th>
                        <th>Biaya</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025-10-24</td>
                        <td>Biaya Telpon dan Pulsa</td>
                        <td>250.000</td>
                        <td>Tunai</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
