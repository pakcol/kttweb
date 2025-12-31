<x-layouts.app title="Data Biaya - PT. Kupang Tour & Travel">
    <link rel="stylesheet" href="{{ asset('css/biaya.css') }}">

    <div class="biaya-page">
    <section class="biaya-section">
        <div class="form-container">
            <h2>Input Data Biaya</h2>

            {{-- PESAN ERROR --}}
            @if(session('error'))
                <div style="background:#ffe0e0; color:#900; padding:10px; margin-bottom:15px;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div style="background:#e0ffe0; color:#060; padding:10px; margin-bottom:15px;">
                    {{ session('success') }}
                </div>
            @endif


            <form action="{{ route('biaya.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="tgl" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label>Biaya</label>
                    <input type="number" name="biaya" required>
                </div>

                <div class="form-group">
                    <label>Jenis Pembayaran</label>
                    <select name="jenis_bayar_id" id="jenis_bayar_id" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        @foreach($jenisBayar as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="bankContainer" style="display:none;">
                    <label>Bank</label>
                    <select name="bank_id" id="bank_id">
                        <option value="">-- Pilih Bank --</option>
                        @foreach($bank as $b)
                            <option value="{{ $b->id }}">
                                {{ $b->name }} (Saldo: Rp {{ number_format($b->saldo,0,',','.') }})
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group" id="bankInfo" style="display:none;">
                    <label>Bank</label>
                    <input type="text" id="bankName" readonly>

                    <label>Saldo Saat Ini</label>
                    <input type="text" id="bankSaldo" readonly>
                </div>


                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan"></textarea>
                </div>

                <button class="btn-simpan" type="submit">Simpan</button>
            </form>


        </div>

        <div class="table-container">
            <h2>Data Biaya</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th>Jenis Bayar</th>
                        <th>Bank</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biaya as $row)
                    <tr>
                        <td>{{ $row->tgl->format('d-m-Y H:i') }}</td>
                        <td>Rp {{ number_format($row->biaya,0,',','.') }}</td>
                        <td>{{ $row->jenisBayar->jenis }}</td>
                        <td>{{ $row->bank->name ?? '-' }}</td>
                        <td>{{ $row->keterangan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </section>
    <script>
        const jenis = document.getElementById('jenis_bayar_id');
        const bankBox = document.getElementById('bankContainer');
        const bankSelect = document.getElementById('bank_id');

        jenis.addEventListener('change', function () {
            if (this.value == '2') {
                bankBox.style.display = 'none';
                bankSelect.value = '';
                bankSelect.required = false;
            } else {
                bankBox.style.display = 'block';
                bankSelect.required = true;
            }
        });
    </script>
</x-layouts.app>
