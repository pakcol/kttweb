<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<div class="piutang-page">
<section class="piutang-section">

    {{-- ===================== CARD FORM BAYAR ===================== --}}
    <div class="card-form">
        <h2 class="form-title">Data Piutang</h2>
        <form method="POST" id="formPiutang">
            @csrf
            @method('PUT')

            <input type="hidden" name="mutasi_id" id="mutasi_id">

            <div class="form-group">
                <label>Nama Piutang</label>
                <select id="piutang_id_select" class="form-control">
                    <option value="" data-nama="">ALL</option>
                    @foreach($piutangNames as $p)
                        <option value="{{ $p->id }}" data-nama="{{ strtoupper(trim($p->nama)) }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kode Booking</label>
                <input type="text" id="kode_booking" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" id="nominal" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Jenis Pembayaran</label>
                <select name="jenis_bayar_id" id="jenis_bayar_id" class="form-control" required>
                    @foreach($jenisBayarNonPiutang as $j)
                        <option value="{{ $j->id }}">{{ $j->jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="bankContainer" style="display:none">
                <label>Bank</label>
                <select name="bank_id" class="form-control">
                    <option value="">-- Pilih Bank --</option>
                    @foreach($bank as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Realisasi</label>
                <input type="date" name="tgl_bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <button class="btn btn-success">SIMPAN</button>
        </form>
    </div>

    {{-- ===================== TABEL DATA PIUTANG ===================== --}}
    <div class="card-table">
        <h3 id="tableTitle">Data Piutang</h3>
        <table class="table" id="tabelPiutang">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Piutang</th>
                    <th>Kode Booking</th>
                    <th>Airlines</th>
                    <th>Nominal</th>
                    <th>Rute</th>
                    <th>Tgl Flight</th>
                    <th>Rute 2</th>
                    <th>Tgl Flight 2</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tbodyPiutang">
                @foreach ($piutang as $row)
                @php
                    {{-- Murni dari relasi piutang, tidak ada fallback nama_piutang --}}
                    $namaPiutang = strtoupper(trim($row->piutang?->nama ?? ''));
                @endphp
                <tr
                    data-mutasi-id="{{ $row->id }}"
                    data-piutang-id="{{ $row->piutang_id ?? '' }}"
                    data-nama-piutang="{{ $namaPiutang }}"
                >
                    <td>{{ $row->tiket?->tgl_issued?->format('Y-m-d') ?? '-' }}</td>

                    <td class="nama-piutang" style="cursor:pointer; color:#1a73e8; font-weight:600;">
                        {{ $row->piutang?->nama ?? '-' }}
                    </td>

                    <td>{{ $row->tiket_kode_booking }}</td>
                    <td>{{ $row->tiket?->jenisTiket?->name_jenis ?? '-' }}</td>
                    <td>{{ number_format($row->harga_bayar, 0, ',', '.') }}</td>
                    <td>{{ $row->tiket?->rute ?? '-' }}</td>
                    <td>{{ $row->tiket?->tgl_flight?->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ $row->tiket?->rute2 ?? '-' }}</td>
                    <td>{{ $row->tiket?->tgl_flight2?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-primary btn-edit"
                            data-id="{{ $row->id }}"
                            data-kode="{{ $row->tiket_kode_booking }}"
                            data-nominal="{{ $row->harga_bayar }}"
                        >Bayar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const piutangSelect = document.getElementById('piutang_id_select');
    const tableTitle    = document.getElementById('tableTitle');
    const tbody         = document.getElementById('tbodyPiutang');

    function normalize(str) {
        return (str || '').toString().trim().toUpperCase();
    }

    // ===================== FUNGSI FILTER TABEL =====================
    function filterTabel(selectedId, selectedNama, labelText) {
        const allRows = tbody.querySelectorAll('tr');

        if (!selectedId && !selectedNama) {
            allRows.forEach(function(row) { row.style.display = ''; });
            tableTitle.textContent = 'Data Piutang';
            return;
        }

        allRows.forEach(function(row) {
            var rowId   = normalize(row.getAttribute('data-piutang-id'));
            var rowNama = normalize(row.getAttribute('data-nama-piutang'));

            var cocokById   = (selectedId !== '' && rowId === normalize(selectedId));
            var cocokByNama = (selectedNama !== '' && rowNama !== '' && rowNama === normalize(selectedNama));

            row.style.display = (cocokById || cocokByNama) ? '' : 'none';
        });

        tableTitle.textContent = 'Data Piutang — ' + labelText;
    }

    // ===================== FILTER BY DROPDOWN =====================
    piutangSelect.addEventListener('change', function () {
        var selectedOpt  = this.options[this.selectedIndex];
        var selectedId   = this.value;
        var selectedNama = normalize(selectedOpt.getAttribute('data-nama'));
        var labelText    = selectedOpt.text.trim();

        filterTabel(selectedId, selectedNama, labelText);
    });

    // ===================== KLIK NAMA PIUTANG DI TABEL =====================
    tbody.querySelectorAll('.nama-piutang').forEach(function(cell) {
        cell.addEventListener('click', function () {
            var row       = this.closest('tr');
            var piutangId = row.getAttribute('data-piutang-id') || '';
            var namaTeks  = normalize(row.getAttribute('data-nama-piutang'));
            var label     = this.textContent.trim();

            var matched = false;
            for (var i = 0; i < piutangSelect.options.length; i++) {
                var opt     = piutangSelect.options[i];
                var optId   = opt.value;
                var optNama = normalize(opt.getAttribute('data-nama'));

                var matchId   = (piutangId !== '' && optId !== '' && optId === piutangId);
                var matchNama = (namaTeks !== '' && optNama === namaTeks);

                if (matchId || matchNama) {
                    piutangSelect.selectedIndex = i;
                    matched = true;
                    break;
                }
            }
            if (!matched) piutangSelect.selectedIndex = 0;

            filterTabel(piutangId, namaTeks, label);
        });
    });

    // ===================== TOMBOL BAYAR =====================
    tbody.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('mutasi_id').value    = this.getAttribute('data-id');
            document.getElementById('kode_booking').value = this.getAttribute('data-kode');
            document.getElementById('nominal').value      = this.getAttribute('data-nominal');
        });
    });

    // ===================== TOGGLE BANK =====================
    document.getElementById('jenis_bayar_id').addEventListener('change', function () {
        document.getElementById('bankContainer').style.display =
            this.value === '1' ? 'block' : 'none';
    });

});
</script>

<style>
.nama-piutang:hover {
    text-decoration: underline;
    color: #0d47a1 !important;
}
.table tbody tr {
    cursor: default;
}
</style>

</x-layouts.app>
