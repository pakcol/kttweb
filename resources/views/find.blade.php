<x-layouts.app title="Find Ticket">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Ticket</title>
    <link rel="stylesheet" href="{{ asset('css/find.css') }}">
</head>
<body>
        <form action="{{ route('find.searchTiket') }}" method="GET" class="form-section">
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

    @if(isset($ticket))
<div class="modal-overlay" id="resultModal">
    <div class="modal-box">

        <div class="modal-header">
            <h3>Hasil Pencarian Tiket</h3>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>

        <div class="modal-info">
            Menampilkan {{ $ticket->count() }} hasil pencarian
        </div>

        <div class="modal-body">
            <div class="table-responsive">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>No</th>
                            <th>Tgl Issued</th>
                            <th>Kode Booking</th>
                            <th>Nama</th>
                            <th>Rute</th>
                            <th>Tgl Flight</th>
                            <th>Rute 2</th>
                            <th>Tgl Flight 2</th>
                            <th>Harga Jual</th>
                            <th>NTA</th>
                            <th>Diskon</th>
                            <th>Status</th>
                            <th>Jenis Tiket</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticket as $index => $t)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $t->tgl_issued ? \Carbon\Carbon::parse($t->tgl_issued)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $t->kode_booking }}</td>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->rute }}</td>
                            <td>{{ $t->tgl_flight ? \Carbon\Carbon::parse($t->tgl_flight)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $t->rute2 ?? '-' }}</td>
                            <td>{{ $t->tgl_flight2 ? \Carbon\Carbon::parse($t->tgl_flight2)->format('Y-m-d') : '-' }}</td>
                            <td>{{ number_format($t->harga_jual ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($t->nta ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $t->diskon ? number_format($t->diskon, 0, ',', '.') : '-' }}</td>
                            <td>
                                <span class="status {{ $t->status }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td>{{ $t->jenisTiket->name_jenis ?? '-' }}</td>
                            <td>{{ $t->keterangan ?? '-' }}</td>
                            <td>
                                <form action="{{ route('input-tiket.destroy', $t->kode_booking) }}" method="POST"
                                      onsubmit="return confirm('Hapus tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" style="text-align:center;">Data tiket tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endif

    <script>
        function resetForm() {
            document.querySelectorAll('input[type="date"]').forEach(input => input.value = '');
            document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
            window.location.href = "{{ route('find.searchTiket') }}";
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