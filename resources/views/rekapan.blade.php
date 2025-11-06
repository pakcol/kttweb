<x-layouts.app title="Rekapan Penjualan">
<link rel="stylesheet" href="{{ asset('css/rekapan.css') }}">

<section class="rekapan-section">
    <div class="rekapan-container">
        <h2>Rekapan Penjualan</h2>

        <form class="rekapan-form" action="{{ route('rekapan-penjualan.index') }}" method="GET">
            @csrf
            <div class="search-bar">
                <label for="tanggal">Laporan Penjualan Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}">
                <button type="submit" class="btn-cari">Cari</button>
            </div>

            <div class="rekapan-grid">
                <div class="rekapan-card">
                    <h3>PENJUALAN</h3>
                    <div class="input-group"><label>Penjualan:</label><input type="number" name="penjualan" value="12500000"></div>
                    <div class="input-group"><label>Piutang:</label><input type="number" name="piutang" value="2500000"></div>
                    <div class="input-group"><label>Pengeluaran:</label><input type="number" name="pengeluaran" value="3200000"></div>
                    <div class="input-group"><label>Refund:</label><input type="number" name="refund" value="500000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>TRANSFER BANK</h3>
                    <div class="input-group"><label>BCA:</label><input type="number" name="bca" value="3000000"></div>
                    <div class="input-group"><label>BRI:</label><input type="number" name="bri" value="2000000"></div>
                    <div class="input-group"><label>BNI:</label><input type="number" name="bni" value="1500000"></div>
                    <div class="input-group"><label>BTN:</label><input type="number" name="btn" value="1000000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>SALDO AIRLINES</h3>
                    <div class="input-group"><label>Citilink:</label><input type="number" name="citilink" value="1200000"></div>
                    <div class="input-group"><label>Garuda:</label><input type="number" name="garuda" value="1800000"></div>
                    <div class="input-group"><label>QGCorner:</label><input type="number" name="qgcorner" value="900000"></div>
                    <div class="input-group"><label>Lion:</label><input type="number" name="lion" value="1400000"></div>
                    <div class="input-group"><label>Sriwijaya:</label><input type="number" name="sriwijaya" value="700000"></div>
                    <div class="input-group"><label>Transnusa:</label><input type="number" name="transnusa" value="800000"></div>
                    <div class="input-group"><label>Pelni:</label><input type="number" name="pelni" value="600000"></div>
                    <div class="input-group"><label>Air Asia:</label><input type="number" name="airasia" value="900000"></div>
                    <div class="input-group"><label>DLU:</label><input type="number" name="dlu" value="500000"></div>
                    <div class="input-group"><label>PLN:</label><input type="number" name="pln" value="450000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>TOP UP AIRLINES</h3>
                    <div class="input-group"><label>Citilink:</label><input type="number" name="tu_citilink" value="600000"></div>
                    <div class="input-group"><label>Garuda:</label><input type="number" name="tu_garuda" value="800000"></div>
                    <div class="input-group"><label>QGCorner:</label><input type="number" name="tu_qgcorner" value="450000"></div>
                    <div class="input-group"><label>Lion:</label><input type="number" name="tu_lion" value="700000"></div>
                    <div class="input-group"><label>Sriwijaya:</label><input type="number" name="tu_sriwijaya" value="650000"></div>
                    <div class="input-group"><label>Transnusa:</label><input type="number" name="tu_transnusa" value="400000"></div>
                    <div class="input-group"><label>Pelni:</label><input type="number" name="tu_pelni" value="500000"></div>
                    <div class="input-group"><label>Air Asia:</label><input type="number" name="tu_airasia" value="450000"></div>
                    <div class="input-group"><label>DLU:</label><input type="number" name="tu_dlu" value="300000"></div>
                    <div class="input-group"><label>PLN:</label><input type="number" name="tu_pln" value="350000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>SETORAN BANK</h3>
                    <div class="input-group"><label>BCA:</label><input type="number" name="set_bca" value="2000000"></div>
                    <div class="input-group"><label>BRI:</label><input type="number" name="set_bri" value="1500000"></div>
                    <div class="input-group"><label>BNI:</label><input type="number" name="set_bni" value="1200000"></div>
                    <div class="input-group"><label>Mandiri:</label><input type="number" name="set_mandiri" value="1800000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>SALDO SUB AGENT</h3>
                    <div class="input-group"><label>Evi:</label><input type="number" name="sub_evi" value="1000000"></div>
                    <div class="input-group"><label>Cash:</label><input type="number" name="sub_cash" value="500000"></div>
                </div>
                <div class="rekapan-card">
                    <h3>PLN & SISA SALDO</h3>
                    <div class="input-group"><label>PLN:</label><input type="number" name="saldo_pln" value="250000"></div>
                    <div class="input-group"><label>Sisa Saldo:</label><input type="number" name="sisa_saldo" value="120000"></div>
                </div>
            </div>

            <div class="button-container">
                <button type="reset" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</section>
</x-layouts.app>
