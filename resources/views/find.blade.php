<x-layouts.app title="Find Ticket">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Ticket</title>
    <link rel="stylesheet" href="{{ asset('css/find.css') }}">
</head>
<body>
        <form action="{{ route('find.search') }}" method="GET" class="form-section">
            <div class="grid-container">
                <div class="left">
                    <div class="form-group">
                        <label for="tgl_flight">TANGGAL FLIGHT</label>
                        <input type="date" id="tgl_flight" name="tgl_flight" value="{{ request('tgl_flight') }}">
                    </div>

                    <div class="form-group">
                        <label for="tgl_issued">TANGGAL ISSUED</label>
                        <input type="date" id="tgl_issued" name="tgl_issued" value="{{ request('tgl_issued') }}">
                    </div>

                    <div class="form-group">
                        <label for="tgl_realisasi">TANGGAL REALISASI</label>
                        <input type="date" id="tgl_realisasi" name="tgl_realisasi" value="{{ request('tgl_realisasi') }}">
                    </div>
                </div>

                <div class="right">
                    <div class="form-group">
                        <label for="kode_booking">KODE BOOKING</label>
                        <div class="row">
                            <input type="text" id="kode_booking" name="kode_booking" value="{{ request('kode_booking') }}">
                            <button type="submit" class="btn">FIND</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_pax">NAMA PAX</label>
                        <div class="row">
                            <input type="text" id="nama_pax" name="nama_pax" value="{{ request('nama_pax') }}">
                            <button type="submit" class="btn">FIND</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_piutang">NAMA PIUTANG</label>
                        <div class="row">
                            <input type="text" id="nama_piutang" name="nama_piutang" value="{{ request('nama_piutang') }}">
                            <button type="submit" class="btn">FIND</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-reset" onclick="resetForm()">RESET</button>
            </div>
        </form>

    @if(isset($results))
    <div class="result-section">
        <div class="table-info">
            Menampilkan {{ $results->count() }} hasil pencarian
        </div>
        <div class="table-responsive">
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>No</th>
                        <th>Tgl Issued</th>
                        <th>Jam</th>
                        <th>Kode Booking</th>
                        <th>Airlines</th>
                        <th>Nama</th>
                        <th>Rute 1</th>
                        <th>Tgl Flight 1</th>
                        <th>Rute 2</th>
                        <th>Tgl Flight 2</th>
                        <th>Harga</th>
                        <th>NTA</th>
                        <th>Diskon</th>
                        <th>Komisi</th>
                        <th>Pembayaran</th>
                        <th>Nama Piutang</th>
                        <th>Tanggal Realisasi</th>
                        <th>Jam Realisasi</th>
                        <th>Nilai Refund</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $index => $r)
                    <tr>
                        <td><input type="checkbox" class="select-checkbox"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $r->tgl_issued ? \Carbon\Carbon::parse($r->tgl_issued)->format('d/m/Y') : '' }}</td>
                        <td>{{ $r->jam ?? '' }}</td>
                        <td>{{ $r->kode_booking ?? '' }}</td>
                        <td>{{ $r->airlines ?? '' }}</td>
                        <td>{{ $r->nama ?? '' }}</td>
                        <td>{{ $r->rute_1 ?? '' }}</td>
                        <td>{{ $r->tgl_flight_1 ? \Carbon\Carbon::parse($r->tgl_flight_1)->format('d/m/Y') : '' }}</td>
                        <td>{{ $r->rute_2 ?? '' }}</td>
                        <td>{{ $r->tgl_flight_2 ? \Carbon\Carbon::parse($r->tgl_flight_2)->format('d/m/Y') : '' }}</td>
                        <td class="text-right">{{ $r->harga ? number_format($r->harga, 0, ',', '.') : '0' }}</td>
                        <td class="text-right">{{ $r->nta ? number_format($r->nta, 0, ',', '.') : '0' }}</td>
                        <td class="text-right">{{ $r->diskon ? number_format($r->diskon, 0, ',', '.') : '0' }}</td>
                        <td class="text-right">{{ $r->komisi ? number_format($r->komisi, 0, ',', '.') : '0' }}</td>
                        <td>{{ $r->pembayaran ?? '' }}</td>
                        <td>{{ $r->nama_piutang ?? '' }}</td>
                        <td>{{ $r->tgl_realisasi ? \Carbon\Carbon::parse($r->tgl_realisasi)->format('d/m/Y') : '' }}</td>
                        <td>{{ $r->jam_realisasi ?? '' }}</td>
                        <td class="text-right">{{ $r->nilai_refund ? number_format($r->nilai_refund, 0, ',', '.') : '0' }}</td>
                        <td class="keterangan">{{ $r->keterangan ?? '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="21" class="no-data">Tidak ada data ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <script>
        function resetForm() {
            document.querySelectorAll('input[type="date"]').forEach(input => input.value = '');
            document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
            window.location.href = "{{ route('find.search') }}";
        }
        document.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value) {
                        document.querySelector('form').submit();
                    }
                });
            });
            const textInputs = document.querySelectorAll('input[type="text"]');
            textInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.querySelector('form').submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
</x-layouts.app>