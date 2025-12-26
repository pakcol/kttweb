<x-layouts.app title="Input Tiket - PT. Kupang Tour & Travel">

<section class="input-data-section">
    <div class="form-container">
        <h2>INPUT TIKET</h2>
        <div class="current-time" id="currentDateTime"></div>

        <form action="{{ route('input-tiket.store') }}" id="inputDataForm" method="POST">
            @csrf
            <div class="input-grid">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="form-group">
                        <label for="tgl_issued">TGL ISSUED*</label>
                        <input type="datetime-local" id="tgl_issued" name="tgl_issued" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_booking">KODE BOOKING*</label>
                        <input type="text" id="kode_booking" name="kode_booking" class="text-uppercase" required maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="name">NAMA*</label>
                        <input type="text" id="name" name="name" class="text-uppercase" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="rute">RUTE*</label>
                        <input type="text" id="rute" name="rute" class="text-uppercase" required maxlength="45">
                    </div>
                    <div class="form-group">
                        <label for="tgl_flight">TGL FLIGHT*</label>
                        <input type="datetime-local" id="tgl_flight" name="tgl_flight" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rute2">RUTE 2</label>
                        <input type="text" id="rute2" name="rute2" class="text-uppercase" maxlength="45">
                    </div>
                    <div class="form-group">
                        <label for="tgl_flight2">TGL FLIGHT 2</label>
                        <input type="datetime-local" id="tgl_flight2" name="tgl_flight2">
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="form-group">
                        <label for="harga_jual">HARGA JUAL*</label>
                        <input type="number" id="harga_jual" name="harga_jual" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="nta">NTA*</label>
                        <input type="number" id="nta" name="nta" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="diskon">DISKON</label>
                        <input type="number" id="diskon" name="diskon" value="0">
                    </div>
                    <div class="form-group">
                        <label for="status">STATUS*</label>
                        <select id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="pending">Pending</option>
                            <option value="issued">Issued</option>
                            <option value="canceled">Canceled</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_tiket_id">JENIS TIKET*</label>
                        <select id="jenis_tiket_id" name="jenis_tiket_id" class="text-uppercase" required>
                            <option value="">-- Pilih Jenis Tiket --</option>
                            @if(isset($jenisTiket) && $jenisTiket->count() > 0)
                                @foreach($jenisTiket as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->name_jenis }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Data jenis tiket tidak ditemukan</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">KETERANGAN</label>
                        <textarea id="keterangan" name="keterangan" class="text-uppercase" maxlength="200"></textarea>
                    </div>
                </div>

                {{-- Kolom Tambahan untuk Fitur Lama (opsional) --}}
                <div>
                    <div class="form-group">
                        <label for="jenis_bayar_id">JENIS PEMBAYARAN*</label>
                        <select id="jenis_bayar_id" name="jenis_bayar_id" class="text-uppercase" required>
                            <option value="">-- Pilih Jenis Pembayaran --</option>
                            @if(isset($jenisBayar) && $jenisBayar->count() > 0)
                                @foreach($jenisBayar as $jenis)
                                    <option value="{{ $jenis->id }}">
                                        {{ $jenis->jenis }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Data jenis pembayaran tidak ditemukan</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group" id="bankContainer" style="display: none;">
                        <label for="bank_id">BANK</label>
                        <select id="bank_id" name="bank_id" class="text-uppercase">
                            <option value="">-- Pilih Bank --</option>
                            @if(isset($bank) && $bank->count() > 0)
                                @foreach($bank as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group" id="namaPiutangContainer" style="display: none;">
                        <label for="nama_piutang">NAMA PIUTANG</label>
                        <input type="text" id="nama_piutang" name="nama_piutang" class="text-uppercase">
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
                        <input type="number" id="nilai_refund" name="nilai_refund" value="0">
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
                <th>Kode Booking</th>
                <th>Nama</th>
                <th>Rute</th>
                <th>Tgl Flight</th>
                <th>Rute 2</th>
                <th>Tgl Flight 2</th>
                <th>Harga Jual</th>
                <th>NTA</th>
                <th>Diskon</th>
                <th>Status</th>
                <th>Jenis Tiket</th>
                <th>Keterangan</th>
                <th style="text-align:center;">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket as $index => $t)
            <tr data-id="{{ $t->kode_booking }}">
                <td><input type="checkbox" class="check-row" value="{{ $t->kode_booking }}"></td>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tgl_issued)->format('Y-m-d') }}</td>
                <td>{{ $t->kode_booking }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->rute }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tgl_flight)->format('Y-m-d') }}</td>
                <td>{{ $t->rute2 ?? '-' }}</td>
                <td>{{ $t->tgl_flight2 ? \Carbon\Carbon::parse($t->tgl_flight2)->format('Y-m-d') : '-' }}</td>
                <td>{{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                <td>{{ number_format($t->nta, 0, ',', '.') }}</td>
                <td>{{ number_format($t->diskon, 0, ',', '.') }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>{{ $t->jenisTiket->name_jenis ?? '-' }}</td>
                <td>{{ $t->keterangan ?? '-' }}</td>
                <td style="text-align:center;">
                    <button class="btn-delete" data-id="{{ $t->kode_booking }}">Delete</button>
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

.input-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="datetime-local"],
.form-group input[type="date"],
.form-group input[type="time"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group input[type="datetime-local"]:focus,
.form-group input[type="date"]:focus,
.form-group input[type="time"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.text-uppercase {
    text-transform: uppercase;
}

.button-group {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-hijau, .btn-oranye, .btn-merah {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

.btn-hijau {
    background-color: #27ae60;
    color: white;
}

.btn-hijau:hover {
    background-color: #219653;
}

.btn-oranye {
    background-color: #f39c12;
    color: white;
}

.btn-oranye:hover {
    background-color: #e67e22;
}

.btn-merah {
    background-color: #e74c3c;
    color: white;
}

.btn-merah:hover {
    background-color: #c0392b;
}

/* Modal Styling */
.modal-cari {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    min-width: 300px;
    text-align: center;
}

.modal-content p {
    margin-bottom: 20px;
    font-size: 16px;
}

.modal-content input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-ok, .btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.btn-ok {
    background-color: #27ae60;
    color: white;
}

.btn-cancel {
    background-color: #95a5a6;
    color: white;
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

    // ====================== TOGGLE JENIS PEMBAYARAN ======================
    function toggleJenisPembayaran() {
        const jenisBayarSelect = document.getElementById('jenis_bayar_id');
        const bankContainer = document.getElementById('bankContainer');
        const namaPiutangContainer = document.getElementById('namaPiutangContainer');
        
        if (!jenisBayarSelect.value) {
            bankContainer.style.display = 'none';
            namaPiutangContainer.style.display = 'none';
            return;
        }
        
        // Tampilkan bank jika kategori adalah 'bank'
        if (jenisBayarSelect.value === '1') {
            bankContainer.style.display = 'block';
            document.getElementById('bank_id').required = true;
            namaPiutangContainer.style.display = 'none';
            document.getElementById('nama_piutang').required = false;
        } 
        // Tampilkan nama piutang jika kategori adalah 'piutang'
        else if (jenisBayarSelect.value === '3') {
            bankContainer.style.display = 'none';
            document.getElementById('bank_id').required = false;
            namaPiutangContainer.style.display = 'block';
            document.getElementById('nama_piutang').required = true;
        } 
        // Sembunyikan keduanya untuk kategori lain
        else {
            bankContainer.style.display = 'none';
            document.getElementById('bank_id').required = false;
            namaPiutangContainer.style.display = 'none';
            document.getElementById('nama_piutang').required = false;
        }
    }

    document.getElementById('jenis_bayar_id').addEventListener('change', toggleJenisPembayaran);

    // Panggil fungsi saat pertama kali load
    toggleJenisPembayaran();

    // ====================== FUNGSI UNTUK MENGAMBIL DATA JENIS BAYAR SAAT EDIT ======================
    function loadTiketDetail(kodeBooking) {
        fetch(`/nota/by-tiket/${kodeBooking}`)
            .then(response => response.json())
            .then(data => {

                // JENIS BAYAR
                if (data.jenis_bayar_id) {
                    document.getElementById('jenis_bayar_id').value = data.jenis_bayar_id;
                    toggleJenisPembayaran();
                } else {
                    document.getElementById('jenis_bayar_id').value = '';
                }

                // BANK
                if (data.bank_id) {
                    document.getElementById('bank_id').value = data.bank_id;
                } else {
                    document.getElementById('bank_id').value = '';
                }

                // PIUTANG
                document.getElementById('nama_piutang').value = data.nama_piutang ?? '';

                // REALISASI
                document.getElementById('tgl_realisasi').value = data.tgl_realisasi ?? '';
                document.getElementById('jam_realisasi').value = data.jam_realisasi ?? '';

                // REFUND
                document.getElementById('nilai_refund').value = data.nilai_refund ?? 0;
            })
            .catch(err => console.error(err));
    }


    // Modifikasi fungsi isiFormDariRow untuk menghapus bagian pembayaran lama
    function isiFormDariRow(row) {
        const data = Array.from(row.children).map(td => td.innerText);

        document.getElementById('tiketId').value = row.dataset.id;
        
        // Format datetime untuk tgl_issued
        const tglIssued = data[2];
        const tglIssuedTime = '00:00';
        document.getElementById('tgl_issued').value = `${tglIssued}T${tglIssuedTime}`;
        
        document.getElementById('kode_booking').value = data[3];
        document.getElementById('name').value = data[4];
        document.getElementById('rute').value = data[5];
        
        // Format datetime untuk tgl_flight
        const tglFlight = data[6];
        document.getElementById('tgl_flight').value = `${tglFlight}T${tglIssuedTime}`;
        
        document.getElementById('rute2').value = data[7] !== '-' ? data[7] : '';
        
        if (data[8] !== '-') {
            document.getElementById('tgl_flight2').value = `${data[8]}T${tglIssuedTime}`;
        } else {
            document.getElementById('tgl_flight2').value = '';
        }

        document.getElementById('harga_jual').value = data[9].replace(/\./g, '');
        document.getElementById('nta').value = data[10].replace(/\./g, '');
        document.getElementById('diskon').value = data[11].replace(/\./g, '');
        document.getElementById('status').value = data[12].toLowerCase();
        document.getElementById('keterangan').value = data[14] !== '-' ? data[14] : '';

        // Load detail untuk jenis tiket dan pembayaran
        loadTiketDetail(row.dataset.id);

        document.getElementById('btnInputData').textContent = 'UPDATE';
    }

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


let selectedRow = null;
document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) return;

        document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
        this.classList.add('selected');

        const data = Array.from(this.children).map(td => td.innerText);

        document.getElementById('tiketId').value = this.dataset.id;
        
        // Format datetime untuk tgl_issued
        const tglIssued = data[2];
        const tglIssuedTime = '00:00'; // Default time
        document.getElementById('tgl_issued').value = `${tglIssued}T${tglIssuedTime}`;
        
        document.getElementById('kode_booking').value = data[3];
        document.getElementById('name').value = data[4];
        document.getElementById('rute').value = data[5];
        
        // Format datetime untuk tgl_flight
        const tglFlight = data[6];
        document.getElementById('tgl_flight').value = `${tglFlight}T${tglIssuedTime}`;
        
        document.getElementById('rute2').value = data[7] !== '-' ? data[7] : '';
        
        if (data[8] !== '-') {
            document.getElementById('tgl_flight2').value = `${data[8]}T${tglIssuedTime}`;
        } else {
            document.getElementById('tgl_flight2').value = '';
        }

        document.getElementById('harga_jual').value = data[9].replace(/\./g, '');
        document.getElementById('nta').value = data[10].replace(/\./g, '');
        document.getElementById('diskon').value = data[11].replace(/\./g, '');
        document.getElementById('status').value = data[12].toLowerCase();

        // Untuk jenis tiket, kita perlu mengambil dari data atau melalui AJAX
        // Untuk sekarang kita set default atau kosong
        document.getElementById('keterangan').value = data[14] !== '-' ? data[14] : '';

        // Load detail tiket via AJAX untuk mendapatkan jenis_tiket_id
        loadTiketDetail(this.dataset.id);

        document.getElementById('btnInputData').textContent = 'UPDATE';
    });
});

document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('inputDataForm').reset();
    document.getElementById('tiketId').value = '';
    document.getElementById('tgl_issued').value = '{{ date("Y-m-d\TH:i") }}';
    document.getElementById('tgl_flight').value = '{{ date("Y-m-d\TH:i") }}';
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
        const kode = row.children[3].innerText.toLowerCase();
        const nama = row.children[4].innerText.toLowerCase();

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

    fetch(`/input-tiket/${deleteId}`, {
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
    
    // Format datetime untuk tgl_issued
    const tglIssued = data[2];
    const tglIssuedTime = '00:00';
    document.getElementById('tgl_issued').value = `${tglIssued}T${tglIssuedTime}`;
    
    document.getElementById('kode_booking').value = data[3];
    document.getElementById('name').value = data[4];
    document.getElementById('rute').value = data[5];
    
    // Format datetime untuk tgl_flight
    const tglFlight = data[6];
    document.getElementById('tgl_flight').value = `${tglFlight}T${tglIssuedTime}`;
    
    document.getElementById('rute2').value = data[7] !== '-' ? data[7] : '';
    
    if (data[8] !== '-') {
        document.getElementById('tgl_flight2').value = `${data[8]}T${tglIssuedTime}`;
    } else {
        document.getElementById('tgl_flight2').value = '';
    }

    document.getElementById('harga_jual').value = data[9].replace(/\./g, '');
    document.getElementById('nta').value = data[10].replace(/\./g, '');
    document.getElementById('diskon').value = data[11].replace(/\./g, '');
    document.getElementById('status').value = data[12].toLowerCase();
    document.getElementById('keterangan').value = data[14] !== '-' ? data[14] : '';

    // Load detail untuk jenis tiket
    loadTiketDetail(row.dataset.id);

    toggleNamaPiutang();
    document.getElementById('btnInputData').textContent = 'UPDATE';
}

// Fungsi untuk mengambil data lengkap tiket via AJAX
function loadTiketDetail(kodeBooking) {
    fetch(`/input-tiket/${kodeBooking}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Set nilai jenis tiket jika ada
            if (data.jenis_tiket_id) {
                document.getElementById('jenis_tiket_id').value = data.jenis_tiket_id;
            }
            
            // Set nilai fields tambahan jika ada
            if (data.pembayaran) {
                document.getElementById('pembayaran').value = data.pembayaran;
                toggleNamaPiutang();
            }
            
            if (data.namaPiutang) {
                document.getElementById('namaPiutang').value = data.namaPiutang;
            }
            
            if (data.tglRealisasi) {
                document.getElementById('tglRealisasi').value = data.tglRealisasi;
            }
            
            if (data.jamRealisasi) {
                document.getElementById('jamRealisasi').value = data.jamRealisasi;
            }
            
            if (data.nilaiRefund) {
                document.getElementById('nilaiRefund').value = data.nilaiRefund;
            }
        })
        .catch(error => {
            console.error('Error loading tiket detail:', error);
        });
}
</script>
</x-layouts.app>