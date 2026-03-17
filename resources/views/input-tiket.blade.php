<x-layouts.app title="Input Tiket - PT. Kupang Tour & Travel">

@push('styles')
<style>
    main.container {
        padding-top: 90px !important;
        padding-bottom: 40px !important;
        max-width: 100% !important;
    }
    body {
        background: linear-gradient(90deg, #0f4d4d 0%, #b8d3d6 100%) !important;
        min-height: 100vh !important;
    }
</style>
@endpush

{{-- ===================== TOMBOL & TABEL DATA TIKET ===================== --}}
<section class="tiket-section">

    <div class="tiket-action-bar">
        <button type="button" class="btn-hijau" id="btnOpenModal">+ Input Tiket</button>
        <button type="button" class="btn-hijau" id="btnCari">Cari</button>
        <button type="button" class="btn-merah" id="btnCetakInvoice">Cetak Invoice</button>
        <a href="{{ route('cash-flow.cashFlow', ['from' => 'input-kas']) }}" class="btn-oranye btn-link">Tutup Kas</a>
    </div>

    {{-- TABEL 1: Cash & Bank --}}
    <div class="table-card">
        <h3>Data Tiket — Pembayaran Cash &amp; Bank</h3>
        <table id="tiketTableCashBank">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAllCashBank"></th>
                    <th>No</th><th>Tgl Issued</th><th>Kode Booking</th><th>Nama</th>
                    <th>Rute</th><th>Tgl Flight</th><th>Rute 2</th><th>Tgl Flight 2</th>
                    <th>Harga Jual</th><th>NTA</th><th>Diskon</th><th>Komisi</th>
                    <th>Status</th><th>Jenis Tiket</th><th>Pembayaran</th>
                    <th>Keterangan</th><th>Nilai Refund</th>
                    <th style="text-align:center;">Delete</th>
                </tr>
            </thead>
            <tbody>
                @php $indexCashBank = 1; @endphp
                @foreach ($ticket as $t)
                    @if($t->jenis_bayar_id != 3)
                    <tr data-id="{{ $t->kode_booking }}" data-jenis-tiket-id="{{ $t->jenis_tiket_id }}">
                        <td><input type="checkbox" class="check-row" value="{{ $t->kode_booking }}"></td>
                        <td>{{ $indexCashBank++ }}</td>
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
                        <td>{{ number_format($t->komisi, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($t->status) }}</td>
                        <td>{{ $t->jenisTiket->name_jenis ?? '-' }}</td>
                        <td>{{ $t->pembayaran_label }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td>{{ number_format($t->nilai_refund ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:center;">
                            <form action="{{ route('input-tiket.destroy', $t->kode_booking) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn-delete" type="submit" onclick="return confirm('Hapus tiket ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TABEL 2: Piutang --}}
    <div class="table-card" style="margin-top: 30px;">
        <h3>Data Tiket — Pembayaran Piutang</h3>
        <table id="tiketTablePiutang">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAllPiutang"></th>
                    <th>No</th><th>Tgl Issued</th><th>Kode Booking</th><th>Nama</th>
                    <th>Rute</th><th>Tgl Flight</th><th>Rute 2</th><th>Tgl Flight 2</th>
                    <th>Harga Jual</th><th>NTA</th><th>Diskon</th><th>Komisi</th>
                    <th>Status</th><th>Jenis Tiket</th><th>Nama Piutang</th>
                    <th>Keterangan</th><th>Nilai Refund</th>
                    <th style="text-align:center;">Delete</th>
                </tr>
            </thead>
            <tbody>
                @php $indexPiutang = 1; @endphp
                @foreach ($ticket as $t)
                    @if($t->jenis_bayar_id == 3)
                    <tr data-id="{{ $t->kode_booking }}" data-jenis-tiket-id="{{ $t->jenis_tiket_id }}">
                        <td><input type="checkbox" class="check-row" value="{{ $t->kode_booking }}"></td>
                        <td>{{ $indexPiutang++ }}</td>
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
                        <td>{{ number_format($t->komisi, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($t->status) }}</td>
                        <td>{{ $t->jenisTiket->name_jenis ?? '-' }}</td>
                        <td>{{ $t->mutasiTiket?->piutang?->nama ?? '-' }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td>{{ number_format($t->nilai_refund ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:center;">
                            <form action="{{ route('input-tiket.destroy', $t->kode_booking) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn-delete" type="submit" onclick="return confirm('Hapus tiket ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

</section>

{{-- ===================== MODAL INPUT TIKET ===================== --}}
<div id="modalInputTiket" class="modal-overlay" style="display:none;">
    <div class="modal-tiket-content">
        <div class="modal-tiket-header">
            <h2>INPUT TIKET</h2>
            <div class="current-time" id="currentDateTime"></div>
            <button type="button" class="btn-close-modal" id="btnCloseModal">&times;</button>
        </div>

        <form action="{{ route('input-tiket.store') }}" id="inputDataForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="statusCustomer">CUSTOMER TIKET</label>
                <select id="statusCustomer" name="statusCustomer" required>
                    <option value="customer">CUSTOMER</option>
                    <option value="subagent">SUBAGENT</option>
                </select>
            </div>
            <div class="form-group" id="subagentContainer" style="display:none;">
                <label>SUBAGENT</label>
                <select name="subagent_id" id="subagent_id">
                    @foreach($subagents as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-grid">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="form-group">
                        <label>TGL ISSUED*</label>
                        <input type="datetime-local" id="tgl_issued" name="tgl_issued" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label>KODE BOOKING*</label>
                        <input type="text" id="kode_booking" name="kode_booking" class="text-uppercase" required maxlength="10">
                    </div>
                    <div class="form-group">
                        <label>NAMA*</label>
                        <input type="text" id="name" name="name" class="text-uppercase" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>RUTE*</label>
                        <input type="text" id="rute" name="rute" class="text-uppercase" required maxlength="45">
                    </div>
                    <div class="form-group">
                        <label>TGL FLIGHT*</label>
                        <input type="datetime-local" id="tgl_flight" name="tgl_flight" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label>RUTE 2</label>
                        <input type="text" id="rute2" name="rute2" class="text-uppercase" maxlength="45">
                    </div>
                    <div class="form-group">
                        <label>TGL FLIGHT 2</label>
                        <input type="datetime-local" id="tgl_flight2" name="tgl_flight2">
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="form-group">
                        <label>HARGA JUAL*</label>
                        <input type="number" id="harga_jual" name="harga_jual" value="0" required>
                    </div>
                    <div class="form-group">
                        <label>NTA*</label>
                        <input type="number" id="nta" name="nta" value="0" required>
                    </div>
                    <div class="form-group">
                        <label>DISKON</label>
                        <input type="number" id="diskon" name="diskon" value="0">
                    </div>
                    <div class="form-group">
                        <label>KOMISI</label>
                        <input type="number" id="komisi" name="komisi" value="0" readonly>
                    </div>
                    <div class="form-group status-advanced">
                        <label>STATUS</label>
                        <select id="status" name="status" required>
                            <option value="issued" selected>Issued</option>
                            <option value="canceled">Canceled</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>JENIS TIKET*</label>
                        <select id="jenis_tiket_id" name="jenis_tiket_id" required>
                            <option value="">-- Pilih Jenis Tiket --</option>
                            @foreach($jenisTiket as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->name_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>KETERANGAN</label>
                        <textarea id="keterangan" name="keterangan" class="text-uppercase" maxlength="200"></textarea>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="form-group subagent-hide">
                        <label>JENIS PEMBAYARAN</label>
                        <select id="jenis_bayar_id" name="jenis_bayar_id">
                            @foreach($jenisBayar as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="bankContainer" style="display:none;">
                        <label>BANK</label>
                        <select id="bank_id" name="bank_id">
                            @foreach($bank as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="refundValueContainer" style="display:none;">
                        <label>NILAI REFUND</label>
                        <input type="number" id="nilai_refund" name="nilai_refund" value="0">
                    </div>

                    <div class="form-group" id="refundDateContainer" style="display:none;">
                        <label>TGL REALISASI</label>
                        <input type="datetime-local" id="tgl_realisasi" name="tgl_realisasi">
                    </div>

                    {{-- NAMA PIUTANG — textbox autocomplete, kirim piutang_id (jika ada) + nama_piutang_input --}}
                    <div class="form-group" id="namaPiutangContainer" style="display:none;">
                        <label>NAMA PIUTANG</label>
                        {{-- Hidden: id piutang jika dipilih dari rekomendasi --}}
                        <input type="hidden" id="piutang_id" name="piutang_id">
                        {{-- Textbox: teks yang diketik pengguna --}}
                        <input
                            type="text"
                            id="nama_piutang_input"
                            name="nama_piutang_input"
                            class="text-uppercase"
                            placeholder="Ketik nama piutang..."
                            autocomplete="off"
                            maxlength="100"
                        >
                        {{-- Dropdown rekomendasi --}}
                        <ul id="piutangSuggestions" style="
                            display:none; position:absolute; z-index:9999;
                            background:#fff; border:1px solid #ccc; border-radius:6px;
                            list-style:none; margin:0; padding:4px 0;
                            min-width:220px; max-height:200px; overflow-y:auto;
                            box-shadow:0 4px 12px rgba(0,0,0,0.15);
                        "></ul>
                        <small id="piutangHint" style="color:#888; font-size:11px;"></small>
                    </div>

                    <div class="button-group">
                        <button type="submit" id="btnInputData" class="btn-hijau">Input Data</button>
                        <button type="button" id="btnBatal" class="btn-hijau">Batal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL CARI ===================== --}}
<div id="modalCari" class="modal-cari">
    <div class="modal-content">
        <p>Masukan <b>KODE BOOKING</b> atau <b>NAMA</b> :</p>
        <input type="text" id="searchInput" placeholder="Search">
        <div class="modal-buttons">
            <button id="btnCariOk" class="btn-ok">OK</button>
            <button id="btnCariCancel" class="btn-cancel">CANCEL</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/input-data.css') }}">

<style>
    .tiket-section { padding: 20px; }
    .tiket-action-bar {
        display: flex; gap: 10px; margin-bottom: 16px;
        flex-wrap: wrap; align-items: center;
    }
    .tiket-action-bar > * {
        height: 40px; padding: 8px 18px; border: none;
        border-radius: 8px; font-weight: 600; font-size: 14px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; text-decoration: none;
        box-sizing: border-box; transition: all 0.25s ease;
    }
    .modal-overlay {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.55);
        z-index: 2000; display: flex;
        align-items: center; justify-content: center;
        overflow-y: auto; padding: 20px; box-sizing: border-box;
    }
    .modal-tiket-content {
        background: #fff; border-radius: 12px; padding: 30px;
        width: 100%; max-width: 960px; position: relative;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
    .modal-tiket-header {
        display: flex; align-items: center; gap: 16px;
        margin-bottom: 20px; flex-wrap: wrap;
    }
    .modal-tiket-header h2 { margin: 0; font-size: 20px; color: #2c3e50; }
    .btn-close-modal {
        margin-left: auto; background: #e74c3c; color: #fff;
        border: none; border-radius: 8px; width: 36px; height: 36px;
        font-size: 20px; cursor: pointer; display: flex;
        align-items: center; justify-content: center; transition: background 0.2s;
    }
    .btn-close-modal:hover { background: #c0392b; }
    .btn-delete {
        background-color: #e74c3c; color: #fff; border: none;
        padding: 5px 10px; border-radius: 6px; cursor: pointer; transition: 0.3s;
    }
    .btn-delete:hover { background-color: #c0392b; }
    .text-uppercase { text-transform: uppercase; }
    .modal-content {
        background: white; padding: 30px; border-radius: 10px;
        min-width: 300px; text-align: center;
    }
    .modal-content p { margin-bottom: 20px; font-size: 16px; }
    .modal-content input[type="text"] {
        width: 100%; padding: 10px; margin-bottom: 20px;
        border: 1px solid #ddd; border-radius: 6px;
    }
    .modal-buttons { display: flex; gap: 10px; justify-content: center; }
    /* Autocomplete highlight */
    #piutangSuggestions li {
        padding: 8px 14px; cursor: pointer; font-size: 13px;
    }
    #piutangSuggestions li:hover,
    #piutangSuggestions li.active {
        background: #e8f5e9; color: #1b5e20;
    }
    #piutangSuggestions li.create-new {
        color: #0277bd; font-style: italic;
    }
    #namaPiutangContainer { position: relative; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const $ = id => document.getElementById(id);

    /* ========= OPEN / CLOSE MODAL ========= */
    $('btnOpenModal').addEventListener('click', () => $('modalInputTiket').style.display = 'flex');
    $('btnCloseModal').addEventListener('click', closeInputModal);
    $('modalInputTiket').addEventListener('click', e => {
        if (e.target === $('modalInputTiket')) closeInputModal();
    });
    function closeInputModal() { $('modalInputTiket').style.display = 'none'; }

    /* ========= REFERENSI ELEMEN ========= */
    const statusCustomer    = $('statusCustomer');
    const form              = $('inputDataForm');
    const piutangIdInput    = $('piutang_id');          // hidden
    const namaPiutangInput  = $('nama_piutang_input');  // textbox
    const piutangSuggestions= $('piutangSuggestions');
    const piutangHint       = $('piutangHint');
    const ntaInput          = $('nta');
    const hargaJualInput    = $('harga_jual');
    const diskonInput       = $('diskon');
    const komisiInput       = $('komisi');

    /* ========= KOMISI AUTO ========= */
    function updateKomisi() {
        const nta    = parseInt(ntaInput.value)    || 0;
        const harga  = parseInt(hargaJualInput.value) || 0;
        const diskon = parseInt(diskonInput.value) || 0;
        komisiInput.value = Math.max(0, harga - diskon - nta);
    }
    [ntaInput, hargaJualInput, diskonInput].forEach(el => el.addEventListener('input', updateKomisi));

    /* ========= STATUS SHOW/HIDE ========= */
    const showBasicStatus = () =>
        document.querySelectorAll('.status-advanced').forEach(o => o.style.display = 'none');
    const showAllStatus = () =>
        document.querySelectorAll('.status-advanced').forEach(o => o.style.display = 'block');

    /* ========= TOGGLE REFUND ========= */
    const toggleRefund = () => {
        const isRefund = $('status').value === 'refunded';
        $('refundValueContainer').style.display = isRefund ? 'block' : 'none';
        $('refundDateContainer').style.display  = isRefund ? 'block' : 'none';
        $('nilai_refund').required  = isRefund;
        $('tgl_realisasi').required = isRefund;
    };
    $('status').addEventListener('change', toggleRefund);

    /* ========= AUTOCOMPLETE PIUTANG ========= */
    let acDebounce = null;
    let acActiveIndex = -1;
    let acData = [];

    function resetPiutangInput() {
        piutangIdInput.value   = '';
        namaPiutangInput.value = '';
        piutangHint.textContent = '';
        hideSuggestions();
    }

    function hideSuggestions() {
        piutangSuggestions.style.display = 'none';
        piutangSuggestions.innerHTML = '';
        acActiveIndex = -1;
        acData = [];
    }

    function renderSuggestions(items, keyword) {
        piutangSuggestions.innerHTML = '';
        acData = items;
        acActiveIndex = -1;

        items.forEach((item, idx) => {
            const li = document.createElement('li');
            li.textContent = item.nama;
            li.dataset.id  = item.id;
            li.addEventListener('mousedown', e => {
                e.preventDefault();
                selectPiutang(item);
            });
            piutangSuggestions.appendChild(li);
        });

        // Opsi buat baru jika tidak ada yang cocok persis
        const exactMatch = items.some(i => i.nama === keyword.toUpperCase());
        if (keyword && !exactMatch) {
            const li = document.createElement('li');
            li.className   = 'create-new';
            li.textContent = `+ Buat baru: "${keyword.toUpperCase()}"`;
            li.addEventListener('mousedown', e => {
                e.preventDefault();
                createNewPiutang(keyword);
            });
            piutangSuggestions.appendChild(li);
        }

        if (piutangSuggestions.children.length > 0) {
            piutangSuggestions.style.display = 'block';
        } else {
            hideSuggestions();
        }
    }

    function selectPiutang(item) {
        piutangIdInput.value    = item.id;
        namaPiutangInput.value  = item.nama;
        piutangHint.textContent = '✓ Terhubung ke data piutang yang ada';
        piutangHint.style.color = '#2e7d32';
        hideSuggestions();
    }

    function createNewPiutang(keyword) {
        piutangIdInput.value    = '';  // kosong → controller akan firstOrCreate
        namaPiutangInput.value  = keyword.toUpperCase();
        piutangHint.textContent = '✦ Nama baru — akan otomatis dibuat saat disimpan';
        piutangHint.style.color = '#0277bd';
        hideSuggestions();
    }

    namaPiutangInput.addEventListener('input', function () {
        const val = this.value.trim();
        piutangIdInput.value = ''; // reset id saat mengetik ulang
        piutangHint.textContent = '';

        clearTimeout(acDebounce);
        if (!val) { hideSuggestions(); return; }

        acDebounce = setTimeout(() => {
            fetch(`{{ route('input-tiket.searchPiutang') }}?q=${encodeURIComponent(val)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => renderSuggestions(data, val))
            .catch(() => hideSuggestions());
        }, 250);
    });

    // Navigasi keyboard pada suggestion list
    namaPiutangInput.addEventListener('keydown', function (e) {
        const items = piutangSuggestions.querySelectorAll('li');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            acActiveIndex = Math.min(acActiveIndex + 1, items.length - 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            acActiveIndex = Math.max(acActiveIndex - 1, 0);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (acActiveIndex >= 0) items[acActiveIndex].dispatchEvent(new Event('mousedown'));
            return;
        } else if (e.key === 'Escape') {
            hideSuggestions(); return;
        } else { return; }

        items.forEach((li, i) => li.classList.toggle('active', i === acActiveIndex));
    });

    namaPiutangInput.addEventListener('blur', () => {
        setTimeout(hideSuggestions, 200);
    });

    /* ========= TOGGLE JENIS PEMBAYARAN ========= */
    function toggleJenisPembayaran() {
        const jenisSelect = $('jenis_bayar_id');
        const bankSelect  = $('bank_id');

        if (jenisSelect.disabled) {
            bankSelect.value = '';
            bankSelect.disabled = true;
            $('bankContainer').style.display = 'none';
            $('namaPiutangContainer').style.display = 'none';
            return;
        }

        const jenis     = jenisSelect.value;
        const isBank    = jenis === '1';
        const isPiutang = jenis === '3';

        $('bankContainer').style.display = isBank ? 'block' : 'none';
        bankSelect.disabled = !isBank;
        if (!isBank) bankSelect.value = '';

        $('namaPiutangContainer').style.display = isPiutang ? 'block' : 'none';

        if (!isPiutang) {
            resetPiutangInput();
        }
    }
    $('jenis_bayar_id').addEventListener('change', toggleJenisPembayaran);

    /* ========= LOAD DETAIL TIKET (untuk edit) ========= */
    function loadTiketDetail(kodeBooking) {
        fetch(`tiket/by-tiket/${kodeBooking}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(d => {
            if (d.subagent_id) {
                statusCustomer.value = 'subagent';
                toggleCustomerType();
                $('subagent_id').value = d.subagent_id;
            } else {
                statusCustomer.value = 'customer';
                toggleCustomerType();
            }

            $('jenis_bayar_id').value = d.jenis_bayar_id ?? '';
            $('bank_id').value        = d.bank_id ?? '';

            // Set autocomplete piutang dari relasi
            if (d.piutang_id && d.piutang_nama) {
                selectPiutang({ id: d.piutang_id, nama: d.piutang_nama });
            } else {
                resetPiutangInput();
            }

            $('nilai_refund').value  = d.nilai_refund ?? 0;
            $('tgl_realisasi').value = d.tgl_realisasi
                ? d.tgl_realisasi.replace(' ', 'T').substring(0, 16)
                : '';

            toggleJenisPembayaran();
            toggleRefund();
        })
        .catch(err => console.error('loadTiketDetail error:', err));
    }

    /* ========= TOGGLE CUSTOMER TYPE ========= */
    function toggleCustomerType() {
        const type           = statusCustomer.value;
        const subagentSelect = $('subagent_id');
        const jenisBayarSel  = $('jenis_bayar_id');
        const bankSelect     = $('bank_id');

        document.querySelectorAll('.subagent-hide').forEach(el =>
            el.style.display = type === 'subagent' ? 'none' : 'block'
        );
        $('subagentContainer').style.display = type === 'subagent' ? 'block' : 'none';

        if (type === 'subagent') {
            subagentSelect.disabled = false;
            subagentSelect.required = true;
            jenisBayarSel.value     = '';
            jenisBayarSel.disabled  = true;
            bankSelect.value        = '';
            bankSelect.disabled     = true;
            $('bankContainer').style.display = 'none';
        } else {
            subagentSelect.disabled = true;
            subagentSelect.required = false;
            subagentSelect.value    = '';
            jenisBayarSel.disabled  = false;
        }
        toggleJenisPembayaran();
    }
    statusCustomer.addEventListener('change', toggleCustomerType);
    toggleCustomerType();

    /* ========= FILL FORM FROM ROW (klik baris tabel) ========= */
    let formMode = 'create';

    function fillFormFromRow(row) {
        showAllStatus();
        const td = row.children;
        formMode = 'update';
        form.action = `/input-tiket/${row.dataset.id}`;

        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type  = 'hidden';
            methodInput.name  = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        $('kode_booking').value  = row.dataset.id;
        $('name').value          = td[4].innerText;
        $('rute').value          = td[5].innerText;
        $('rute2').value         = td[7].innerText !== '-' ? td[7].innerText : '';
        $('harga_jual').value    = td[9].innerText.replace(/\./g, '');
        $('nta').value           = td[10].innerText.replace(/\./g, '');
        $('diskon').value        = td[11].innerText.replace(/\./g, '');
        $('komisi').value        = td[12].innerText.replace(/\./g, '');
        $('status').value        = td[13].innerText.toLowerCase();
        $('keterangan').value    = td[16].innerText !== '-' ? td[16].innerText : '';
        $('tgl_issued').value    = `${td[2].innerText}T00:00`;
        $('tgl_flight').value    = `${td[6].innerText}T00:00`;
        $('tgl_flight2').value   = td[8].innerText !== '-' ? `${td[8].innerText}T00:00` : '';
        $('jenis_tiket_id').value = row.dataset.jenisTiketId;

        toggleRefund();
        loadTiketDetail(row.dataset.id);

        $('btnInputData').textContent = 'UPDATE';
        $('modalInputTiket').style.display = 'flex';
    }

    function attachRowEvents() {
        document.querySelectorAll(
            '#tiketTableCashBank tbody tr, #tiketTablePiutang tbody tr'
        ).forEach(row => {
            row.addEventListener('click', e => {
                if (e.target.classList.contains('btn-delete')) return;
                document.querySelectorAll('#tiketTableCashBank tr, #tiketTablePiutang tr')
                    .forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');
                fillFormFromRow(row);
            });
        });
    }
    attachRowEvents();

    /* ========= CETAK INVOICE ========= */
    $('btnCetakInvoice').addEventListener('click', () => {
        const checked = document.querySelectorAll('.check-row:checked');
        if (!checked.length) { alert('Pilih minimal satu tiket'); return; }
        const codes = Array.from(checked).map(cb => cb.value).join(',');
        window.open(`/invoice-multi?codes=${codes}`, '_blank');
    });

    /* ========= MODAL CARI ========= */
    $('btnCari').addEventListener('click', () => {
        $('modalCari').style.display = 'flex';
        $('searchInput').focus();
    });

    function loadAllTickets() {
        fetch(`{{ route('input-tiket.search') }}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json()).then(data => renderTables(data))
        .catch(err => console.error(err));
    }

    $('btnCariCancel').addEventListener('click', () => {
        $('modalCari').style.display = 'none';
        $('searchInput').value = '';
        loadAllTickets();
    });
    $('searchInput').addEventListener('keydown', e => { if (e.key === 'Enter') $('btnCariOk').click(); });

    $('btnCariOk').addEventListener('click', () => {
        const keyword = $('searchInput').value.trim();
        if (!keyword) { alert('Masukkan nama atau kode booking'); return; }
        fetch(`{{ route('input-tiket.search') }}?q=${encodeURIComponent(keyword)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json()).then(data => renderTables(data))
        .catch(err => { console.error(err); alert('Gagal mengambil data'); });
        $('modalCari').style.display = 'none';
    });

    function renderTables(data) {
        const tbodyCB = document.querySelector('#tiketTableCashBank tbody');
        const tbodyP  = document.querySelector('#tiketTablePiutang tbody');
        tbodyCB.innerHTML = '';
        tbodyP.innerHTML  = '';

        const cbData = data.filter(t => t.mutasi_tiket?.jenis_bayar_id != 3);
        const pData  = data.filter(t => t.mutasi_tiket?.jenis_bayar_id == 3);

        tbodyCB.innerHTML = cbData.length
            ? cbData.map((t,i) => buildRowCB(t,i)).join('')
            : `<tr><td colspan="19" style="text-align:center;">Data tidak ditemukan</td></tr>`;

        tbodyP.innerHTML = pData.length
            ? pData.map((t,i) => buildRowP(t,i)).join('')
            : `<tr><td colspan="19" style="text-align:center;">Data tidak ditemukan</td></tr>`;

        attachRowEvents();
    }

    function buildRowCB(t, i) {
        return `<tr data-id="${t.kode_booking}" data-jenis-tiket-id="${t.jenis_tiket_id}">
            <td><input type="checkbox" class="check-row" value="${t.kode_booking}"></td>
            <td>${i+1}</td>
            <td>${t.tgl_issued?.substring(0,10) ?? '-'}</td>
            <td>${t.kode_booking}</td><td>${t.name}</td><td>${t.rute}</td>
            <td>${t.tgl_flight?.substring(0,10) ?? '-'}</td>
            <td>${t.rute2 ?? '-'}</td>
            <td>${t.tgl_flight2?.substring(0,10) ?? '-'}</td>
            <td>${Number(t.harga_jual).toLocaleString('id-ID')}</td>
            <td>${Number(t.nta).toLocaleString('id-ID')}</td>
            <td>${Number(t.diskon).toLocaleString('id-ID')}</td>
            <td>${Number(t.komisi).toLocaleString('id-ID')}</td>
            <td>${t.status}</td>
            <td>${t.jenis_tiket?.name_jenis ?? '-'}</td>
            <td>${t.pembayaran_label ?? '-'}</td>
            <td>${t.keterangan ?? '-'}</td>
            <td>${Number(t.nilai_refund ?? 0).toLocaleString('id-ID')}</td>
            <td>-</td>
        </tr>`;
    }

    function buildRowP(t, i) {
        const namaPiutang = t.mutasi_tiket?.piutang?.nama ?? '-';
        return `<tr data-id="${t.kode_booking}" data-jenis-tiket-id="${t.jenis_tiket_id}">
            <td><input type="checkbox" class="check-row" value="${t.kode_booking}"></td>
            <td>${i+1}</td>
            <td>${t.tgl_issued?.substring(0,10) ?? '-'}</td>
            <td>${t.kode_booking}</td><td>${t.name}</td><td>${t.rute}</td>
            <td>${t.tgl_flight?.substring(0,10) ?? '-'}</td>
            <td>${t.rute2 ?? '-'}</td>
            <td>${t.tgl_flight2?.substring(0,10) ?? '-'}</td>
            <td>${Number(t.harga_jual).toLocaleString('id-ID')}</td>
            <td>${Number(t.nta).toLocaleString('id-ID')}</td>
            <td>${Number(t.diskon).toLocaleString('id-ID')}</td>
            <td>${Number(t.komisi).toLocaleString('id-ID')}</td>
            <td>${t.status}</td>
            <td>${t.jenis_tiket?.name_jenis ?? '-'}</td>
            <td>${namaPiutang}</td>
            <td>${t.keterangan ?? '-'}</td>
            <td>${Number(t.nilai_refund ?? 0).toLocaleString('id-ID')}</td>
            <td>-</td>
        </tr>`;
    }

    /* ========= RESET / BATAL ========= */
    $('btnBatal').addEventListener('click', () => {
        formMode = 'create';
        form.action = "{{ route('input-tiket.store') }}";
        const mi = form.querySelector('input[name="_method"]');
        if (mi) mi.remove();
        form.reset();
        resetPiutangInput();
        $('btnInputData').textContent = 'Input Data';
        showBasicStatus();
        toggleJenisPembayaran();
        toggleRefund();
        closeInputModal();
    });

    showBasicStatus();
    toggleRefund();
    toggleJenisPembayaran();

    /* ========= ENTER KEY FLOW ========= */
    const enterFlow = [
        'kode_booking','name','rute','tgl_flight','rute2','tgl_flight2',
        'harga_jual','nta','diskon','status','jenis_tiket_id','keterangan'
    ];
    enterFlow.forEach((id, index) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('keydown', e => {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            updateKomisi();
            const next = document.getElementById(enterFlow[index + 1]);
            if (next) next.focus();
        });
    });

}); // end DOMContentLoaded
</script>

</x-layouts.app>
