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
                    <label for="nama">NAMA PELANGGAN*</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Pelanggan" required>
                </div>

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
                                    <option value="{{ $jenis->id }}">{{ $jenis->jenis_ppob }}</option>
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
                            @foreach($bank as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        @endif
                    </select>
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

        <div class="topup-form">
            <div class="form-group">
                <label>Jenis Top Up</label>
                <select class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option>Saldo PPOB</option>
                    <option>Saldo Agen</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nominal Top Up</label>
                <input type="number" class="form-control" placeholder="Masukkan nominal top up">
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" placeholder="Contoh: Top up saldo PPOB"></textarea>
            </div>

            <div class="topup-button-group">
                <button type="button" class="btn btn-green">TOP UP</button>
                <button type="button" class="btn btn-red" onclick="closeTopup()">BATAL</button>
            </div>
        </div>
    </div>
</div>

    <div class="table-container">
        <h2>Data PPOB</h2>
        <table class="table-pln">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>ID Pelanggan</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>NTA</th>
                    <th>Metode Pembayaran</th>
                    <th>Bank</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ppob as $index => $row)
                <tr>
                    <td>{{ $row->tgl }}</td>
                    <td>{{ $row->nota->nama ?? '-' }}</td>
                    <td>{{ $row->id_pel }}</td>
                    <td>{{ $row->ppobJenis->jenis_ppob ?? '-' }}</td>
                    <td>{{ number_format($row->harga_jual) }}</td>
                    <td>{{ number_format($row->nta) }}</td>
                    <td>{{ $row->nota->jenisBayar->jenis ?? '-' }}</td>
                    <td>{{ $row->nota->bank->name ?? '-' }}</td>
                    <td>
                        <button class="btn btn-edit" onclick='editPpob(@json($row))'>
                            Edit
                        </button>
                        <form action="{{ route('ppob.destroy', $row->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-delete" onclick="return confirm('Hapus data ini?')">
                                Delete
                            </button>
                        </form>
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
            bankInput.required = false;

            if (jenis == 1) {
                bankContainer.style.display = 'block';
                bankInput.required = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            jenisSelect.addEventListener('change', toggleJenisPembayaran);
            toggleJenisPembayaran();
        });

        function editPpob(data) {
            document.getElementById('ppobForm').action = `/ppob/${data.id}`;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('nama').value = data.nota?.nama ?? '';
            document.getElementById('id_pel').value = data.id_pel;
            document.getElementById('jenis_ppob_id').value = data.jenis_ppob_id;
            document.getElementById('harga_jual').value = data.harga_jual;
            document.getElementById('nta').value = data.nta;
            document.getElementById('tgl').value = data.tgl.substring(0,10);

            if (data.nota) {
                jenisSelect.value = data.nota.jenis_bayar_id;
                toggleJenisPembayaran(); 
                bankInput.value = data.nota.bank_id ?? '';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

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
    </script>


</section>
</x-layouts.app>
