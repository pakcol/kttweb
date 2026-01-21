<x-layouts.app title="Rekap Penjualan - PT. Kupang Tour & Travel">

<link rel="stylesheet" href="{{ asset('css/rekapan.css') }}">

<section class="page-bg">
    <div class="main-card">

    {{-- FILTER BAR --}}
    <form action="{{ route('rekap-penjualan.index') }}" method="GET" class="rekap-filter-bar">
        <div class="filter-group">
            <label>DARI TANGGAL</label>
            <input type="date" name="tanggal_awal" value="{{ $tanggalAwal ?? date('Y-m-d') }}">
        </div>

        <div class="filter-group">
            <label>SAMPAI TANGGAL</label>
            <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir ?? date('Y-m-d') }}">
        </div>

        <div class="filter-group">
    <label>JENIS DATA</label>
    <select name="jenis_data" class="jenis-data-select">
        <option value="tiket" {{ request('jenis_data','tiket') == 'tiket' ? 'selected' : '' }}>
            TIKET
        </option>
        <option value="ppob" {{ request('jenis_data') == 'ppob' ? 'selected' : '' }}>
            PPOB
        </option>
    </select>
</div>

<style>
.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #003b4f;
    white-space: nowrap;
}

.filter-group .jenis-data-select {
    height: 38px;                 
    min-width: 90px;
    padding: 0 12px;

    border-radius: 8px;
    border: 1.5px solid #cfdde5;
    background: #ffffff;

    font-size: 14px;
    font-weight: 600;
    color: #000;

    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-group .jenis-data-select:focus {
    outline: none;
    border-color: #3aa0ff;
    box-shadow: 0 0 0 3px rgba(58, 160, 255, 0.25);
}
</style>


        <button class="btn-tampil">TAMPIL</button>
                <a href="{{ route('rekap-penjualan.export', request()->query()) }}"
        class="btn-excel">
            EXPORT EXCEL
        </a>


        
    </form>

    {{-- PANEL UTAMA --}}
    <div class="rekap-grid">

        {{-- PENJUALAN --}}
        <div class="rekap-panel">
            <h4>PENJUALAN</h4>

            @foreach($penjualan['tiket'] as $nama => $row)
                <div class="row-input">
                    <span>{{ $nama }}</span>
                    <input readonly value="{{ number_format($row['penjualan'],0,',','.') }}">
                </div>
            @endforeach
        </div>

        {{-- NTA --}}
        <div class="rekap-panel">
            <h4>NTA</h4>

            @foreach($penjualan['tiket'] as $nama => $row)
                <div class="row-input">
                    <span>{{ $nama }}</span>
                    <input readonly value="{{ number_format($row['nta'],0,',','.') }}">
                </div>
            @endforeach

            @foreach($penjualan['ppob'] as $nama => $row)
                <div class="row-input">
                    <span>{{ $nama }}</span>
                    <input readonly value="{{ number_format($row['nta'],0,',','.') }}">
                </div>
            @endforeach
        </div>


        {{-- NTA SUMMARY --}}
        <div class="rekap-panel">
            <h4>NTA</h4>

            <div class="row-input">
                <span>PENJUALAN</span>
                <input readonly value="{{ number_format($TTL_PENJUALAN,0,',','.') }}">
            </div>

            <div class="row-input">
                <span>NTA</span>
                <input readonly value="{{ number_format($TTL_NTA,0,',','.') }}">
            </div>

            <div class="row-input">
        <span>DISKON</span>
        <input readonly value="{{ number_format($TTL_DISKON ?? 0,0,',','.') }}">
    </div>

    <div class="row-input">
        <span>BIAYA</span>
        <input readonly value="{{ number_format($TTL_BIAYA ?? 0,0,',','.') }}">
    </div>

            <div class="row-input">
                <span>RUGI / LABA</span>
                <input readonly value="{{ number_format($TTL_PENJUALAN - $TTL_NTA,0,',','.') }}">
            </div>
        </div>

        <div class="rekap-panel small">
            <h4>PENJUALAN PPOB</h4>
            @foreach($penjualan['ppob'] as $nama => $row)
                <div class="row-input">
                    <span>{{ $nama }}</span>
                    <input readonly value="{{ number_format($row['penjualan'],0,',','.') }}">
                </div>
            @endforeach
        </div>

        <div class="rekap-panel small">
            <h4>NTA PPOB</h4>
            @foreach($penjualan['ppob'] as $nama => $row)
                <div class="row-input">
                    <span>{{ $nama }}</span>
                    <input readonly value="{{ number_format($row['nta'],0,',','.') }}">
                </div>
            @endforeach
        </div>

        <div class="rekap-panel small">
            <h4>PPOB</h4>
            <div class="row-input">
                <span>Sisa Saldo</span>
                <input readonly value="{{ number_format($SISA_SALDO_PPOB,0,',','.') }}">
            </div>
        </div>

    </div>


    {{-- TABEL DATABASE (DITAMBAHKAN) --}}
    @if(isset($tableData) && $tableData->count())
    <div class="rekap-table-wrapper">
        <table class="rekap-table">
            <thead>
                <tr>
                    <th>TGL ISSU</th>
                    <th>JAM</th>
                    <th>KODE</th>

                    @if($jenisData === 'tiket')
                        <th>JENIS TIKET</th>
                        <th>NAMA</th>
                        <th>RUTE</th>
                        <th>TGL FLIGHT</th>
                        <th>RUTE 2</th>
                        <th>TGL FLIGHT 2</th>

                    @else
                        <th>KATEGORI PPOB</th>
                    @endif

                    <th>HARGA</th>
                    <th>NTA</th>
                    <th>PEMBAYARAN</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tableData as $row)
                <tr>
                    <td>{{ $row->tgl_issu }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ $row->kode_booking }}</td>

                    @if($jenisData === 'tiket')
                        <td>{{ $row->jenis_tiket }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->rute }}</td>
                        <td>{{ $row->tgl_flight }}</td>
                        <td>{{ $row->rute2 ?? '-' }}</td>
                        <td>{{ $row->tgl_flight2 ?? '-' }}</td>

                    @else
                        <td>{{ $row->kategori }}</td>
                    @endif

                    <td class="right">{{ number_format($row->harga,0,',','.') }}</td>
                    <td class="right">{{ number_format($row->nta,0,',','.') }}</td>
                    <td>{{ strtoupper($row->pembayaran ?? '-') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

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