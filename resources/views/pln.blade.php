<x-layouts.app title="PLN - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/pln.css') }}">

<section class="pln-section">
    <div class="card-container">
        <h2 class="form-title">PLN</h2>
        <form action="{{ route('pln.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="id_pel">ID PELANGGAN*</label>
                    <input type="text" id="id_pel" name="id_pel" class="form-control" placeholder="Masukkan No Pelanggan" required>
                </div>

                <div class="form-group">
                    <label for="harga_jual">HARGA JUAL*</label>
                    <input type="text" id="harga_jual" name="harga_jual" class="form-control" placeholder="Masukkan Nominal Harga Jual" required>
                </div>

                <div class="form-group">
                    <label for="transaksi">TRANSAKSI</label>
                    <input type="text" id="transaksi" name="transaksi" class="form-control" placeholder="Masukkan Transaksi" required>
                </div>

                <div class="form-group">
                    <label for="tgl">TANGGAL*</label>
                    <input type="date" id="tgl" name="tgl" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="bayar">BAYAR*</label>
                    <select id="bayar" name="bayar" class="form-control" required>
                        <option value="">-- PILIH --</option>
                        <option value="TUNAI">TUNAI</option>
                        <option value="PIUTANG">PIUTANG</option>
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="MANDIRI">MANDIRI</option>
                        <option value="BRI">BRI</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_piutang">NAMA PIUTANG</label>
                    <input type="text" id="nama_piutang" name="nama_piutang" class="form-control" placeholder="Masukkan Nama Piutang">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-green">SAVE</button>
                <button type="reset" class="btn btn-red">CANCEL</button>
                <button type="button" class="btn btn-blue">TOP UP</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <h2>Data PLN</h2>
        <table class="table-pln">
            <thead>
                <tr>
                    <th>ID Pelanggan</th>
                    <th>Harga Jual</th>
                    <th>Transaksi</th>
                    <th>Tanggal</th>
                    <th>Bayar</th>
                    <th>Nama Piutang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                <tr>
                    <td>{{ $row->id_pel }}</td>
                    <td>{{ number_format($row->harga_jual) }}</td>
                    <td>{{ number_format($row->transaksi) }}</td>
                    <td>{{ $row->tgl }}</td>
                    <td>{{ $row->bayar }}</td>
                    <td>{{ $row->nama_piutang }}</td>
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
