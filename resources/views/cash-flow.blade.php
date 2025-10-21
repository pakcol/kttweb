<x-layouts.app title="Cash Flow - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/tutup-kas.css') }}">

<div class="tutup-kas-wrapper">
    <div class="tutup-kas-card">
        <h2 class="page-title">CASH FLOW</h2>

```
    <!-- Form Pencarian Laporan Penjualan -->
    <form action="{{ route('tutup-kas.search') }}" method="GET" class="search-form">
        <label for="tanggal">LAPORAN PENJUALAN TANGGAL :</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
        <label for="tanggal">s/d</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
        <button type="submit" class="search-btn">CARI</button>
    </form>

    <!-- Form Utama Tutup Kas -->
    <form action="{{ route('tutup-kas.store') }}" method="POST" id="formTutupKas">
        @csrf
        <input type="hidden" name="id" id="recordId">

        <div class="form-grid">
            <!-- PENJUALAN -->
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

            <!-- TRANSFER -->
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

            <!-- SETORAN -->
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

            <!-- SALDO AIRLINES -->
            <div class="card">
                <h3>SALDO AIRLINES</h3>
                <label>CITILINK</label>
                <input type="text" name="SOCITILINK" id="SOCITILINK">
                <label>GARUDA</label>
                <input type="text" name="SOGARUDA" id="SOGARUDA">
                <label>QGCORNER</label>
                <input type="text" name="SOQGCORNER" id="SOQGCORNER">
                <label>LION</label>
                <input type="text" name="SOLION" id="SOLION">
                <label>SRIWIJAYA</label>
                <input type="text" name="SOSRIWIJAYA" id="SOSRIWIJAYA">
                <label>TRANSNUSA</label>
                <input type="text" name="SOTRANSNUSA" id="SOTRANSNUSA">
                <label>PELNI</label>
                <input type="text" name="SOPELNI" id="SOPELNI">
                <label>AIR ASIA</label>
                <input type="text" name="SOAIRASIA" id="SOAIRASIA">
                <label>DLU</label>
                <input type="text" name="SODLU" id="SODLU">
            </div>

            <!-- TOP UP AIRLINES -->
            <div class="card">
                <h3>TOP UP AIRLINES</h3>
                <label>CITILINK</label>
                <input type="text" name="TUCITILINK" id="TUCITILINK">
                <label>GARUDA</label>
                <input type="text" name="TUGARUDA" id="TUGARUDA">
                <label>QGCORNER</label>
                <input type="text" name="TUQGCORNER" id="TUQGCORNER">
                <label>LION</label>
                <input type="text" name="TULION" id="TULION">
                <label>SRIWIJAYA</label>
                <input type="text" name="TUSRIWIJAYA" id="TUSRIWIJAYA">
                <label>TRANSNUSA</label>
                <input type="text" name="TUTRANSNUSA" id="TUTRANSNUSA">
                <label>PELNI</label>
                <input type="text" name="TUPELNI" id="TUPELNI">
                <label>AIR ASIA</label>
                <input type="text" name="TUAIRASIA" id="TUAIRASIA">
                <label>DLU</label>
                <input type="text" name="TUDLU" id="TUDLU">
            </div>

            <!-- SALDO SUB AGENT -->
            <div class="card sub-agent">
                <h3>SALDO SUB AGENT</h3>
                <label>EVI</label>
                <input type="text" name="SOEVI" id="SOEVI">
                <label>CASH</label>
                <input type="text" name="CASH_FLOW" id="CASH_FLOW">
            </div>

            <!-- PLN -->
            <div class="card pln">
                <h3>PLN</h3>
                <label>PLN</label>
                <input type="text" name="PLN" id="PLN">
                <label>SISA SALDO</label>
                <input type="text" name="SALDOPLN" id="SALDOPLN">
            </div>
        </div>

        <!-- Tombol Simpan dan Batal -->
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
    <p style="color:#333;text-align:center;margin-top:30px;">Tidak ada data penjualan pada tanggal tersebut.</p>
    @endif

</div>
```

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
