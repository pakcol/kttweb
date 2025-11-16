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
                        <label for="tglIssued">TGL ISSUED*</label>
                        <input type="date" id="tglIssued" name="tglIssued" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kodeBooking">KODE BOOKING*</label>
                        <input type="text" id="kodeBooking" name="kodeBooking" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="airlines">AIRLINES*</label>
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
                        <label for="nama">NAMA*</label>
                        <input type="text" id="nama" name="nama" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="rute1">RUTE 1*</label>
                        <input type="text" id="rute1" name="rute1" class="text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="tglFlight1">TGL FLIGHT1*</label>
                        <input type="date" id="tglFlight1" name="tglFlight1" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rute2">RUTE 2</label>
                        <input type="text" id="rute2" name="rute2" class="text-uppercase">
                    </div>
                    <div class="form-group">
                        <label for="tglFlight2">TGL FLIGHT2</label>
                        <input type="date" id="tglFlight2" name="tglFlight2">
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="form-group">
                        <label for="harga">HARGA*</label>
                        <input type="number" id="harga" name="harga" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="nta">NTA*</label>
                        <input type="number" id="nta" name="nta" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="diskon">DISKON*</label>
                        <input type="number" id="diskon" name="diskon" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="komisi">KOMISI</label>
                        <input type="number" id="komisi" name="komisi" value="0" readonly>
                    </div>  
                    <div class="form-group">
                        <label for="pembayaran">PEMBAYARAN*</label>
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
                        <label for="namaPiutang">NAMA PIUTANG</label>
                        <input type="text" id="namaPiutang" name="namaPiutang" class="text-uppercase" disabled>
                    </div>
                    <div class="form-group">
                        <label for="tglRealisasi">TGL REALISASI</label>
                        <input type="date" id="tglRealisasi" name="tglRealisasi">
                    </div>
                    <div class="form-group">
                        <label for="jamRealisasi">JAM REALISASI</label>
                        <input type="time" id="jamRealisasi" name="jamRealisasi">
                    </div>
                    <div class="form-group">
                        <label for="nilaiRefund">NILAI REFUND</label>
                        <input type="number" id="nilaiRefund" name="nilaiRefund" value="0">
                    </div>
                </div>

                {{-- Kolom Keterangan --}}
                <div>
                    <div class="form-group">
                        <label for="keterangan">KETERANGAN</label>
                        <textarea id="keterangan" name="keterangan" class="text-uppercase"></textarea>
                    </div>

                    <div class="button-group">
                        <button type="submit" id="btnInputData" class="btn-hijau">Input Data</button>
                        <button type="button" class="btn-oranye" onclick="window.location.href='/rekapan-penjualan'">Tutup Kas</button>
                        <button type="button" class="btn-hijau" id="btnCari">Cari</button>
                        <button type="button" class="btn-merah" id="btnCetakInvoice">Cetak Invoice</button>
                        <button type="button" class="btn-hijau" id="btnBatal">Batal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<div class="table-card">
    <h3>Data Tiket</h3>
    <table id="tiketTable">
<thead>
    <tr>
        <th><input type="checkbox" id="checkAll"></th>
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
        <th style="text-align:center;">Delete</th>
    </tr>
</thead>
<tbody>
    @foreach ($ticket as $index => $t)
    <tr data-id="{{ $t->id }}">
        <td><input type="checkbox" class="check-row" value="{{ $t->id }}"></td>
        <td>{{ $index + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($t->tglIssued)->format('Y-m-d') }}</td>
        <td>{{ $t->jam ? \Carbon\Carbon::parse($t->jam)->format('H:i:s') : '' }}</td>
        <td>{{ $t->kodeBooking }}</td>
        <td>{{ $t->airlines }}</td>
        <td>{{ $t->nama }}</td>
        <td>{{ $t->rute1 }}</td>
         <td>{{ \Carbon\Carbon::parse($t->tglFlight1)->format('Y-m-d') }}</td>
                <td>{{ $t->rute2 }}</td>
                <td>{{ $t->tglFlight2 ? \Carbon\Carbon::parse($t->tglFlight2)->format('Y-m-d') : '' }}</td>

                <td>{{ number_format($t->harga, 0, ',', '.') }}</td>
                <td>{{ number_format($t->nta, 0, ',', '.') }}</td>
                <td>{{ number_format($t->diskon, 0, ',', '.') }}</td>
                <td>{{ number_format($t->komisi, 0, ',', '.') }}</td>
                <td>{{ $t->pembayaran }}</td>
                <td>{{ $t->namaPiutang }}</td>
                <td>{{ $t->tglRealisasi }}</td>
                <td>{{ $t->jamRealisasi }}</td>
                <td>{{ number_format($t->nilaiRefund, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan }}</td>
                <td style="text-align:center;">
                    <button class="btn-delete" data-id="{{ $t->id }}">Delete</button>
                </td>
    </tr>
    @endforeach
</tbody>
</table>

</div>

<style>
.btn-delete {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-delete:hover {
    background-color: #c0392b;
}
</style>

<div id="modalDelete" class="modal-cari">
    <div class="modal-content">
        <p>Are you sure want to <br><b>Delete this Line?</b></p>
        <div class="modal-buttons">
            <button id="btnYesDelete" class="btn-ok">YES</button>
            <button id="btnNoDelete" class="btn-cancel">NO</button>
        </div>
    </div>
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
// ====================== JAM REALTIME ===========================
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


function unformatNumber(str) {
    return parseInt(String(str).replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
}
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}


function hitungKomisi() {
    const harga = unformatNumber(document.getElementById('harga').value);
    const nta = unformatNumber(document.getElementById('nta').value);

    const diskonPercent = parseFloat(document.getElementById('diskon').value) || 0;

    let komisiDasar = harga - nta;
    if (komisiDasar < 0) komisiDasar = 0;

    const diskonRupiah = Math.round(komisiDasar * (diskonPercent / 100));
    let komisi = komisiDasar - diskonRupiah;
    if (komisi < 0) komisi = 0;

    document.getElementById('komisi').value = formatNumber(komisi);
}



document.getElementById('harga').addEventListener('input', hitungKomisi);
document.getElementById('nta').addEventListener('input', hitungKomisi);
document.getElementById('diskon').addEventListener('input', hitungKomisi);
document.getElementById('inputDataForm').addEventListener('submit', function () {
    const harga = unformatNumber(document.getElementById('harga').value);
    const nta = unformatNumber(document.getElementById('nta').value);
    const diskonPercent = parseFloat(document.getElementById('diskon').value) || 0;

    let komisiDasar = harga - nta;
    if (komisiDasar < 0) komisiDasar = 0;

    const diskonRupiah = Math.round(komisiDasar * (diskonPercent / 100));
    document.getElementById('diskon').value = diskonRupiah;

    const finalKomisi = komisiDasar - diskonRupiah;
    document.getElementById('komisi').value = finalKomisi;
});


function toggleNamaPiutang() {
    const pembayaran = document.getElementById('pembayaran').value;
    const namaPiutang = document.getElementById('namaPiutang');

    if (pembayaran === 'PIUTANG') {
        namaPiutang.disabled = false;
        namaPiutang.required = true;
    } else {
        namaPiutang.disabled = true;
        namaPiutang.required = false;
        namaPiutang.value = '';
    }
}
document.getElementById('pembayaran').addEventListener('change', toggleNamaPiutang);
toggleNamaPiutang();


let selectedRow = null;
document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) return;

        document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
        this.classList.add('selected');

        const data = Array.from(this.children).map(td => td.innerText);

        document.getElementById('tiketId').value = this.dataset.id;
        document.getElementById('tglIssued').value = data[2];
        document.getElementById('kodeBooking').value = data[4];
        document.getElementById('airlines').value = data[5];
        document.getElementById('nama').value = data[6];
        document.getElementById('rute1').value = data[7];
        document.getElementById('tglFlight1').value = data[8];
        document.getElementById('rute2').value = data[9];
        document.getElementById('tglFlight2').value = data[10];

        document.getElementById('harga').value = data[11].replace(/\./g, '');
        document.getElementById('nta').value = data[12].replace(/\./g, '');

        const komisiDasar = unformatNumber(data[11]) - unformatNumber(data[12]);
        const diskonRupiah = unformatNumber(data[13]);
        const diskonPercent = komisiDasar > 0 ? Math.round((diskonRupiah / komisiDasar) * 100) : 0;

        document.getElementById('diskon').value = diskonPercent;
        document.getElementById('komisi').value = data[14].replace(/\./g, '');

        document.getElementById('pembayaran').value = data[15];
        document.getElementById('namaPiutang').value = data[16];
        document.getElementById('tglRealisasi').value = data[17];
        document.getElementById('jamRealisasi').value = data[18];
        document.getElementById('nilaiRefund').value = data[19].replace(/\./g, '');
        document.getElementById('keterangan').value = data[20];

        toggleNamaPiutang();
        hitungKomisi();

        document.getElementById('btnInputData').textContent = 'UPDATE';
    });
});

document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('inputDataForm').reset();
    document.getElementById('tiketId').value = '';
    document.getElementById('btnInputData').textContent = 'INPUT DATA';
    document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
    toggleNamaPiutang();
});

document.getElementById('btnCetakInvoice').addEventListener('click', function() {
    const selected = Array.from(document.querySelectorAll('.check-row:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        alert('Silakan pilih minimal satu tiket untuk cetak invoice!');
        return;
    }

    if (confirm(`Cetak invoice untuk ${selected.length} tiket terpilih?`)) {
        window.open(`{{ route('invoice.multi') }}?ids=${selected.join(',')}`, '_blank');
    }       
});

const checkAll = document.getElementById('checkAll');
if (checkAll) {
    const checkboxes = document.querySelectorAll('.check-row');
    checkAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
}

const modalCari = document.getElementById('modalCari');
const btnCari = document.getElementById('btnCari');
const btnCariOk = document.getElementById('btnCariOk');
const btnCariCancel = document.getElementById('btnCariCancel');
const searchInput = document.getElementById('searchInput');

btnCari.addEventListener('click', () => {
    modalCari.style.display = 'flex';
    searchInput.focus();
});

btnCariCancel.addEventListener('click', () => {
    modalCari.style.display = 'none';
    searchInput.value = '';
    document.querySelectorAll('#tiketTable tbody tr').forEach(r => r.style.display = '');
});

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

let deleteId = null;
const modalDelete = document.getElementById('modalDelete');
const btnYesDelete = document.getElementById('btnYesDelete');
const btnNoDelete = document.getElementById('btnNoDelete');

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation(); 
        deleteId = this.dataset.id;
        modalDelete.style.display = 'flex';
    });
});

btnNoDelete.addEventListener('click', () => {
    deleteId = null;
    modalDelete.style.display = 'none';
});

btnYesDelete.addEventListener('click', () => {
    if (!deleteId) return;

    fetch(`/input-data/${deleteId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`#tiketTable tbody tr[data-id="${deleteId}"]`);
            if (row) row.remove();
            alert(data.message);
        } else {
            alert(data.message || 'Gagal menghapus data.');
        }
        deleteId = null;
        modalDelete.style.display = 'none';
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat menghapus data.');
        deleteId = null;
        modalDelete.style.display = 'none';
    });
});

function isiFormDariRow(row) {
    const data = Array.from(row.children).map(td => td.innerText);

    document.getElementById('tiketId').value = row.dataset.id;
    document.getElementById('tglIssued').value = data[2];
    document.getElementById('kodeBooking').value = data[4];
    document.getElementById('airlines').value = data[5];
    document.getElementById('nama').value = data[6];
    document.getElementById('rute1').value = data[7];
    document.getElementById('tglFlight1').value = data[8];
    document.getElementById('rute2').value = data[9];
    document.getElementById('tglFlight2').value = data[10];

    document.getElementById('harga').value = data[11].replace(/\./g, '');
    document.getElementById('nta').value = data[12].replace(/\./g, '');
    document.getElementById('diskon').value = data[13].replace(/\./g, '');
    document.getElementById('komisi').value = data[14].replace(/\./g, '');

    document.getElementById('pembayaran').value = data[15];
    document.getElementById('namaPiutang').value = data[16];
    document.getElementById('tglRealisasi').value = data[17];
    document.getElementById('jamRealisasi').value = data[18];
    document.getElementById('nilaiRefund').value = data[19].replace(/\./g, '');
    document.getElementById('keterangan').value = data[20];

    toggleNamaPiutang();
    document.getElementById('btnInputData').textContent = 'UPDATE';
}
</script>
</x-layouts.app>
