<x-layouts.app title="Data Insentif - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Insentif</h2>

        <form id="formInsentif" method="POST" action="{{ route('insentif.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="tgl">TGL</label>
                    <input type="date" id="tgl" name="tgl" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Sumber Insentif</label>
                    <select name="sumber" id="sumber" required>
                        <option value="">-- Pilih --</option>
                        <option value="tiket">TIKET</option>
                        <option value="ppob">PPOB</option>
                    </select>
                </div>

                <div class="form-group" id="wrapJenisTiket" style="display:none;">
                    <label>Jenis Tiket</label>
                    <select name="jenis_tiket_id">
                        <option value="">-- Pilih Jenis Tiket --</option>
                        @foreach($jenisTiket as $jt)
                            <option value="{{ $jt->id }}">{{ $jt->name_jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="wrapJenisPpob" style="display:none;">
                    <label>Jenis PPOB</label>
                    <select name="jenis_ppob_id">
                        <option value="">-- Pilih Jenis PPOB --</option>
                        @foreach($jenisPpob as $jp)
                            <option value="{{ $jp->id }}">{{ $jp->jenis_ppob }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Insentif</label>
                    <input type="number" name="jumlah" placeholder="Masukkan jumlah insentif" required>
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
        <h3>Data Insentif</h3>
        <table class="table-piutang">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>Insentif</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insentif as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->tgl)->format('d/m/Y') }}</td>
                    <td>{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $row->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Belum ada data insentif</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
<script>
    document.getElementById('sumber').addEventListener('change', function () {
        const tiket = document.getElementById('wrapJenisTiket');
        const ppob  = document.getElementById('wrapJenisPpob');

        tiket.style.display = 'none';
        ppob.style.display  = 'none';

        if (this.value === 'tiket') {
            tiket.style.display = 'block';
        } else if (this.value === 'ppob') {
            ppob.style.display = 'block';
        }
    });
</script>
</x-layouts.app>
