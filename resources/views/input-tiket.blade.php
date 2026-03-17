<x-layouts.app title="Input Tiket - PT. Kupang Tour & Travel">

{{-- ===================== TOMBOL & TABEL DATA TIKET ===================== --}}
<section class="tiket-section">

    {{-- Tombol aksi di atas tabel --}}
    <div class="tiket-action-bar">
        <button type="button" class="tiket-btn tiket-btn-hijau" id="btnOpenModal">+ Input Tiket</button>
        <button type="button" class="tiket-btn tiket-btn-hijau" id="btnCari">Cari</button>
        <button type="button" class="tiket-btn tiket-btn-merah" id="btnCetakInvoice">Cetak Invoice</button>
        <a href="{{ route('cash-flow.cashFlow', ['from' => 'input-kas']) }}" class="tiket-btn tiket-btn-oranye">Tutup Kas</a>
    </div>

    {{-- TABEL 1: Data Tiket Pembayaran Cash & Bank --}}
    <div class="tiket-table-card">
        <h3>Data Tiket — Pembayaran Cash &amp; Bank</h3>
        <div style="overflow-x:auto;">
        <table id="tiketTableCashBank">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAllCashBank"></th>
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
                    <th>Komisi</th>
                    <th>Status</th>
                    <th>Jenis Tiket</th>
                    <th>Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Nilai Refund</th>
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
                                @csrf
                                @method('DELETE')
                                <button class="tiket-btn-delete" type="submit" onclick="return confirm('Hapus tiket ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    {{-- TABEL 2: Data Tiket Pembayaran Piutang --}}
    <div class="tiket-table-card" style="margin-top:30px;">
        <h3>Data Tiket — Pembayaran Piutang</h3>
        <div style="overflow-x:auto;">
        <table id="tiketTablePiutang">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAllPiutang"></th>
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
                    <th>Komisi</th>
                    <th>Status</th>
                    <th>Jenis Tiket</th>
                    <th>Nama Piutang</th>
                    <th>Keterangan</th>
                    <th>Nilai Refund</th>
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
                        <td>{{ $t->nama_piutang ?? '-' }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td>{{ number_format($t->nilai_refund ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:center;">
                            <form action="{{ route('input-tiket.destroy', $t->kode_booking) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="tiket-btn-delete" type="submit" onclick="return confirm('Hapus tiket ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

</section>

{{-- ===================== MODAL POPUP INPUT TIKET ===================== --}}
<div id="modalInputTiket" class="tiket-modal-overlay" style="display:none;">
    <div class="tiket-modal-box">
        <div class="tiket-modal-header">
            <h2>INPUT TIKET</h2>
            <div class="current-time" id="currentDateTime"></div>
            <button type="button" class="tiket-btn-close" id="btnCloseModal">&times;</button>
        </div>

        <form action="{{ route('input-tiket.store') }}" id="inputDataForm" method="POST">
            @csrf
            <div class="tiket-form-group">
                <label for="statusCustomer">CUSTOMER TIKET</label>
                <select id="statusCustomer" name="statusCustomer" required>
                    <option value="customer">CUSTOMER</option>
                    <option value="subagent">SUBAGENT</option>
                </select>
            </div>
            <div class="tiket-form-group" id="subagentContainer" style="display:none;">
                <label>SUBAGENT</label>
                <select name="subagent_id" id="subagent_id">
                    @foreach($subagents as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tiket-input-grid">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="tiket-form-group">
                        <label for="tgl_issued">TGL ISSUED*</label>
                        <input type="datetime-local" id="tgl_issued" name="tgl_issued" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="tiket-form-group">
                        <label for="kode_booking">KODE BOOKING*</label>
                        <input type="text" id="kode_booking" name="kode_booking" class="text-uppercase" required maxlength="10">
                    </div>
                    <div class="tiket-form-group">
                        <label for="name">NAMA*</label>
                        <input type="text" id="name" name="name" class="text-uppercase" required maxlength="100">
                    </div>
                    <div class="tiket-form-group">
                        <label for="rute">RUTE*</label>
                        <input type="text" id="rute" name="rute" class="text-uppercase" required maxlength="45">
                    </div>
                    <div class="tiket-form-group">
                        <label for="tgl_flight">TGL FLIGHT*</label>
                        <input type="datetime-local" id="tgl_flight" name="tgl_flight" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="tiket-form-group">
                        <label for="rute2">RUTE 2</label>
                        <input type="text" id="rute2" name="rute2" class="text-uppercase" maxlength="45">
                    </div>
                    <div class="tiket-form-group">
                        <label for="tgl_flight2">TGL FLIGHT 2</label>
                        <input type="datetime-local" id="tgl_flight2" name="tgl_flight2">
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="tiket-form-group">
                        <label for="harga_jual">HARGA JUAL*</label>
                        <input type="number" id="harga_jual" name="harga_jual" value="0" required>
                    </div>
                    <div class="tiket-form-group">
                        <label for="nta">NTA*</label>
                        <input type="number" id="nta" name="nta" value="0" required>
                    </div>
                    <div class="tiket-form-group">
                        <label for="diskon">DISKON</label>
                        <input type="number" id="diskon" name="diskon" value="0">
                    </div>
                    <div class="tiket-form-group">
                        <label for="komisi">KOMISI</label>
                        <input type="number" id="komisi" name="komisi" value="0" readonly>
                    </div>
                    <div class="tiket-form-group status-advanced">
                        <label for="status">STATUS</label>
                        <select id="status" name="status" required>
                            <option value="issued" selected>Issued</option>
                            <option value="canceled">Canceled</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="tiket-form-group">
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
                    <div class="tiket-form-group">
                        <label for="keterangan">KETERANGAN</label>
                        <textarea id="keterangan" name="keterangan" class="text-uppercase" maxlength="200"></textarea>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="tiket-form-group subagent-hide">
                        <label for="jenis_bayar_id">JENIS PEMBAYARAN</label>
                        <select id="jenis_bayar_id" name="jenis_bayar_id" class="text-uppercase">
                            @if(isset($jenisBayar) && $jenisBayar->count() > 0)
                                @foreach($jenisBayar as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Data jenis pembayaran tidak ditemukan</option>
                            @endif
                        </select>
                    </div>

                    <div class="tiket-form-group" id="bankContainer" style="display:none;">
                        <label for="bank_id">BANK</label>
                        <select id="bank_id" name="bank_id" class="text-uppercase">
                            @if(isset($bank) && $bank->count() > 0)
                                @foreach($bank as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="tiket-form-group" id="refundValueContainer" style="display:none;">
                        <label for="nilai_refund">NILAI REFUND</label>
                        <input type="number" id="nilai_refund" name="nilai_refund" value="0">
                    </div>

                    <div class="tiket-form-group" id="refundDateContainer" style="display:none;">
                        <label for="tgl_realisasi">TGL REALISASI</label>
                        <input type="datetime-local" id="tgl_realisasi" name="tgl_realisasi">
                    </div>

                    <div class="tiket-form-group" id="namaPiutangContainer" style="display:none;">
                        <label for="nama_piutang_select">NAMA PIUTANG</label>
                        <select id="nama_piutang_select" class="text-uppercase">
                            <option value="">-- Pilih Nama Piutang --</option>
                            @foreach($piutangList as $p)
                                <option value="{{ $p->nama }}">{{ $p->nama }}</option>
                            @endforeach
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                        <input type="text" id="nama_piutang_input" name="nama_piutang"
                            class="text-uppercase" placeholder="Masukkan nama piutang"
                            style="display:none; margin-top:8px;">
                    </div>

                    <div class="tiket-button-group">
                        <button type="submit" id="btnInputData" class="tiket-btn tiket-btn-hijau">Input Data</button>
                        <button type="button" id="btnBatal" class="tiket-btn tiket-btn-abu">Batal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL CARI ===================== --}}
<div id="modalCari" class="tiket-modal-cari">
    <div class="tiket-modal-cari-box">
        <p>Masukan <b>KODE BOOKING</b> atau <b>NAMA</b> :</p>
        <input type="text" id="searchInput" placeholder="Search">
        <div class="tiket-modal-cari-btns">
            <button id="btnCariOk" class="tiket-btn tiket-btn-hijau">OK</button>
            <button id="btnCariCancel" class="tiket-btn tiket-btn-abu">CANCEL</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/input-data.css') }}">

<style>
/* =========================================================
   SCOPED STYLES  —  prefix "tiket-" agar tidak bentrok
   dengan CSS global dari layout
   ========================================================= */

/* Pastikan halaman ini full height agar background tidak terpotong */
body, html {
    min-height: 100%;
}

.tiket-section {
    padding: 24px 20px 60px;
    min-height: calc(100vh - 80px); /* 80px = estimasi tinggi navbar */
    box-sizing: border-box;
}

/* ---- ACTION BAR ---- */
.tiket-action-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
    flex-wrap: wrap;
    align-items: center;
}

/* ---- BUTTONS (semua pakai tiket- prefix) ---- */
.tiket-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 40px !important;
    padding: 0 18px !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    box-sizing: border-box !important;
    transition: background 0.2s, opacity 0.2s !important;
    white-space: nowrap !important;
}

.tiket-btn-hijau  { background-color: #27ae60 !important; color: #fff !important; }
.tiket-btn-hijau:hover  { background-color: #219653 !important; }
.tiket-btn-oranye { background-color: #f39c12 !important; color: #fff !important; }
.tiket-btn-oranye:hover { background-color: #e67e22 !important; }
.tiket-btn-merah  { background-color: #e74c3c !important; color: #fff !important; }
.tiket-btn-merah:hover  { background-color: #c0392b !important; }
.tiket-btn-abu    { background-color: #95a5a6 !important; color: #fff !important; }
.tiket-btn-abu:hover    { background-color: #7f8c8d !important; }

.tiket-btn-delete {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.2s;
}
.tiket-btn-delete:hover { background-color: #c0392b; }

/* ---- TABLE CARD ---- */
.tiket-table-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.tiket-table-card h3 {
    margin: 0 0 14px;
    font-size: 16px;
    color: #2c3e50;
    font-weight: 700;
}

/* ---- MODAL OVERLAY ---- */
.tiket-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 2000;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    overflow-y: auto;
    padding: 30px 16px;
    box-sizing: border-box;
}

.tiket-modal-box {
    background: #fff;
    border-radius: 12px;
    padding: 28px;
    width: 100%;
    max-width: 980px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    box-sizing: border-box;
}

.tiket-modal-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.tiket-modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: #2c3e50;
    font-weight: 700;
}
.tiket-btn-close {
    margin-left: auto;
    background: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    font-size: 22px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    transition: background 0.2s;
}
.tiket-btn-close:hover { background: #c0392b; }

/* ---- FORM ---- */
.tiket-input-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 16px;
}

.tiket-form-group { margin-bottom: 14px; }
.tiket-form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 13px;
    color: #333;
}
.tiket-form-group input[type="text"],
.tiket-form-group input[type="number"],
.tiket-form-group input[type="datetime-local"],
.tiket-form-group select,
.tiket-form-group textarea {
    width: 100%;
    padding: 9px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    background: #fff;
    color: #333;
}
.tiket-form-group input:focus,
.tiket-form-group select:focus,
.tiket-form-group textarea:focus {
    border-color: #27ae60;
    outline: none;
    box-shadow: 0 0 0 2px rgba(39,174,96,0.18);
}
.text-uppercase { text-transform: uppercase; }

.tiket-button-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
}
.tiket-button-group .tiket-btn {
    width: 100% !important;
    height: 44px !important;
}

/* ---- MODAL CARI ---- */
.tiket-modal-cari {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 3000;
    align-items: center;
    justify-content: center;
}
.tiket-modal-cari-box {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    min-width: 320px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.tiket-modal-cari-box p { margin-bottom: 16px; font-size: 15px; }
.tiket-modal-cari-box input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}
.tiket-modal-cari-btns {
    display: flex;
    gap: 10px;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ========= UTIL ========= */
    const $el = id => document.getElementById(id);

    /* ========= OPEN / CLOSE MODAL INPUT TIKET ========= */
    $el('btnOpenModal').addEventListener('click', () => {
        $el('modalInputTiket').style.display = 'flex';
    });

    $el('btnCloseModal').addEventListener('click', closeInputModal);

    $el('modalInputTiket').addEventListener('click', e => {
        if (e.target === $el('modalInputTiket')) closeInputModal();
    });

    function closeInputModal() {
        $el('modalInputTiket').style.display = 'none';
    }

    /* ========= REFERENSI FORM ========= */
    const statusCustomer  = $el('statusCustomer');
    const form            = $el('inputDataForm');
    const piutangSelect   = $el('nama_piutang_select');
    const piutangInput    = $el('nama_piutang_input');
    const ntaInput        = $el('nta');
    const hargaJualInput  = $el('harga_jual');
    const diskonInput     = $el('diskon');
    const komisiInput     = $el('komisi');

    function updateKomisi() {
        const nta      = parseInt(ntaInput.value) || 0;
        const hargaJual = parseInt(hargaJualInput.value) || 0;
        const diskon   = parseInt(diskonInput.value) || 0;
        const komisi   = hargaJual - diskon - nta;
        komisiInput.value = komisi >= 0 ? komisi : 0;
    }

    [ntaInput, hargaJualInput, diskonInput].forEach(el => {
        el.addEventListener('input', updateKomisi);
    });

    piutangSelect.addEventListener('change', () => {
        if (piutangSelect.value === 'LAINNYA') {
            piutangInput.style.display = 'block';
            piutangInput.required = true;
            piutangInput.value = '';
            piutangInput.focus();
        } else {
            piutangInput.style.display = 'none';
            piutangInput.required = false;
            piutangInput.value = piutangSelect.value;
        }
    });

    const showBasicStatus = () =>
        document.querySelectorAll('.status-advanced').forEach(o => o.style.display = 'none');
    const showAllStatus = () =>
        document.querySelectorAll('.status-advanced').forEach(o => o.style.display = 'block');

    const toggleRefund = () => {
        const isRefund = $el('status').value === 'refunded';
        $el('refundValueContainer').style.display = isRefund ? 'block' : 'none';
        $el('refundDateContainer').style.display  = isRefund ? 'block' : 'none';
        $el('nilai_refund').required  = isRefund;
        $el('tgl_realisasi').required = isRefund;
    };
    $el('status').addEventListener('change', toggleRefund);

    function toggleJenisPembayaran() {
        const jenisSelect = $el('jenis_bayar_id');
        const bankSelect  = $el('bank_id');

        if (jenisSelect.disabled) {
            bankSelect.value = '';
            bankSelect.disabled = true;
            $el('bankContainer').style.display = 'none';
            return;
        }

        const jenis    = jenisSelect.value;
        const isBank   = jenis === '1';
        const isPiutang = jenis === '3';

        $el('bankContainer').style.display = isBank ? 'block' : 'none';
        bankSelect.disabled = !isBank;
        if (!isBank) bankSelect.value = '';

        $el('namaPiutangContainer').style.display = isPiutang ? 'block' : 'none';
        if (!isPiutang) {
            piutangSelect.value = '';
            piutangInput.value = '';
            piutangInput.style.display = 'none';
            piutangInput.required = false;
        }
    }
    $el('jenis_bayar_id').addEventListener('change', toggleJenisPembayaran);

    function loadTiketDetail(kodeBooking) {
        fetch(`tiket/by-tiket/${kodeBooking}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(d => {
                if (d.subagent_id !== null && d.subagent_id !== '') {
                    statusCustomer.value = 'subagent';
                    toggleCustomerType();
                    $el('subagent_id').value = d.subagent_id;
                } else {
                    statusCustomer.value = 'customer';
                    toggleCustomerType();
                }
                $el('jenis_bayar_id').value = d.jenis_bayar_id ?? '';
                $el('bank_id').value        = d.bank_id ?? '';
                $el('nama_piutang_input').value = d.nama_piutang ?? '';
                $el('nilai_refund').value   = d.nilai_refund ?? 0;
                $el('tgl_realisasi').value  = d.tgl_realisasi ? d.tgl_realisasi.replace(' ', 'T') : '';
                toggleJenisPembayaran();
                toggleRefund();
            })
            .catch(err => console.error('Mutasi error:', err));
    }

    function toggleCustomerType() {
        const type             = statusCustomer.value;
        const subagentSelect   = $el('subagent_id');
        const jenisBayarSelect = $el('jenis_bayar_id');
        const bankSelect       = $el('bank_id');

        document.querySelectorAll('.subagent-hide').forEach(el => {
            el.style.display = (type === 'subagent') ? 'none' : 'block';
        });
        $el('subagentContainer').style.display = type === 'subagent' ? 'block' : 'none';

        if (type === 'subagent') {
            subagentSelect.disabled   = false;
            subagentSelect.required   = true;
            jenisBayarSelect.value    = '';
            jenisBayarSelect.disabled = true;
            bankSelect.value          = '';
            bankSelect.disabled       = true;
            $el('bankContainer').style.display = 'none';
        } else {
            subagentSelect.disabled   = true;
            subagentSelect.required   = false;
            subagentSelect.value      = '';
            jenisBayarSelect.disabled = false;
        }
        toggleJenisPembayaran();
    }
    statusCustomer.addEventListener('change', toggleCustomerType);
    toggleCustomerType();

    let formMode = 'create';

    function fillFormFromRow(row) {
        showAllStatus();
        const td = row.children;
        formMode = 'update';
        form.action = `/input-tiket/${row.dataset.id}`;

        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        $el('kode_booking').value  = row.dataset.id;
        $el('name').value          = td[4].innerText;
        $el('rute').value          = td[5].innerText;
        $el('rute2').value         = td[7].innerText !== '-' ? td[7].innerText : '';
        $el('harga_jual').value    = td[9].innerText.replace(/\./g, '');
        $el('nta').value           = td[10].innerText.replace(/\./g, '');
        $el('diskon').value        = td[11].innerText.replace(/\./g, '');
        $el('komisi').value        = td[12].innerText.replace(/\./g, '');
        $el('status').value        = td[13].innerText.toLowerCase();
        $el('keterangan').value    = td[16].innerText !== '-' ? td[16].innerText : '';
        $el('tgl_issued').value    = `${td[2].innerText}T00:00`;
        $el('tgl_flight').value    = `${td[6].innerText}T00:00`;
        $el('tgl_flight2').value   = td[8].innerText !== '-' ? `${td[8].innerText}T00:00` : '';
        $el('jenis_tiket_id').value = row.dataset.jenisTiketId;

        toggleRefund();
        loadTiketDetail(row.dataset.id);
        $el('btnInputData').textContent = 'UPDATE';
        $el('modalInputTiket').style.display = 'flex';
    }

    function attachRowEvents() {
        document.querySelectorAll(
            '#tiketTableCashBank tbody tr, #tiketTablePiutang tbody tr'
        ).forEach(row => {
            row.addEventListener('click', e => {
                if (e.target.classList.contains('tiket-btn-delete')) return;
                document.querySelectorAll('#tiketTableCashBank tr, #tiketTablePiutang tr')
                    .forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');
                fillFormFromRow(row);
            });
        });
    }
    attachRowEvents();

    /* ========= CETAK INVOICE ========= */
    $el('btnCetakInvoice').addEventListener('click', () => {
        const checked = document.querySelectorAll('.check-row:checked');
        if (checked.length === 0) { alert('Pilih minimal satu tiket'); return; }
        const codes = Array.from(checked).map(cb => cb.value).join(',');
        window.open(`/invoice-multi?codes=${codes}`, '_blank');
    });

    /* ========= MODAL CARI ========= */
    $el('btnCari').addEventListener('click', () => {
        $el('modalCari').style.display = 'flex';
        $el('searchInput').focus();
    });

    function loadAllTickets() {
        fetch(`{{ route('input-tiket.search') }}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => renderTables(data))
            .catch(err => console.error('Load all tickets error:', err));
    }

    $el('btnCariCancel').addEventListener('click', () => {
        $el('modalCari').style.display = 'none';
        $el('searchInput').value = '';
        loadAllTickets();
    });

    $el('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') $el('btnCariOk').click();
    });

    $el('btnCariOk').addEventListener('click', () => {
        const keyword = $el('searchInput').value.trim();
        if (!keyword) { alert('Masukkan nama atau kode booking'); return; }
        fetch(`{{ route('input-tiket.search') }}?q=${encodeURIComponent(keyword)}`, {
            headers: { 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => renderTables(data))
            .catch(err => { console.error(err); alert('Gagal mengambil data'); });
        $el('modalCari').style.display = 'none';
    });

    function buildRow(t, i) {
        return `
        <tr data-id="${t.kode_booking}" data-jenis-tiket-id="${t.jenis_tiket_id}">
            <td><input type="checkbox" class="check-row" value="${t.kode_booking}"></td>
            <td>${i + 1}</td>
            <td>${t.tgl_issued?.substring(0,10) ?? '-'}</td>
            <td>${t.kode_booking}</td>
            <td>${t.name}</td>
            <td>${t.rute}</td>
            <td>${t.tgl_flight?.substring(0,10) ?? '-'}</td>
            <td>${t.rute2 ?? '-'}</td>
            <td>${t.tgl_flight2?.substring(0,10) ?? '-'}</td>
            <td>${Number(t.harga_jual).toLocaleString('id-ID')}</td>
            <td>${Number(t.nta).toLocaleString('id-ID')}</td>
            <td>${Number(t.diskon).toLocaleString('id-ID')}</td>
            <td>${Number(t.komisi).toLocaleString('id-ID')}</td>
            <td>${t.status}</td>
            <td>${t.jenis_tiket?.name_jenis ?? '-'}</td>
            <td>${t.pembayaran_label ?? t.nama_piutang ?? '-'}</td>
            <td>${t.keterangan ?? '-'}</td>
            <td>${Number(t.nilai_refund ?? 0).toLocaleString('id-ID')}</td>
            <td>-</td>
        </tr>`;
    }

    function renderTables(data) {
        const tbodyCB  = document.querySelector('#tiketTableCashBank tbody');
        const tbodyPiu = document.querySelector('#tiketTablePiutang tbody');
        tbodyCB.innerHTML  = '';
        tbodyPiu.innerHTML = '';

        const cbData  = data.filter(t => t.jenis_bayar_id != 3);
        const piuData = data.filter(t => t.jenis_bayar_id == 3);

        tbodyCB.innerHTML  = cbData.length  ? cbData.map(buildRow).join('')
            : '<tr><td colspan="19" style="text-align:center;">Data tidak ditemukan</td></tr>';
        tbodyPiu.innerHTML = piuData.length ? piuData.map(buildRow).join('')
            : '<tr><td colspan="19" style="text-align:center;">Data tidak ditemukan</td></tr>';

        attachRowEvents();
    }

    /* ========= RESET / BATAL ========= */
    $el('btnBatal').addEventListener('click', () => {
        formMode = 'create';
        form.action = "{{ route('input-tiket.store') }}";
        const m = form.querySelector('input[name="_method"]');
        if (m) m.remove();
        form.reset();
        $el('btnInputData').textContent = 'Input Data';
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
        const el = $el(id);
        if (!el) return;
        el.addEventListener('keydown', e => {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            updateKomisi();
            const nextEl = $el(enterFlow[index + 1]);
            if (nextEl) {
                nextEl.focus();
                if (nextEl.tagName === 'TEXTAREA')
                    nextEl.selectionStart = nextEl.selectionEnd = nextEl.value.length;
            }
        });
    });

}); // end DOMContentLoaded
</script>

</x-layouts.app>
