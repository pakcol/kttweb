@php
    $fromInputKas = request()->query('from') === 'input-kas';
@endphp

<x-layouts.app 
    :fullWidth="true"
    title="{{ $fromInputKas ? 'Tutup Kas - PT. Kupang Tour & Travel' : 'Cash Flow - PT. Kupang Tour & Travel' }}">

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cashflow.css') }}">
@endpush

<div class="cash-flow-wrapper {{ $fromInputKas ? 'mode-tutup-kas' : '' }}">
    <div class="cash-flow-card">

        <h2 class="page-title">CASH FLOW</h2>

        {{-- FILTER --}}
        <form method="GET" class="search-form">
            <label>LAPORAN PENJUALAN TANGGAL</label>
            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
            <button type="submit">CARI</button>
        </form>

        {{-- GRID --}}
        <form method="POST">
            @csrf

            <div class="form-grid cashflow-grid">

    {{-- BARIS 1 --}}
    <div class="card penjualan">
        <h3>PENJUALAN</h3>
        <label>PENJUALAN</label><input>
        <label>PIUTANG</label><input>
        <label>PENGELUARAN</label><input>
        <label>REFUND</label><input>
    </div>

    <div class="card saldo-airlines">
        <h3>SALDO AIRLINES</h3>
        <label>CITILINK</label><input>
        <label>GARUDA</label><input>
        <label>QGCORNER</label><input>
        <label>LION</label><input>
        <label>SRIWIJAYA</label><input>
        <label>TRANSNUSA</label><input>
        <label>PELNI</label><input>
        <label>AIR ASIA</label><input>
        <label>DLU</label><input>
    </div>

    <div class="card topup-airlines">
        <h3>TOP UP AIRLINES</h3>
        <label>CITILINK</label><input>
        <label>GARUDA</label><input>
        <label>QGCORNER</label><input>
        <label>LION</label><input>
        <label>SRIWIJAYA</label><input>
        <label>TRANSNUSA</label><input>
        <label>PELNI</label><input>
        <label>AIR ASIA</label><input>
        <label>DLU</label><input>
    </div>

    {{-- BARIS 2 --}}
    <div class="card transfer">
        <h3>TRANSFER</h3>
        <label>TRANSFER BCA</label><input>
        <label>TRANSFER BRI</label><input>
        <label>TRANSFER BNI</label><input>
        <label>TRANSFER BTN</label><input>
    </div>

    <div class="card pln">
    <h3>PLN</h3>

    <label>PLN</label>
    <input type="text">

    <div class="sisa-saldo-box">
        <label>SISA SALDO</label>
        <input type="text">
    </div>
</div>

    <div class="card sub-agent">
        <h3>SALDO SUB AGENT</h3>
        <label>EVI</label><input>
    </div>

    {{-- BARIS 3 --}}
    <div class="card setoran">
        <h3>SETORAN</h3>
        <label>SETORAN BCA</label><input>
        <label>SETORAN BRI</label><input>
        <label>SETORAN BNI</label><input>
        <label>SETORAN MANDIRI</label><input>
    </div>

    <div class="card cash">
        <h3>CASH</h3>
        <input>
    </div>

</div>

            <div class="button-container">
                <button class="btn red" type="reset">BATAL</button>
                <button class="btn green" type="submit">SIMPAN</button>
            </div>

        </form>

    </div>
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