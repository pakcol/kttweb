<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Insentif</h2>

        <form id="formPiutang" method="POST" action="{{ route('piutang.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="tgl_issued">TGL ISSUED</label>
                    <input type="date" id="tgl_issued" name="tgl_issued" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Insentif</label>
                    <input type="number" name="total_piutang" placeholder="Masukkan total piutang">
                </div>

                <div class="form-group full-width">
                    <label for="keterangan">KETERANGAN</label>
                    <textarea id="keterangan" name="keterangan" class="text-uppercase"></textarea>
                </div>
            </div>

            <div class="btn-actions">
                <button type="submit" class="btn-update">Update</button>
                <button type="button" class="btn-print">Cetak Invoice</button>
            </div>
        </form>
    </div>

    <div class="card-table">
        <h3>Data Piutang</h3>
        <table class="table-piutang">
            <thead>
                <tr>
                    <th>Tgl Issued</th>
                    <th>Insentif</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12/12/2004</td>
                    <td>2.500.000</td>
                    <td>Diberikan untuk kenangan</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
</x-layouts.app>
