<x-layouts.app title="Buku Bank">
<link rel="stylesheet" href="{{ asset('css/bukuBank.css') }}">

<section class="buku-bank-container">
    <div class="buku-bank-card">
        <h2 class="title">Buku Bank</h2>

        <div class="form-section">
            <div class="form-left">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal">

                <label for="bank">Bank</label>
                <select id="bank" name="bank">
                    <option value="">-- Pilih Bank --</option>
                    <option value="BCA">BCA</option>
                    <option value="BTN">BTN</option>
                    <option value="BNI">BNI</option>
                    <option value="MANDIRI">MANDIRI</option>
                    <option value="BRI">BRI</option>
                </select>

                <label for="debit">Debit</label>
                <input type="text" id="debit" name="debit" placeholder="Masukkan jumlah debit">

                <label for="kredit">Kredit</label>
                <input type="text" id="kredit" name="kredit" placeholder="Masukkan jumlah kredit">

                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="form-right">
                <button class="btn-action save">Simpan</button>
                <button class="btn-action delete">Hapus</button>
            </div>
        </div>

        <hr class="divider">

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Bank</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bukuBank as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->bank ?? '-' }}</td>
                            <td>{{ $item->debit }}</td>
                            <td>{{ $item->kredit }}</td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
</x-layouts.app>
