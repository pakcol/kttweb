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

            {{-- ✅ FIX: dropdown diambil dari tabel piutang yg benar-benar terpakai --}}
            <div class="form-group">
                <label>Nama Piutang</label>
                <select id="piutang_id_select" class="form-control">
                    <option value="">ALL</option>
                    @foreach($piutangNames as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
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
        <table class="table">
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
            <tbody>
                @foreach ($piutang as $row)
                <tr
                    data-mutasi-id="{{ $row->id }}"
                    data-piutang-id="{{ $row->piutang_id }}"
                >
                    <td>{{ $row->tiket?->tgl_issued?->format('Y-m-d') ?? '-' }}</td>

                    {{-- ✅ FIX: nama dari relasi tabel piutang --}}
                    <td class="nama-piutang" style="cursor:pointer; color:#1a73e8; font-weight:600;">
                        {{ $row->piutang?->nama ?? $row->nama_piutang ?? '-' }}
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
const piutangSelect = document.getElementById('piutang_id_select');
const rows          = document.querySelectorAll('.table tbody tr');
const tableTitle    = document.getElementById('tableTitle');

// ===================== FILTER TABEL BY DROPDOWN =====================
piutangSelect.addEventListener('change', function () {
    const selectedId   = this.value;
    const selectedText = this.options[this.selectedIndex].text;

    rows.forEach(row => {
        const rowPiutangId = row.dataset.piutangId?.toString();
        row.style.display  = (!selectedId || rowPiutangId === selectedId) ? '' : 'none';
    });

    tableTitle.textContent = selectedId
        ? `Data Piutang — ${selectedText}`
        : 'Data Piutang';
});

// ===================== KLIK NAMA PIUTANG → FILTER TABEL =====================
// Ketika nama piutang di tabel diklik, filter tabel hanya tampilkan
// semua piutang dengan nama yang sama (piutang_id yang sama)
document.querySelectorAll('.nama-piutang').forEach(cell => {
    cell.addEventListener('click', function () {
        const row        = this.closest('tr');
        const piutangId  = row.dataset.piutangId;
        const namaTeks   = this.textContent.trim();

        // Sync dropdown
        piutangSelect.value = piutangId;

        // Filter tabel
        rows.forEach(r => {
            r.style.display = r.dataset.piutangId === piutangId ? '' : 'none';
        });

        tableTitle.textContent = `Data Piutang — ${namaTeks}`;
    });
});

// ===================== TOMBOL BAYAR =====================
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('mutasi_id').value  = btn.dataset.id;
        document.getElementById('kode_booking').value = btn.dataset.kode;
        document.getElementById('nominal').value    = btn.dataset.nominal;
    });
});

// ===================== TOGGLE BANK =====================
document.getElementById('jenis_bayar_id').addEventListener('change', function () {
    document.getElementById('bankContainer').style.display =
        this.value === '1' ? 'block' : 'none';
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
