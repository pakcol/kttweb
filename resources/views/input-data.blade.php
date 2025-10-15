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
                        <button type="button" class="btn-merah" id="btnHapus">Hapus</button>
                        <button type="submit" id="btnInputData" class="btn-hijau">Input Data</button>
                        <button type="button" class="btn-oranye" onclick="window.location.href='{{ route('tutupKas') }}'">Tutup Kas</button>
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
                <th>Pilih</th>
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
                <td><input type="checkbox" class="chkInvoice" value="{{ $t->id }}"></td>
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

<div id="modalCari" class="modal-cari">
    <div class="modal-content">
        <p>Masukan <b>KODE BOOKING</b> atau <b>NAMA</b> :</p>
        <input type="text" id="searchInput" placeholder=" Search">
        <div class="modal-buttons">
            <button id="btnCariOk" class="btn-ok">OK</button>
            <button id="btnCariCancel" class="btn-cancel">CANCEL</button>
        </div>
    </div>
</div>


<link rel="stylesheet" href="{{ asset('css/input-data.css') }}">

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

// Klik satu kali isi form & update tombol
let selectedRow = null;
document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
    row.addEventListener('click', function() {
        document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
        this.classList.add('selected');
        selectedRow = this;

        const data = Array.from(this.children).map(td => td.innerText);
        document.getElementById('tiketId').value = this.dataset.id;
        document.getElementById('tgl_issued').value = data[2];
        document.getElementById('kode_booking').value = data[4];
        document.getElementById('airlines').value = data[5];
        document.getElementById('nama').value = data[6];
        document.getElementById('rute1').value = data[7];
        document.getElementById('tgl_flight1').value = data[8];
        document.getElementById('rute2').value = data[9];
        document.getElementById('tgl_flight2').value = data[10];
        document.getElementById('harga').value = parseFloat(data[11].replace(/\./g,''));
        document.getElementById('nta').value = parseFloat(data[12].replace(/\./g,''));
        document.getElementById('diskon').value = parseFloat(data[13].replace(/\./g,''));
        document.getElementById('pembayaran').value = data[15];
        document.getElementById('nama_piutang').value = data[16];
        document.getElementById('tgl_realisasi').value = data[17];
        document.getElementById('jam_realisasi').value = data[18];
        document.getElementById('nilai_refund').value = parseFloat(data[19].replace(/\./g,''));
        document.getElementById('keterangan').value = data[20];

        document.getElementById('btnInputData').textContent = 'UPDATE';
    });

    // Hapus klik 2x
    row.addEventListener('dblclick', function() {
        const id = this.dataset.id;
        if(confirm('Apakah yakin ingin menghapus data ini?')) {
            window.location.href = `/input-data/destroy/${id}`;
        }
    });
});

document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('inputDataForm').reset();
    document.getElementById('tiketId').value = '';
    document.getElementById('btnInputData').textContent = 'INPUT DATA';
    document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
});

document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.ctrlKey || e.metaKey) {
            this.classList.toggle('selected');
        } else { 
            document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
            this.classList.add('selected');
        }
        const selectedIds = Array.from(document.querySelectorAll('#tiketTable tbody tr.selected'))
            .map(r => r.dataset.id);
        document.getElementById('tiketId').value = selectedIds.join(',');
    });
});

// Cetak invoice multi-tiket
document.getElementById('btnCetakInvoice').addEventListener('click', function() {
    const ids = document.getElementById('tiketId').value;
    if (!ids) {
        alert('Silakan pilih minimal satu tiket untuk cetak invoice!');
        return;
    }
    if (confirm('Apakah Anda ingin mencetak invoice untuk tiket yang dipilih?')) {
        window.open(`/invoice-multi?ids=${ids}`, '_blank');
    }
});

const modalCari = document.getElementById('modalCari');
const btnCari = document.getElementById('btnCari');
const btnCariOk = document.getElementById('btnCariOk');
const btnCariCancel = document.getElementById('btnCariCancel');
const searchInput = document.getElementById('searchInput');
const table = document.getElementById('tiketTable');

// buka modal
btnCari.addEventListener('click', () => {
    modalCari.style.display = 'flex';
    searchInput.focus();
});

// tombol cancel
btnCariCancel.addEventListener('click', () => {
    modalCari.style.display = 'none';
    searchInput.value = '';
    document.querySelectorAll('#tiketTable tbody tr').forEach(r => r.style.display = '');
});

// tombol OK
btnCariOk.addEventListener('click', () => {
    const keyword = searchInput.value.trim().toLowerCase();
    if (!keyword) {
        alert('Masukkan KODE BOOKING atau NAMA terlebih dahulu!');
        return;
    }

    let found = false;
    document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
        const kode = row.children[4].innerText.toLowerCase();
        const nama = row.children[6].innerText.toLowerCase();

        if (kode.includes(keyword) || nama.includes(keyword)) {
            row.style.display = '';
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');
            isiFormDariRow(row);
            found = true;
        } else {
            row.style.display = 'none';
        }
    });

    if (!found) {
        alert('Data tidak ditemukan.');
    } else {
        modalCari.style.display = 'none';
        searchInput.value = '';
    }
});

function isiFormDariRow(row) {
    const data = Array.from(row.children).map(td => td.innerText);
    document.getElementById('tiketId').value = row.dataset.id;
    document.getElementById('tgl_issued').value = data[2];
    document.getElementById('kode_booking').value = data[4];
    document.getElementById('airlines').value = data[5];
    document.getElementById('nama').value = data[6];
    document.getElementById('rute1').value = data[7];
    document.getElementById('tgl_flight1').value = data[8];
    document.getElementById('rute2').value = data[9];
    document.getElementById('tgl_flight2').value = data[10];
    document.getElementById('harga').value = parseFloat(data[11].replace(/\./g,'')) || 0;
    document.getElementById('nta').value = parseFloat(data[12].replace(/\./g,'')) || 0;
    document.getElementById('diskon').value = parseFloat(data[13].replace(/\./g,'')) || 0;
    document.getElementById('pembayaran').value = data[15];
    document.getElementById('nama_piutang').value = data[16];
    document.getElementById('tgl_realisasi').value = data[17];
    document.getElementById('jam_realisasi').value = data[18];
    document.getElementById('nilai_refund').value = parseFloat(data[19].replace(/\./g,'')) || 0;
    document.getElementById('keterangan').value = data[20];
    document.getElementById('btnInputData').textContent = 'UPDATE';
}
</script>

</x-layouts.app>
