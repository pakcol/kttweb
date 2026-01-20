<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<div class="piutang-page">
<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Data Piutang</h2>
        <form method="POST" id="formPiutang">
            @csrf
            @method('PUT')

            <input type="hidden" name="mutasi_id" id="mutasi_id">

            <div class="form-group">
                <label>Nama Piutang</label>
                <select id="piutang_id_select" class="form-control">
                    <option value="">ALL</option>

                    @foreach(
                        $piutang
                            ->pluck('piutang')
                            ->filter()
                            ->unique('id')
                            ->values()
                        as $p
                    )
                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kode Booking</label>
                <input type="text" id="kode_booking" class="form-control">
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" id="nominal" class="form-control">
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

    <div class="card-table">
        <h3>Data Piutang</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
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

                        <td>{{ $row->tiket->tgl_issued?->format('Y-m-d') }}</td>

                        <td>{{ $row->piutang->nama ?? '-' }}</td>
                        <td>{{ $row->tiket_kode_booking }}</td>
                        <td>{{ $row->tiket->jenisTiket->name_jenis ?? '-' }}</td>

                        <td>
                            {{ number_format($row->harga_bayar, 0, ',', '.') }}
                        </td>

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
                            >

                                Bayar
                            </button>
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
    const rows = document.querySelectorAll('.table tbody tr');

    piutangSelect.addEventListener('change', function () {
        const selectedId = this.value;

        rows.forEach(row => {
            const rowPiutangId = row.dataset.piutangId;
            row.style.display =
                !selectedId || rowPiutangId === selectedId ? '' : 'none';
        });
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {

            document.getElementById('mutasi_id').value = btn.dataset.id;
            document.getElementById('kode_booking').value = btn.dataset.kode;
            document.getElementById('nominal').value = btn.dataset.nominal;
        });
    });
</script>

<style>
.table-piutang tbody tr {
    cursor: pointer;
}
.table-piutang tbody tr:hover {
    background-color: #f5f5f5;
}
.table-piutang tbody tr.selected {
    background-color: #e3f2fd;
}
.status-belum-lunas {
    color: #f44336;
    font-weight: bold;
}
</style>

</x-layouts.app>
