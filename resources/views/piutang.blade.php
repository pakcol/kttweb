<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Data Piutang</h2>

        <form id="formPiutang" method="POST" action="{{ route('piutang.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_piutang">NAMA PIUTANG</label>
                    <select id="nama_piutang" name="nama_piutang" class="form-control">
                        <option value="" disabled selected>-- Pilih Nama Piutang --</option>
                        <option>BTN</option>
                        <option>OJK</option>
                        <option>OTHER</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Total Piutang Belum Dibayar</label>
                    <input type="text" name="piutang_belum_dibayar" readonly>
                </div>

                <div class="form-group">
                    <label>Total Piutang</label>
                    <input type="number" name="total_piutang" placeholder="Masukkan total piutang">
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" placeholder="Masukkan harga">
                </div>

                <div class="form-group">
                    <label>Total Diskon</label>
                    <input type="number" name="total_diskon" placeholder="Masukkan total diskon">
                </div>

                <div class="form-group">
                    <label>Diskon</label>
                    <input type="number" name="diskon" placeholder="Masukkan diskon">
                </div>

                <div class="form-group">
                    <label>Kode Booking</label>
                    <input type="text" name="kode_booking" placeholder="Masukkan kode booking">
                </div>

                <div class="form-group">
                    <label>Komisi</label>
                    <input type="number" name="komisi" placeholder="Masukkan komisi">
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" placeholder="Masukkan nama">
                </div>

                <div class="form-group">
                    <label for="pembayaran">PEMBAYARAN</label>
                    <select id="pembayaran" name="pembayaran" class="form-control">
                        <option>LUNAS</option>
                        <option>BCA</option>
                        <option>BNI</option>
                        <option>BTN</option>
                        <option>MANDIRI</option>
                        <option>BRI</option>
                    </select>
                </div>
            </div>

            <div class="btn-actions">
                <button type="submit" class="btn-update">Update</button>
                <button type="button" class="btn-print">Cetak Invoice</button>
            </div>
        </form>
    </div>

    <div class="card-table">
        <h3>Data Piutang</h3>
        <table class="table-piutang">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Komentar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AB123</td>
                    <td>Calvin</td>
                    <td>2.500.000</td>
                    <td>100.000</td>
                    <td>-</td>
                    <td><span class="status-lunas">Lunas</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
</x-layouts.app>
