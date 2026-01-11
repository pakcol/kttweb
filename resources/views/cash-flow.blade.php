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

        <h2 class="page-title">
            {{ $fromInputKas ? 'TUTUP KAS' : 'CASH FLOW' }}
        </h2>


        {{-- FILTER --}}
        <form method="GET" class="search-form">
            <label>
                {{ $fromInputKas ? 'TUTUP KAS TANGGAL' : 'LAPORAN PENJUALAN TANGGAL' }}
            </label>

            <input 
                type="date"
                name="tanggal"
                value="{{ $fromInputKas ? date('Y-m-d') : request('tanggal', date('Y-m-d')) }}"
                {{ $fromInputKas ? 'readonly' : '' }}
            >

            @if(!$fromInputKas)
                <button type="submit">CARI</button>
            @endif
        </form>

        {{-- GRID --}}
        <form method="POST">
            @csrf

            <div class="form-grid cashflow-grid">

                {{-- BARIS 1 --}}
                <div class="card penjualan">
                    <h3>PENJUALAN</h3>
                    <label>PENJUALAN</label>
                    <input readonly value="{{ number_format($TTL_PENJUALAN,0,',','.') }}">
                    <label>PIUTANG</label>
                    <input readonly value="{{ number_format($PIUTANG,0,',','.') }}">
                    <label>PENGELUARAN</label>
                    <input readonly value="{{ number_format($BIAYA,0,',','.') }}">
                    <label>REFUND</label>
                    <input readonly value="0">
                </div>

                <div class="card saldo-airlines">
                    <h3>SALDO AIRLINES</h3>

                    @foreach($jenisTiket as $jt)
                        <label>{{ strtoupper($jt->name_jenis) }}</label>
                        <input readonly value="{{ number_format($jt->saldo,0,',','.') }}">
                    @endforeach
                </div>

                <div class="card topup-airlines">
                    <h3>TOP UP AIRLINES</h3>

                    @foreach($topupJenisTiket as $jt)
                        <label>{{ strtoupper($jt->name_jenis) }}</label>
                        <input readonly value="{{ number_format($jt->total_topup,0,',','.') }}">
                    @endforeach
                </div>

                {{-- BARIS 2 --}}
                <div class="card transfer">
                    <h3>TRANSFER</h3>

                    @foreach($banks as $bank)
                        <label>TRANSFER {{ strtoupper($bank->name) }}</label>
                        <input readonly value="{{ number_format($transfer[$bank->id] ?? 0,0,',','.') }}">
                    @endforeach
                </div>

                <div class="card pln">
                <h3>PPOB</h3>

                <label>TOTAL PENJUALAN</label>
                <input readonly value="{{ number_format($TOTAL_PENJUALAN_PPOB,0,',','.') }}">

                <div class="sisa-saldo-box">
                    <label>SISA SALDO</label>
                    <input readonly value="{{ number_format($SISA_SALDO_PPOB,0,',','.') }}">
                </div>
            </div>

                <div class="card sub-agent">
                    <h3>SALDO SUB AGENT</h3>

                    @foreach($subagents as $sa)
                        <label>{{ strtoupper($sa->nama) }}</label>
                        <input readonly value="{{ number_format($sa->saldo,0,',','.') }}">
                    @endforeach
                </div>


                {{-- BARIS 3 --}}
                <div class="card setoran">
                    <h3>SETORAN</h3>

                    @foreach($banks as $bank)
                        <label>SETORAN {{ strtoupper($bank->name) }}</label>
                        <input type="number" value="">
                    @endforeach
                </div>

                <div class="card cash">
                    <h3>CASH</h3>
                    <input readonly value="{{ number_format($CASH_FLOW,0,',','.') }}">
                </div>

            </div>

            <div class="button-container">
                <button class="btn red" type="reset" onclick="resetSetoran()"><script>
                    function resetSetoran() {
                        const setoranInputs = document.querySelectorAll('.card.setoran input');

                        setoranInputs.forEach(input => {
                            input.value = '';
                        });
                    }
                </script>BATAL</button>
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
    document.addEventListener('DOMContentLoaded', function () {

        const cashAwal = {{ $CASH_FLOW }};
        const cashInput = document.querySelector('.card.cash input');
        const setoranInputs = document.querySelectorAll('.card.setoran input');

        function hitungCash() {
            let totalSetoran = 0;

            setoranInputs.forEach(input => {
                const val = parseInt(input.value.replace(/\D/g, '')) || 0;
                totalSetoran += val;
            });

            const cashAkhir = cashAwal - totalSetoran;
            cashInput.value = cashAkhir.toLocaleString('id-ID');
        }

        setoranInputs.forEach(input => {

            // realtime
            input.addEventListener('input', hitungCash);

            // ENTER tidak submit form
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    hitungCash();
                }
            });
        });

    });
</script>


</x-layouts.app>