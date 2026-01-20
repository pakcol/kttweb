<x-layouts.app title="PPOB - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/pln.css') }}">

<section class="pln-section">
    <div class="card-container">
        <h2 class="form-title">PPOB</h2>
        @if ($errors->any())
            <div style="background:#ffdddd;padding:10px">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="ppobForm" action="{{ route('ppob.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="id_pel">ID PELANGGAN*</label>
                    <input type="text" id="id_pel" name="id_pel" class="form-control" placeholder="Masukkan No Pelanggan" required>
                </div>
                
                <div class="form-group">
                    <label for="jenis_ppob_id">KATEGORI PPOB*</label>
                    <select id="jenis_ppob_id" name="jenis_ppob_id" class="form-control" required>
                        <option value="">-- Pilih Kategori PPOB --</option>
                            @if(isset($jenisPpob) && $jenisPpob->count() > 0)
                                @foreach($jenisPpob as $jenis)
                                    @if($jenis->id != 5) {{-- sembunyikan jenis PPOB dengan id 5 --}}
                                        <option value="{{ $jenis->id }}">{{ $jenis->jenis_ppob }}</option>
                                    @endif
                                @endforeach
                            @else
                                <option value="" disabled>Data kategori ppob tidak ditemukan</option>
                            @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="harga_jual">HARGA JUAL*</label>
                    <input type="number" id="harga_jual" name="harga_jual" class="form-control" placeholder="Masukkan Nominal Harga Jual" required>
                </div>

                <div class="form-group">
                    <label for="nta">NTA*</label>
                    <input type="number" id="nta" name="nta" class="form-control" placeholder="Masukkan Transaksi" required>
                </div>
                <div class="form-group">
                    <label for="tgl">TANGGAL*</label>
                    <input type="date" id="tgl" name="tgl" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="jenis_bayar_id">JENIS PEMBAYARAN*</label>
                    <select id="jenis_bayar_id" name="jenis_bayar_id" class="form-control" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        @if(isset($jenisBayar) && $jenisBayar->count() > 0)
                            @foreach($jenisBayar as $jenis)
                                <option value="{{ $jenis->id }}">
                                    {{ $jenis->jenis }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Data jenis pembayaran tidak ditemukan</option>
                        @endif
                    </select>
                </div>

                <div class="form-group" id="bankContainer">
                    <label for="bank_id">BANK</label>
                    <select id="bank_id" name="bank_id" class="form-control">
                        <option value="">-- Pilih Bank --</option>
                        @if(isset($bank) && $bank->count() > 0)
                            @foreach($bank as $bk)
                                <option value="{{ $bk->id }}">{{ $bk->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group" id="namaPiutangContainer" style="display: none;">
                    <label for="nama_piutang">NAMA PIUTANG</label>
                    <input type="text" id="nama_piutang" name="nama_piutang" class="form-control">
                </div>
                
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-green">SAVE</button>
                <button type="reset" class="btn btn-red">CANCEL</button>
                <button type="button" class="btn btn-blue" onclick="openTopup()">TOP UP</button>
            </div>
        </form>
    </div>
    <!-- ===== TOP UP MODAL ===== -->
    <div id="topupModal" class="topup-modal">
        <div class="topup-modal-content">
            <h3>Top Up Saldo PPOB</h3>

            <form class="topup-form" method="POST" action="{{ route('ppob.topup') }}">
                @csrf
                <div class="form-group">
                    <label>Tanggal Top Up</label>
                    <input type="datetime-local"
                        name="tgl"
                        class="form-control"
                        value="{{ date('Y-m-d\TH:i') }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Nominal Top Up</label>
                    <input type="number"
                        name="nominal"
                        class="form-control"
                        placeholder="Masukkan nominal top up"
                        required>
                </div>
                <select id="jenis_bayar_id" name="jenis_bayar_id" style="display: none;">
                    <option value="1"></option>
                </select>

                <div class="form-group">
                    <label>Bank (sumber dana)</label>
                    <select name="bank_id" class="form-control" required>
                        <option value="">-- Pilih Bank --</option>
                        @foreach($bank as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="topup-button-group">
                    <button type="submit" class="btn btn-green">TOP UP</button>
                    <button type="button" class="btn btn-red" onclick="closeTopup()">BATAL</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <h2>Data PPOB</h2>
        <table class="table-pln">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>ID Pelanggan</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>NTA</th>
                    <th>Saldo</th>
                    <th>Metode Pembayaran</th>
                    <th>Bank</th>
                    <th>Nama Piutang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ppob as $index => $row)
                <tr>
                    <td>{{ $row->tgl }}</td>
                    <td>{{ $row->id_pel }}</td>
                    <td>{{ $row->jenisPpob->jenis_ppob ?? '-' }}</td>
                    <td>{{ number_format($row->harga_jual) }}</td>
                    <td>{{ number_format($row->nta) }}</td>
                    <td>{{ number_format($row->saldo) }}</td>
                    <td>{{ $row->jenisBayar->jenis ?? '-' }}</td>
                    <td>{{ $row->bank->name ?? '-' }}</td>
                    <td>{{ $row->nama_piutang ?? '-' }}</td>
                    <td>
                    <div style="
                        display:flex;
                        flex-direction:column;
                        gap:6px;
                        align-items:center;
                    ">
                        <button 
                            type="button"
                            class="btn btn-edit"
                            style="
                                background-color:#0d6efd;
                                color:#fff;
                                border:none;
                                width:72px;
                                padding:4px 0;
                                font-size:12px;
                            "
                            onclick='editPpob(@json($row))'>
                            Edit
                        </button>

                        <form action="{{ route('ppob.destroy', $row->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="btn btn-delete"
                                style="
                                    background-color:#dc3545;
                                    color:#fff;
                                    border:none;
                                    width:72px;
                                    padding:4px 0;
                                    font-size:12px;
                                "
                                onclick="return confirm('Hapus data ini?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const jenisSelect = document.getElementById('jenis_bayar_id');
        const bankContainer = document.getElementById('bankContainer');
        const bankInput = document.getElementById('bank_id');

        function toggleJenisPembayaran() {
            const jenis = jenisSelect.value;
            bankContainer.style.display = 'none';
            namaPiutangContainer.style.display = 'none';
            bankInput.required = false;

            if (jenis == 1) {
                bankContainer.style.display = 'block';
                bankInput.required = true;
            } else if (jenis == 3) {
                namaPiutangContainer.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            jenisSelect.addEventListener('change', toggleJenisPembayaran);
            toggleJenisPembayaran();
        });

    const topupModal = document.getElementById('topupModal');

    function openTopup() {
        topupModal.classList.add('show');
    }

    function closeTopup() {
        topupModal.classList.remove('show');
    }

    // Klik area gelap untuk close
    topupModal.addEventListener('click', function (e) {
        if (e.target === this) {
            closeTopup();
        }
    });

    function editPpob(data) {

    const form = document.getElementById('ppobForm');

    // ✅ URL SESUAI ROUTE
    form.action = "{{ url('ppob') }}/" + data.id;

    // ganti method POST → PUT
    document.getElementById('formMethod').value = 'PUT';

    // isi field
    document.getElementById('id_pel').value = data.id_pel;
    document.getElementById('jenis_ppob_id').value = data.jenis_ppob_id;
    document.getElementById('harga_jual').value = data.harga_jual;
    document.getElementById('nta').value = data.nta;
    document.getElementById('tgl').value = data.tgl.substring(0, 10);

    // jenis bayar
    if (data.jenis_bayar_id) {
        document.getElementById('jenis_bayar_id').value = data.jenis_bayar_id;
        toggleJenisPembayaran();
    }

    // bank
    if (data.bank_id) {
        document.getElementById('bank_id').value = data.bank_id;
    }

    // nama piutang
    if (data.nama_piutang) {
        document.getElementById('nama_piutang').value = data.nama_piutang;
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
    </script>
@if(session('success'))
<script>
    const form = document.getElementById('ppobForm');

    // balik ke CREATE mode
    form.action = "{{ route('ppob.store') }}";
    document.getElementById('formMethod').value = 'POST';

    // reset form
    form.reset();

    // reset conditional field
    document.getElementById('bankContainer').style.display = 'none';
    document.getElementById('namaPiutangContainer').style.display = 'none';
</script>
@endif


</section>
</x-layouts.app>
