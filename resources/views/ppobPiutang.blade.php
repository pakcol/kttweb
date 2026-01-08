<x-layouts.app title="PPOB - Piutang">
<link rel="stylesheet" href="{{ asset('css/plnPiutang.css') }}">

<section class="pln-piutang-container">
    {{-- JUDUL PAGE --}}
    <div class="piutang-title">
        PPOB - PIUTANG
    </div>

<style>
.piutang-title {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #ffffff;
    margin-bottom: 35px;
}
</style>


    {{-- FORM BAYAR PIUTANG --}}
    <form method="POST" action="" id="piutangForm" class="piutang-form">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" id="selected_id">

        <div class="form-left">
            {{-- NAMA --}}
            <label>Nama</label>
            <input type="text" id="nama" readonly>

            {{-- HARGA --}}
            <label>Harga</label>
            <input type="text" id="harga" readonly>

            {{-- JENIS PEMBAYARAN --}}
            <label for="jenis_bayar_id">Pembayaran</label>
            <select id="jenis_bayar_id" name="jenis_bayar_id" required>
                <option value="">-- Pilih Jenis Pembayaran --</option>
                @foreach($jenisBayarNonPiutang as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                @endforeach
            </select>

            {{-- BANK --}}
            <div id="bankContainer" style="display:none;">
                <label for="bank_id">BANK</label>
                <select id="bank_id" name="bank_id">
                    <option value="">-- Pilih Bank --</option>
                    @foreach($bank as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-right">
    <button type="submit" class="btn-hijau">
        BAYAR
    </button>
</div>

<style>
/* ===============================
   BUTTON BAYAR - INLINE FINAL
================================ */
.form-right .btn-hijau {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    background: linear-gradient(135deg, #06c167, #049f57);
    border: none !important;
    color: #ffffff !important;

    font-weight: 600;
    font-size: 14px;
    letter-spacing: 0.4px;
    text-transform: uppercase;

    padding: 12px 34px;
    border-radius: 14px;
    cursor: pointer;

    box-shadow:
        0 8px 20px rgba(6, 193, 103, 0.35),
        inset 0 1px 0 rgba(255,255,255,0.25);

    transition: all 0.25s ease;
}

.form-right .btn-hijau:hover {
    transform: translateY(-2px);
    box-shadow:
        0 14px 28px rgba(6, 193, 103, 0.45);
}

.form-right .btn-hijau:active {
    transform: translateY(0);
    box-shadow: 0 6px 14px rgba(6, 193, 103, 0.35);
}
</style>

    </form>

    {{-- TABEL PIUTANG --}}
    <div class="table-section">
        <table id="piutangTable">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>ID Pelanggan</th>
                    <th>Kategori PPOB</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($piutang as $row)
                    <tr onclick="selectRow(this)"
                        data-id="{{ $row->id }}"
                        data-nama="{{ $row->nama_piutang }}"
                        data-harga="{{ $row->harga_jual }}"
                    >
                        <td>{{ $row->tgl?->format('d-m-Y') }}</td>
                        <td>{{ $row->nama_piutang }}</td>
                        <td>{{ $row->id_pel }}</td>
                        <td>{{ $row->jenisPpob->jenis_ppob ?? '-' }}</td>
                        <td>{{ number_format($row->harga_jual) }}</td>
                        <td><span class="hint">Klik baris</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">DATA KOSONG</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

{{-- SCRIPT --}}
<script>
    const form = document.getElementById('piutangForm');
    const jenisSelect = document.getElementById('jenis_bayar_id');
    const bankContainer = document.getElementById('bankContainer');
    const bankInput = document.getElementById('bank_id');

    function toggleJenisPembayaran() {
        const jenis = jenisSelect.value;
        bankContainer.style.display = (jenis == 1) ? 'block' : 'none';
        bankInput.required = (jenis == 1);
    }

    jenisSelect.addEventListener('change', toggleJenisPembayaran);

    function selectRow(row) {
        document.querySelectorAll('#piutangTable tr')
            .forEach(r => r.classList.remove('selected'));

        row.classList.add('selected');

        document.getElementById('selected_id').value = row.dataset.id;
        document.getElementById('nama').value = row.dataset.nama;
        document.getElementById('harga').value =
            new Intl.NumberFormat('id-ID').format(row.dataset.harga);

        // set action form dinamis
        form.action = `/ppob/piutang/${row.dataset.id}`;
    }
</script>

</x-layouts.app>
