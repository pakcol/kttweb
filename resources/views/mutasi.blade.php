<x-layouts.app title="Mutasi Airlines">

<link rel="stylesheet" href="{{ asset('css/mutasi.css') }}">

<section class="mutasi-airlines-section">
<div class="mutasi-container">
    <h2>MUTASI AIRLINES</h2>

    <form action="{{ route('mutasi-airlines.store') }}" method="POST" class="mutasi-airlines">
        @csrf
        <div class="form-group">
            <label>TANGGAL</label>
            <input type="date" name="tanggal" required>
        </div>

        <div class="form-group">
            <label>TOP UP</label>
            <input type="number" name="topup" placeholder="Masukkan nominal">
        </div>

        <div class="form-group">
            <label>BANK</label>
            <select name="bank" required>
                <option value="">Pilih Bank</option>
                <option value="bca">BCA</option>
                <option value="bni">BNI</option>
                <option value="bri">BRI</option>
                <option value="mandiri">MANDIRI</option>
            </select>
        </div>

        <div class="form-group">
            <label>BIAYA</label>
            <input type="number" name="biaya" placeholder="Masukkan biaya">
        </div>

        <div class="form-group">
            <label>INSENTIF</label>
            <input type="number" name="insentif" placeholder="Masukkan insentif">
        </div>

        <div class="form-group">
            <label>KETERANGAN</label>
            <input type="text" name="keterangan" placeholder="Contoh: Pengeluaran tiket, bonus, dll">
        </div>

        <div class="form-group">
            <label>AIRLINES</label>
            <select name="airlines" required>
                <option value="">Pilih Maskapai</option>
                <option value="citilink">Citilink</option>
                <option value="garuda">Garuda</option>
                <option value="lion">Lion</option>
                <option value="sriwijaya">Sriwijaya</option>
                <option value="qgcorner">QGCorner</option>
                <option value="transnusa">Transnusa</option>
                <option value="pelni">Pelni</option>
                <option value="airasia">Air Asia</option>
                <option value="dlu">DLU</option>
            </select>
        </div>

        <button type="submit" class="btn-update">SIMPAN</button>
    </form>

    <div class="table-section">
        <h3>Data Mutasi Airlines</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Top Up</th>
                    <th>Bank</th>
                    <th>Biaya</th>
                    <th>Insentif</th>
                    <th>Keterangan</th>
                    <th>Airlines</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ number_format($row->topup, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($row->bank) }}</td>
                    <td>{{ number_format($row->biaya, 0, ',', '.') }}</td>
                    <td>{{ number_format($row->insentif, 0, ',', '.') }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ strtoupper($row->airlines) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</section>

</x-layouts.app>
