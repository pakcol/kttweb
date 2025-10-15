<x-layouts.app title="Input Data - PT. Kupang Tour & Travel">

<section class="input-data-section">
    <div class="form-container">
        <h2>INPUT DATA</h2>
        <div class="current-time" id="currentDateTime"></div>

        <form id="inputDataForm" method="POST" action="{{ route('input-data.store') }}">
            @csrf
            <input type="hidden" id="tiketId" name="tiket_id">
            <input type="hidden" id="jam_input" name="jam_input"> 

            <div class="input-grid">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="form-group">
                        <label for="tgl_issued">TGL ISSUED</label>
                        <input type="date" id="tgl_issued" name="tgl_issued" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_booking">KODE BOOKING</label>
                        <input type="text" id="kode_booking" name="kode_booking" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="airlines">AIRLINES</label>
                        <select id="airlines" name="airlines" class="text-uppercase" required>
                            <option value="">Pilih Airlines</option>
                            <option value="AIRASIA">AIR ASIA</option>
                            <option value="CITILINK">CITILINK</option>
                            <option value="DLU">DLU</option>
                            <option value="GARUDA">GARUDA</option>
                            <option value="KAI">KAI</option>
                            <option value="LION">LION</option>
                            <option value="PELNI">PELNI</option>
                            <option value="QGCORNER">QGCORNER</option>
                            <option value="SRIWIJAYA">SRIWIJAYA</option>
                            <option value="TRANSNUSA">TRANSNUSA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama">NAMA</label>
                        <input type="text" id="nama" name="nama" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="rute1">RUTE 1</label>
                        <input type="text" id="rute1" name="rute1" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_flight1">TGL FLIGHT1</label>
                        <input type="date" id="tgl_flight1" name="tgl_flight1" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rute2">RUTE 2</label>
                        <input type="text" id="rute2" name="rute2" class="text-uppercase">
                    </div>
                    <div class="form-group">
                        <label for="tgl_flight2">TGL FLIGHT2</label>
                        <input type="date" id="tgl_flight2" name="tgl_flight2">
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="form-group">
                        <label for="harga">HARGA</label>
                        <input type="number" id="harga" name="harga" value="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="nta">NTA</label>
                        <input type="number" id="nta" name="nta" value="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="diskon">DISKON</label>
                        <input type="number" id="diskon" name="diskon" value="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="komisi">KOMISI</label>
                        <input type="number" id="komisi" name="komisi" value="0" step="0.01" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_piutang">NAMA PIUTANG</label>
                        <input type="text" id="nama_piutang" name="nama_piutang" class="text-uppercase">
                    </div>
                    <div class="form-group">
                        <label for="pembayaran">PEMBAYARAN</label>
                        <select id="pembayaran" name="pembayaran" class="text-uppercase" required>
                            <option value="">Pilih Pembayaran</option>
                            <option value="CASH">CASH</option>
                            <option value="PIUTANG">PIUTANG</option>
                            <option value="FLIGHT CANCEL">FLIGHT CANCEL</option>
                            <option value="REFUND">REFUND</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="BTN">BTN</option>
                            <option value="MANDIRI">MANDIRI</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl_realisasi">TGL REALISASI</label>
                        <input type="date" id="tgl_realisasi" name="tgl_realisasi">
                    </div>
                    <div class="form-group">
                        <label for="jam_realisasi">JAM REALISASI</label>
                        <input type="time" id="jam_realisasi" name="jam_realisasi">
                    </div>
                    <div class="form-group">
                        <label for="nilai_refund">NILAI REFUND</label>
                        <input type="number" id="nilai_refund" name="nilai_refund" value="0" step="0.01">
                    </div>
                </div>

                {{-- Kolom Keterangan --}}
                <div>
                    <div class="form-group">
                        <label for="keterangan">KETERANGAN</label>
                        <textarea id="keterangan" name="keterangan" class="text-uppercase"></textarea>
                    </div>

                    <div class="button-group">
                        <button type="reset" class="btn-merah">Hapus</button>
                        <button type="submit" id="btnInputData" class="btn-hijau">Input Data</button>
                        <button type="button" class="btn-oranye">Tutup Kas</button>
                        <button type="button" class="btn-hijau" id="btnCari">Cari</button>
                        <button type="button" class="btn-merah" id="btnCetakInvoice">Cetak Invoice</button>
                        <button type="button" class="btn-hijau" id="btnBatal">Batal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Tabel Data Tiket -->
<div class="table-card">
    <h3>Data Tiket</h3>
    <table id="tiketTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Issued</th>
                <th>Jam</th>
                <th>Kode Booking</th>
                <th>Airlines</th>
                <th>Nama</th>
                <th>Rute 1</th>
                <th>Tgl Flight 1</th>
                <th>Rute 2</th>
                <th>Tgl Flight 2</th>
                <th>Harga</th>
                <th>NTA</th>
                <th>Diskon</th>
                <th>Komisi</th>
                <th>Pembayaran</th>
                <th>Nama Piutang</th>
                <th>Tanggal Realisasi</th>
                <th>Jam Realisasi</th>
                <th>Nilai Refund</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tikets as $index => $t)
            <tr data-id="{{ $t->id }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $t->tgl_issued }}</td>
                <td>{{ $t->jam_input ?? '-' }}</td>
                <td>{{ $t->kode_booking }}</td>
                <td>{{ $t->airlines }}</td>
                <td>{{ $t->nama }}</td>
                <td>{{ $t->rute1 }}</td>
                <td>{{ $t->tgl_flight1 }}</td>
                <td>{{ $t->rute2 }}</td>
                <td>{{ $t->tgl_flight2 }}</td>
                <td>{{ number_format($t->harga, 0, ',', '.') }}</td>
                <td>{{ number_format($t->nta, 0, ',', '.') }}</td>
                <td>{{ number_format($t->diskon, 0, ',', '.') }}</td>
                <td>{{ number_format($t->komisi, 0, ',', '.') }}</td>
                <td>{{ $t->pembayaran }}</td>
                <td>{{ $t->nama_piutang }}</td>
                <td>{{ $t->tgl_realisasi }}</td>
                <td>{{ $t->jam_realisasi }}</td>
                <td>{{ number_format($t->nilai_refund, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<link rel="stylesheet" href="{{ asset('css/input-data.css') }}">

<style>
.current-time {
    text-align: right;
    font-size: 13px;
    color: #00343f;
    margin-bottom: 5px;
}
.table-container {
    margin-top: 40px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
}
.table-container h3 {
    margin-bottom: 15px;
    color: #00343f;
    text-align: center;
}
#tiketTable {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
#tiketTable th, #tiketTable td {
    border: 1px solid #ddd;
    padding: 6px;
    text-align: center;
}
#tiketTable th {
    background-color: #00343f;
    color: white;
}
#tiketTable tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}
#tiketTable tr.selected {
    background-color: #cce5ff !important;
}
</style>

<script>
function updateDateTime() {
    const now = new Date();
    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID');
    const jam = now.toLocaleTimeString('id-ID', { hour12: false });
    document.getElementById('currentDateTime').textContent = `${hari}, ${tanggal} | ${jam}`;
    document.getElementById('jam_input').value = jam;
}
setInterval(updateDateTime, 1000);
updateDateTime();

// Pilih baris tabel
document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
    row.addEventListener('click', function() {
        document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('tiketId').value = this.dataset.id;
    });
});

// Cetak invoice
document.getElementById('btnCetakInvoice').addEventListener('click', function() {
    const id = document.getElementById('tiketId').value;
    if (!id) {
        alert('Silakan pilih data tiket terlebih dahulu sebelum mencetak invoice!');
        return;
    }
    if (confirm('Apakah Anda ingin mencetak invoice untuk tiket ini?')) {
        window.location.href = `/invoice/${id}`;
    }
});

// Tombol Batal
document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('inputDataForm').reset();
    document.getElementById('tiketId').value = '';
    document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
});
</script>

</x-layouts.app>
