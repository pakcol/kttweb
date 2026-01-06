<x-layouts.app title="Rekap Penjuaan - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/cash-flow.css') }}">

<div class="cash-flow-wrapper">
    <div class="cash-flow-card">
        <h2 class="page-title">REKAP PENJUALAN</h2>
        <form action="{{ route('rekap-penjualan.rekap') }}" method="GET" class="search-form">
            <label for="tanggal">LAPORAN PENJUALAN TANGGAL :</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" readonly>
        </form>
        <form action="{{ route('rekap-penjualan.rekapPenjualan') }}" method="POST" id="formRekapPenjualan">
            @csrf
            <input type="hidden" name="id" id="recordId">

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
                    <label>REFUND</label>
                    <input type="text" name="REFUND" id="REFUND">
                </div>
                <div class="card">
                    <h3>TRANSFER</h3>
                    @foreach($banks as $bank)
                        <label>TRANSFER {{ strtoupper($bank->name) }}</label>
                        <input type="text"
                            value="{{ number_format($transfer[$bank->id] ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                </div>
                <div class="card">
                    <h3>SETORAN</h3>
                    @foreach($banks as $bank)
                        <label>SETORAN {{ strtoupper($bank->name) }}</label>
                        <input type="text"
                            value="{{ number_format($setoran[$bank->id] ?? 0, 0, ',', '.') }}">
                    @endforeach
                </div>
                <div class="card">
                    <h3>SALDO TIKET</h3>
                    @foreach($jenisTiket as $tiket)
                        <label>{{ strtoupper($tiket->name_jenis) }}</label>
                        <input type="text"
                            value="{{ number_format($tiket->saldo ?? 0, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                </div>
                <div class="card">
                    <h3>TOP UP SALDO TIKET</h3>
                    @foreach($topupJenisTiket as $topup)
                        <label>{{ strtoupper($topup->name_jenis) }}</label>
                        <input type="text"
                            value="{{ number_format($topup->total_topup, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                </div>
                <div class="card sub-agent">
                    <h3>SALDO SUB AGENT</h3>
                    @foreach($subagents as $sa)
                        <label>{{ strtoupper($sa->nama) }}</label>
                        <input type="text"
                            value="{{ number_format($sa->saldo, 0, ',', '.') }}"
                            readonly>
                    @endforeach
                    <label>CASH</label>
                    <input type="text"
                        value="{{ number_format($cashFlowSubagent ?? 0, 0, ',', '.') }}"
                        readonly>
                </div>
                <div class="card pln">
                    <h3>PPOB</h3>
                    @forelse($ppobs as $ppob)
                        <label>{{ strtoupper($ppob->jenis_ppob) }}</label>
                        <input type="text"
                            value="{{ number_format($ppob->saldo ?? 0, 0, ',', '.') }}"
                            readonly>
                    @empty
                        <p class="no-data">Data PPOB belum tersedia</p>
                    @endforelse
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
