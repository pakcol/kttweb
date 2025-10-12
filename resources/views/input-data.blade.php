{{-- resources/views/input-data.blade.php --}}
<x-layouts.app title="Input Data - PT. Kupang Tour & Travel">
    <section class="input-data-section py-5">
        <div class="container-fluid">
            <div class="header text-center mb-4">
                <h2 class="fw-bold text-primary">INPUT DATA</h2>
                <p class="text-muted">PT. Kupang Tour & Travel</p>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form Input --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="inputDataForm" method="POST">
                        @csrf
                        <input type="hidden" id="formMethod" name="_method" value="POST">
                        <input type="hidden" id="tiketId" name="tiket_id">

                        <div class="row g-4">
                            {{-- KIRI --}}
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="fw-semibold mb-3 text-primary">TGL ISSUED</h5>
                                    <div class="mb-3">
                                        <label for="tgl_issued" class="form-label">Tanggal Issued</label>
                                        <input type="date" class="form-control" id="tgl_issued" name="tgl_issued"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kode_booking" class="form-label">Kode Booking</label>
                                        <input type="text" class="form-control text-uppercase" id="kode_booking"
                                            name="kode_booking" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="airlines" class="form-label">Airlines</label>
                                        <select class="form-control text-uppercase" id="airlines" name="airlines" required>
                                            <option value="">Pilih Airlines</option>
                                            <option value="GARUDA">GARUDA</option>
                                            <option value="LION">LION</option>
                                            <option value="CITILINK">CITILINK</option>
                                            <option value="SRIWIJAYA">SRIWIJAYA</option>
                                            <option value="BATIK">BATIK</option>
                                            <option value="AIRASIA">AIRASIA</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control text-uppercase" id="nama" name="nama" required>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h5 class="fw-semibold mb-3 text-primary">RUTE</h5>
                                    <div class="mb-3">
                                        <label for="rute1" class="form-label">Rute 1</label>
                                        <input type="text" class="form-control text-uppercase" id="rute1" name="rute1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tgl_flight1" class="form-label">Tanggal Flight 1</label>
                                        <input type="date" class="form-control" id="tgl_flight1" name="tgl_flight1"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rute2" class="form-label">Rute 2</label>
                                        <input type="text" class="form-control text-uppercase" id="rute2" name="rute2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tgl_flight2" class="form-label">Tanggal Flight 2</label>
                                        <input type="date" class="form-control" id="tgl_flight2" name="tgl_flight2">
                                    </div>
                                </div>
                            </div>

                            {{-- KANAN --}}
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="fw-semibold mb-3 text-primary">HARGA</h5>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="number" class="form-control" id="harga" name="harga" value="0" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nta" class="form-label">NTA</label>
                                        <input type="number" class="form-control" id="nta" name="nta" value="0" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <input type="number" class="form-control" id="diskon" name="diskon" value="0" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="komisi" class="form-label">Komisi</label>
                                        <input type="number" class="form-control" id="komisi" name="komisi" value="0" step="0.01" readonly>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h5 class="fw-semibold mb-3 text-primary">PEMBAYARAN</h5>
                                    <div class="mb-3">
                                        <label for="pembayaran" class="form-label">Pembayaran</label>
                                        <select class="form-control text-uppercase" id="pembayaran" name="pembayaran" required>
                                            <option value="">Pilih Pembayaran</option>
                                            <option value="CASH">CASH</option>
                                            <option value="BCA">BCA</option>
                                            <option value="BNI">BNI</option>
                                            <option value="MANDIRI">MANDIRI</option>
                                            <option value="BRI">BRI</option>
                                            <option value="PIUTANG">PIUTANG</option>
                                            <option value="REFUND">REFUND</option>
                                            <option value="FLIGHT CANCEL">FLIGHT CANCEL</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_piutang" class="form-label">Nama Piutang</label>
                                        <input type="text" class="form-control text-uppercase" id="nama_piutang" name="nama_piutang">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tgl_realisasi" class="form-label">Tanggal Realisasi</label>
                                        <input type="date" class="form-control" id="tgl_realisasi" name="tgl_realisasi">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nilai_refund" class="form-label">Nilai Refund</label>
                                        <input type="number" class="form-control" id="nilai_refund" name="nilai_refund" value="0" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control text-uppercase" id="keterangan" name="keterangan" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                            <button type="submit" class="btn btn-primary" id="btnSimpan">SIMPAN</button>
                            <button type="button" class="btn btn-secondary" id="btnBatal">BATAL</button>
                            <button type="button" class="btn btn-danger d-none" id="btnHapus">HAPUS</button>
                            <button type="button" class="btn btn-info" id="btnCari">CARI</button>
                            <button type="button" class="btn btn-warning" id="btnTutupKas">TUTUP KAS</button>
                            <button type="button" class="btn btn-success" id="btnCetakInvoice">CETAK INVOICE</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="fw-semibold mb-0 text-primary">DATA TIKET</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="tiketTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>TGL ISSUED</th>
                                    <th>JAM</th>
                                    <th>KODE BOOKING</th>
                                    <th>AIRLINES</th>
                                    <th>NAMA</th>
                                    <th>RUTE 1</th>
                                    <th>TGL FLIGHT1</th>
                                    <th>RUTE 2</th>
                                    <th>TGL FLIGHT2</th>
                                    <th>HARGA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tikets as $tiket)
                                    <tr data-id="{{ $tiket->id }}" style="cursor:pointer">
                                        <td>{{ $tiket->tgl_issued->format('d/m/Y') }}</td>
                                        <td>{{ $tiket->jam }}</td>
                                        <td>{{ $tiket->kode_booking }}</td>
                                        <td>{{ $tiket->airlines }}</td>
                                        <td>{{ $tiket->nama }}</td>
                                        <td>{{ $tiket->rute1 }}</td>
                                        <td>{{ $tiket->tgl_flight1->format('d/m/Y') }}</td>
                                        <td>{{ $tiket->rute2 ?? '-' }}</td>
                                        <td>{{ $tiket->tgl_flight2 ? $tiket->tgl_flight2->format('d/m/Y') : '-' }}</td>
                                        <td>Rp {{ number_format($tiket->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Cari --}}
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="searchForm" action="{{ route('input-data.search') }}" method="GET">
                        <div class="mb-3">
                            <label for="searchInput" class="form-label">Masukkan Kode Booking atau Nama</label>
                            <input type="text" class="form-control" id="searchInput" name="search" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" id="btnSearchSubmit">Cari</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Link CSS --}}
    <link rel="stylesheet" href="{{ asset('css/input-data.css') }}">
    @push('scripts')
        {{-- (gunakan script JavaScript kamu yang sudah ada tanpa diubah, masih kompatibel) --}}
    @endpush
</x-layouts.app>
