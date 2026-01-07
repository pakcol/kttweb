<x-layouts.app title="Rekap Penjualan - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/cash-flow.css') }}">

<div class="cash-flow-wrapper">
    <div class="cash-flow-card">
        <h2 class="page-title">REKAP PENJUALAN</h2>

        {{-- FILTER TANGGAL --}}
        <form action="{{ route('rekap-penjualan.index') }}" method="GET" class="search-form">
            <label for="tanggal_awal">LAPORAN PENJUALAN TANGGAL :</label>
            <input type="date" name="tanggal_awal" value="{{ $tanggalAwal ?? date('Y-m-d') }}">
            <label for="tanggal_akhir">s/d</label>
            <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir ?? date('Y-m-d') }}">
            <button type="submit" class="search-btn">CARI</button>
        </form>

        {{-- RINGKASAN --}}
        <div class="form-grid">
            <div class="card">
                <h3>PENJUALAN</h3>
                <label>PENJUALAN</label>
                <input type="text"
                    value="{{ number_format($TTL_PENJUALAN ?? 0,0,',','.') }}"
                    readonly>

                <label>PIUTANG</label>
                <input type="text"
                    value="{{ number_format($PIUTANG ?? 0,0,',','.') }}"
                    readonly>

                <label>PENGELUARAN</label>
                <input type="text"
                    value="{{ number_format($BIAYA ?? 0,0,',','.') }}"
                    readonly>
            </div>

            <div class="card">
                <h3>TRANSFER</h3>
                @if(isset($banks) && count($banks) > 0)
                    @foreach($banks as $bank)
                        <label>TRANSFER {{ strtoupper($bank->name) }}</label>
                        <input type="text"
                            value="{{ number_format($transfer[$bank->id] ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                @else
                    <p class="no-data">Data bank tidak tersedia</p>
                @endif
            </div>

            <div class="card">
                <h3>SALDO TIKET</h3>
                @if(isset($jenisTiket) && count($jenisTiket) > 0)
                    @foreach($jenisTiket as $tiket)
                        <label>{{ strtoupper($tiket->name_jenis) }}</label>
                        <input type="text"
                            value="{{ number_format($tiket->saldo ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                @else
                    <p class="no-data">Data tiket tidak tersedia</p>
                @endif
            </div>

            <div class="card sub-agent">
                <h3>SALDO SUB AGENT</h3>
                @if(isset($subagents) && count($subagents) > 0)
                    @foreach($subagents as $sa)
                        <label>{{ strtoupper($sa->nama) }}</label>
                        <input type="text"
                            value="{{ number_format($sa->saldo ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                @else
                    <p class="no-data">Data sub agent tidak tersedia</p>
                @endif
            </div>

            <div class="card pln">
                <h3>PPOB</h3>
                @if(isset($ppobs) && count($ppobs) > 0)
                    @foreach($ppobs as $ppob)
                        <label>{{ strtoupper($ppob->jenis_ppob) }}</label>
                        <input type="text"
                            value="{{ number_format($ppob->saldo ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                @else
                    <p class="no-data">Data PPOB belum tersedia</p>
                @endif
            </div>
        </div>

        {{-- TABEL DETAIL (OPSIONAL) --}}
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
                        <td>{{ number_format($p->TTL_PENJUALAN,0,',','.') }}</td>
                        <td>{{ number_format($p->PIUTANG,0,',','.') }}</td>
                        <td>{{ number_format($p->REFUND,0,',','.') }}</td>
                        <td>{{ number_format($p->CASH_FLOW,0,',','.') }}</td>
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
const rows = document.querySelectorAll('.table-laporan tbody tr');
if (rows.length) {
    rows.forEach(row => {
        row.addEventListener('click', function () {
            const data = JSON.parse(this.dataset.record);
            console.log('Detail Penjualan:', data);
        });
    });
}
</script>

</x-layouts.app>