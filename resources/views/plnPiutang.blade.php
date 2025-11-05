<x-layouts.app title="PLN - Piutang">
<link rel="stylesheet" href="{{ asset('css/plnPiutang.css') }}">

<section class="pln-piutang-container">
    <div class="piutang-form">
        <div class="form-left">
            {{-- NAMA PIUTANG --}}
            <label>Nama Piutang</label>
            <input list="namaPiutangList" id="namaPiutang" placeholder="Ketik atau pilih nama piutang...">
            <datalist id="namaPiutangList">
                <option value="ALL">
                <option value="OTE">
                <option value="OYU">
                <option value="YATI">
            </datalist>

            {{-- TOTAL PIUTANG --}}
            <label>Total Piutang</label>
            <input type="text" id="totalPiutang" readonly>

            {{-- NAMA --}}
            <label>Nama</label>
            <input type="text" id="nama" readonly>

            {{-- HARGA --}}
            <label>Harga</label>
            <input type="text" id="harga" readonly>

            {{-- PEMBAYARAN --}}
            <label>Pembayaran</label>
            <select id="pembayaran">
                <option value="BCA">BCA</option>
                <option value="BNI">BNI</option>
                <option value="MANDIRI">MANDIRI</option>
                <option value="BRI">BRI</option>
                <option value="LUNAS">LUNAS</option>
            </select>
        </div>

        <div class="form-right">
            <button id="updateBtn" onclick="updateData()">UPDATE</button>
        </div>
    </div>

    <div class="table-section">
        <table id="piutangTable">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>JAM</th>
                    <th>ID_PEL</th>
                    <th>HARGA_JUAL</th>
                    <th>TRANSAKSI</th>
                    <th>BAYAR</th>
                    <th>NAMA_PIUTANG</th>
                    <th>TOP_UP</th>
                    <th>INSENTIF</th>
                    <th>SALDO</th>
                    <th>USR</th>
                    <th>TGL_REALISASI</th>
                    <th>JAM_REALISASI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($piutang as $p)
                <tr onclick="selectRow(this)">
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->jam }}</td>
                    <td>{{ $p->id_pel }}</td>
                    <td>{{ $p->harga_jual }}</td>
                    <td>{{ $p->transaksi }}</td>
                    <td>{{ $p->bayar }}</td>
                    <td>{{ $p->nama_piutang }}</td>
                    <td>{{ $p->top_up }}</td>
                    <td>{{ $p->insentif }}</td>
                    <td>{{ $p->saldo }}</td>
                    <td>{{ $p->usr }}</td>
                    <td>{{ $p->tgl_realisasi }}</td>
                    <td>{{ $p->jam_realisasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
let selectedRow = null;

function selectRow(row) {
    selectedRow = row;
    const cells = row.getElementsByTagName('td');

    const namaPiutang = cells[6].innerText.trim();
    const harga = cells[3].innerText.trim();
    const pembayaran = cells[5].innerText.trim();
    const nama = cells[10].innerText.trim(); 

    document.getElementById('namaPiutang').value = namaPiutang;
    document.getElementById('nama').value = nama;
    document.getElementById('harga').value = harga;

    const selectPembayaran = document.getElementById('pembayaran');
    selectPembayaran.value = pembayaran;
}

function updateData() {
    if (!selectedRow) {
        alert("Silakan pilih data dari tabel terlebih dahulu.");
        return;
    }

    const namaPiutang = document.getElementById('namaPiutang').value;
    const nama = document.getElementById('nama').value;
    const harga = document.getElementById('harga').value;
    const pembayaran = document.getElementById('pembayaran').value;

    const cells = selectedRow.getElementsByTagName('td');
    cells[3].innerText = harga;
    cells[5].innerText = pembayaran;
    cells[6].innerText = namaPiutang;
    cells[10].innerText = nama;

    alert("Data berhasil diperbarui (simulasi).");
}
</script>

</x-layouts.app>
