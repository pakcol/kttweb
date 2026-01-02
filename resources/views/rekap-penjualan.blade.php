<x-layouts.app title="Cash Flow - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/cash-flow.css') }}">

<div class="cash-flow-wrapper">
    <div class="cash-flow-card">
        <h2 class="page-title">CASH FLOW</h2>
        <form action="{{ route('rekap.penjualan') }}" method="GET" class="search-form">
            <label for="tanggal_awal">LAPORAN PENJUALAN TANGGAL :</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
            <label for="tanggal_akhir">s/d</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
            <button type="submit" class="search-btn">CARI</button>
        </form>
        <form action="{{ route('tutup-kas.store') }}" method="POST" id="formTutupKas">
            @csrf
            <input type="hidden" name="id" id="recordId">

            <div class="form-grid">
                <div class="card">
                    <h3>PENJUALAN</h3>
                    <label>PENJUALAN</label>
                    <input type="text" name="TTL_PENJUALAN" id="TTL_PENJUALAN">
                    <label>PIUTANG</label>
                    <input type="text" name="PIUTANG" id="PIUTANG">
                    <label>PENGELUARAN</label>
                    <input type="text" name="BIAYA" id="BIAYA">
                    <label>REFUND</label>
                    <input type="text" name="REFUND" id="REFUND">
                </div>
                <div class="card">
                    <h3>TRANSFER</h3>
                    <label>TRANSFER BCA</label>
                    <input type="text" name="TRF_BCA" id="TRF_BCA">
                    <label>TRANSFER BRI</label>
                    <input type="text" name="TRF_BRI" id="TRF_BRI">
                    <label>TRANSFER BNI</label>
                    <input type="text" name="TRF_BNI" id="TRF_BNI">
                    <label>TRANSFER BTN</label>
                    <input type="text" name="TRF_BTN" id="TRF_BTN">
                </div>
                <div class="card">
                    <h3>SETORAN</h3>
                    <label>SETORAN BCA</label>
                    <input type="text" name="STR_BCA" id="STR_BCA">
                    <label>SETORAN BRI</label>
                    <input type="text" name="STR_BRI" id="STR_BRI">
                    <label>SETORAN BNI</label>
                    <input type="text" name="STR_BNI" id="STR_BNI">
                    <label>SETORAN MANDIRI</label>
                    <input type="text" name="STR_MDR" id="STR_MDR">
                </div>
                <div class="card">
                    <h3>SALDO AIRLINES</h3>
                    @foreach(['CITILINK','GARUDA','QGCORNER','LION','SRIWIJAYA','TRANSNUSA','PELNI','AIRASIA','DLU'] as $air)
                        <label>{{ $air }}</label>
                        <input type="text" name="SO{{ strtoupper($air) }}" id="SO{{ strtoupper($air) }}">
                    @endforeach
                </div>
                <div class="card">
                    <h3>TOP UP AIRLINES</h3>
                    @foreach(['CITILINK','GARUDA','QGCORNER','LION','SRIWIJAYA','TRANSNUSA','PELNI','AIRASIA','DLU'] as $air)
                        <label>{{ $air }}</label>
                        <input type="text" name="TU{{ strtoupper($air) }}" id="TU{{ strtoupper($air) }}">
                    @endforeach
                </div>
                <div class="card sub-agent">
                    <h3>SALDO SUB AGENT</h3>
                    <label>EVI</label>
                    <input type="text" name="SOEVI" id="SOEVI">
                    <label>CASH</label>
                    <input type="text" name="CASH_FLOW" id="CASH_FLOW">
                </div>
                <div class="card pln">
                    <h3>PLN</h3>
                    <label>PLN</label>
                    <input type="text" name="PLN" id="PLN">
                    <label>SISA SALDO</label>
                    <input type="text" name="SALDOPLN" id="SALDOPLN">
                </div>
            </div>

            <div class="button-container">
                <button type="reset" class="btn red">BATAL</button>
                <button type="submit" class="btn green">SIMPAN / UPDATE</button>
            </div>
        </form>

        @if(isset($penjualan) && count($penjualan) > 0)
        <div class="table-laporan">
            <table>
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>JAM</th>
                        <th>TOTAL PENJUALAN</th>
                        <th>PIUTANG</th>
                        <th>REFUND</th>
                        <th>CASH FLOW</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan as $p)
                    <tr data-record='@json($p)'>
                        <td>{{ $p->TANGGAL }}</td>
                        <td>{{ $p->JAM }}</td>
                        <td>{{ $p->TTL_PENJUALAN }}</td>
                        <td>{{ $p->PIUTANG }}</td>
                        <td>{{ $p->REFUND }}</td>
                        <td>{{ $p->CASH_FLOW }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif(isset($penjualan))
        <p class="no-data">Tidak ada data penjualan pada tanggal tersebut.</p>
        @endif

    </div>
</div>

<script>
document.querySelectorAll('.table-laporan tbody tr').forEach(row => {
    row.addEventListener('click', function() {
        const data = JSON.parse(this.dataset.record);
        Object.keys(data).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) input.value = data[key];
        });
        document.getElementById('recordId').value = data.id ?? '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>

</x-layouts.app>
