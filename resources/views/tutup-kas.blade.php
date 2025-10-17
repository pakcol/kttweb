<x-layouts.app title="Tutup Kas - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/tutup-kas.css') }}">

<div class="tutup-kas-wrapper">
    <div class="tutup-kas-card">
        <h2 class="page-title">FORM TUTUP KAS</h2>

        <form action="{{ route('tutup-kas.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="card">
                    <h3>PENJUALAN</h3>
                    <label>PENJUALAN</label>
                    <input type="text" name="TTL_PENJUALAN">

                    <label>PIUTANG</label>
                    <input type="text" name="PIUTANG">

                    <label>PENGELUARAN</label>
                    <input type="text" name="BIAYA">

                    <label>REFUND</label>
                    <input type="text" name="REFUND">
                </div>

                <div class="card">
                    <h3>TRANSFER</h3>
                    <label>TRANSFER BCA</label>
                    <input type="text" name="TRF_BCA">

                    <label>TRANSFER BRI</label>
                    <input type="text" name="TRF_BRI">

                    <label>TRANSFER BNI</label>
                    <input type="text" name="TRF_BNI">

                    <label>TRANSFER BTN</label>
                    <input type="text" name="TRF_BTN">
                </div>

                <div class="card">
                    <h3>SETORAN</h3>
                    <label>SETORAN BCA</label>
                    <input type="text" name="STR_BCA">

                    <label>SETORAN BRI</label>
                    <input type="text" name="STR_BRI">

                    <label>SETORAN BNI</label>
                    <input type="text" name="STR_BNI">

                    <label>SETORAN MANDIRI</label>
                    <input type="text" name="STR_MDR">
                </div>


                <div class="card">
                    <h3>SALDO AIRLINES</h3>
                    <label>CITILINK</label>
                    <input type="text" name="SOCITILINK">

                    <label>GARUDA</label>
                    <input type="text" name="SOGARUDA">

                    <label>QGCORNER</label>
                    <input type="text" name="SOQGCORNER">

                    <label>LION</label>
                    <input type="text" name="SOLION">

                    <label>SRIWIJAYA</label>
                    <input type="text" name="SOSRIWIJAYA">

                    <label>TRANSNUSA</label>
                    <input type="text" name="SOTRANSNUSA">

                    <label>PELNI</label>
                    <input type="text" name="SOPELNI">

                    <label>AIR ASIA</label>
                    <input type="text" name="SOAIRASIA">

                    <label>DLU</label>
                    <input type="text" name="SODLU">
                </div>

                <div class="card">
                    <h3>TOP UP AIRLINES</h3>
                    <label>CITILINK</label>
                    <input type="text" name="TUCITILINK">

                    <label>GARUDA</label>
                    <input type="text" name="TUGARUDA">

                    <label>QGCORNER</label>
                    <input type="text" name="TUQGCORNER">

                    <label>LION</label>
                    <input type="text" name="TULION">

                    <label>SRIWIJAYA</label>
                    <input type="text" name="TUSRIWIJAYA">

                    <label>TRANSNUSA</label>
                    <input type="text" name="TUTRANSNUSA">

                    <label>PELNI</label>
                    <input type="text" name="TUPELNI">

                    <label>AIR ASIA</label>
                    <input type="text" name="TUAIRASIA">

                    <label>DLU</label>
                    <input type="text" name="TUDLU">
                </div>

                <div class="card">
                    <h3>SALDO SUB AGENT</h3>
                    <label>EVI</label>
                    <input type="text" name="SOEVI">

                    <label>CASH</label>
                    <input type="text" name="CASH_FLOW">
                </div>
            </div>

            <div class="button-container">
                <button type="reset" class="btn red">BATAL</button>
                <button type="submit" class="btn green">SIMPAN</button>
            </div>
        </form>
    </div>
</div>

</x-layouts.app>
