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
            <tr data-id="{{ $t->kode_booking }}"
                data-jenis-tiket-id="{{ $t->jenis_tiket_id }}">
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
/* ===================== UTIL ===================== */
const $ = id => document.getElementById(id);

    /* ===================== TOGGLE JENIS BAYAR ===================== */
    function toggleJenisPembayaran() {
        const jenis = $('jenis_bayar_id').value;
        $('bankContainer').style.display = jenis === '1' ? 'block' : 'none';
        $('namaPiutangContainer').style.display = jenis === '3' ? 'block' : 'none';

        $('bank_id').required = jenis === '1';
        $('nama_piutang').required = jenis === '3';
    }

    $('jenis_bayar_id').addEventListener('change', toggleJenisPembayaran);

    /* ===================== LOAD NOTA ===================== */
    function loadTiketDetail(kodeBooking) {
        fetch(`/nota/by-tiket/${kodeBooking}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(d => {
            $('jenis_bayar_id').value = d.jenis_bayar_id ?? '';
            $('bank_id').value = d.bank_id ?? '';
            toggleJenisPembayaran();
        })
        .catch(err => console.error('Nota error:', err));
    }

    /* ===================== FILL FORM ===================== */
    function fillFormFromRow(row) {
        const td = row.children;

        $('kode_booking').value = row.dataset.id;
        $('name').value = td[4].innerText;
        $('rute').value = td[5].innerText;
        $('rute2').value = td[7].innerText !== '-' ? td[7].innerText : '';
        $('harga_jual').value = td[9].innerText.replace(/\./g, '');
        $('nta').value = td[10].innerText.replace(/\./g, '');
        $('diskon').value = td[11].innerText.replace(/\./g, '');
        $('status').value = td[12].innerText.toLowerCase();
        $('keterangan').value = td[14].innerText !== '-' ? td[14].innerText : '';

        // tanggal
        $('tgl_issued').value = `${td[2].innerText}T00:00`;
        $('tgl_flight').value = `${td[6].innerText}T00:00`;
        $('tgl_flight2').value = td[8].innerText !== '-' ? `${td[8].innerText}T00:00` : '';

        $('jenis_tiket_id').value = row.dataset.jenisTiketId;

        // AJAX
        loadTiketDetail(row.dataset.id);

        $('btnInputData').textContent = 'UPDATE';
    }

    /* ===================== ROW CLICK ===================== */
    document.querySelectorAll('#tiketTable tbody tr').forEach(row => {
        row.addEventListener('click', e => {
            if (e.target.classList.contains('btn-delete')) return;

            document.querySelectorAll('#tiketTable tr').forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');

            fillFormFromRow(row);
        });
    });

    /* ===================== DELETE ===================== */
    let deleteId = null;

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = e => {
            e.stopPropagation();
            deleteId = btn.dataset.id;
            $('modalDelete').style.display = 'flex';
        };
    });

    $('btnNoDelete').onclick = () => {
        deleteId = null;
        $('modalDelete').style.display = 'none';
    };

    $('btnYesDelete').onclick = () => {
        if (!deleteId) return;

        fetch(`/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                document.querySelector(`tr[data-id="${deleteId}"]`)?.remove();
            }
            alert(d.message);
            $('modalDelete').style.display = 'none';
            deleteId = null;
        });
    };

    /* ===================== INVOICE ===================== */
    //Cetak Invoice
    document.getElementById('btnCetakInvoice').addEventListener('click', () => {
        const selectedRow = document.querySelector('#tiketTable tr.selected');

        if (!selectedRow) {
            alert('Pilih tiket terlebih dahulu');
            return;
        }

        const kodeBooking = selectedRow.dataset.id;
        window.open(`/invoice/${kodeBooking}`, '_blank');
    });

    /* ===================== RESET ===================== */
    $('btnBatal').onclick = () => {
        $('inputDataForm').reset();
        $('btnInputData').textContent = 'INPUT DATA';
        toggleJenisPembayaran();
    };
</script>

</x-layouts.app>