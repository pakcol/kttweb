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
                    <option value="">-- Semua Piutang --</option>
                    @foreach($piutangNames as $p)
                        <option value="{{ $p->id }}" data-nama="{{ strtoupper($p->nama) }}">
                            {{ $p->nama }}
                        </option>
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
                @php
                    {{-- Ambil nama: prioritaskan dari relasi, fallback ke kolom nama_piutang --}}
                    $namaPiutang = $row->piutang?->nama ?? $row->nama_piutang ?? '-';
                @endphp
                <tr
                    data-mutasi-id="{{ $row->id }}"
                    data-piutang-id="{{ $row->piutang_id ?? '' }}"
                    data-nama-piutang="{{ strtoupper($namaPiutang) }}"
                >
                    <td>{{ $row->tiket?->tgl_issued?->format('Y-m-d') ?? '-' }}</td>

                    <td class="nama-piutang" style="cursor:pointer; color:#1a73e8; font-weight:600;">
                        {{ $namaPiutang }}
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
const tbody         = document.querySelector('.table tbody');
const tableTitle    = document.getElementById('tableTitle');

/**
 * Filter baris tabel berdasarkan pilihan dropdown.
 * Cocokkan pakai piutang_id (data baru) ATAU nama teks (data lama yg piutang_id masih null).
 */
function filterTabel(selectedId, selectedNama, labelText) {
    const rows = tbody.querySelectorAll('tr');

    rows.forEach(row => {
        if (!selectedId && !selectedNama) {
            // Tampilkan semua
            row.style.display = '';
        } else {
            const rowPiutangId   = row.dataset.piutangId?.toString();
            const rowNamaPiutang = row.dataset.namaPiutang?.toString().toUpperCase();

            const cocokById   = selectedId   && rowPiutangId   === selectedId.toString();
            const cocokByNama = selectedNama && rowNamaPiutang === selectedNama.toString().toUpperCase();

            row.style.display = (cocokById || cocokByNama) ? '' : 'none';
        }
    });

    tableTitle.textContent = labelText
        ? `Data Piutang \u2014 ${labelText}`
        : 'Data Piutang';
}

// ===================== FILTER BY DROPDOWN =====================
piutangSelect.addEventListener('change', function () {
    const selectedId   = this.value;
    const opt          = this.options[this.selectedIndex];
    const selectedNama = opt ? opt.dataset.nama : '';
    const labelText    = selectedId ? opt.text.trim() : '';

    filterTabel(selectedId, selectedNama, labelText);
});

// ===================== KLIK NAMA PIUTANG DI TABEL → FILTER OTOMATIS =====================
document.querySelectorAll('.nama-piutang').forEach(cell => {
    cell.addEventListener('click', function () {
        const row        = this.closest('tr');
        const piutangId  = row.dataset.piutangId;
        const namaTeks   = row.dataset.namaPiutang;
        const labelText  = this.textContent.trim();

        // Sync dropdown ke pilihan yang sesuai
        let synced = false;
        for (const opt of piutangSelect.options) {
            if (
                (piutangId && opt.value === piutangId) ||
                (!piutangId && opt.dataset.nama === namaTeks)
            ) {
                piutangSelect.value = opt.value;
                synced = true;
                break;
            }
        }
        if (!synced) piutangSelect.value = '';

        filterTabel(piutangId, namaTeks, labelText);
    });
});

// ===================== TOMBOL BAYAR =====================
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('mutasi_id').value    = btn.dataset.id;
        document.getElementById('kode_booking').value = btn.dataset.kode;
        document.getElementById('nominal').value      = btn.dataset.nominal;
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
</style>

</x-layouts.app>
