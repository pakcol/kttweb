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
                @foreach($namaPiutangList as $nama)
                <option value="{{ $nama }}">
                @endforeach
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
                <tr onclick="selectRow(this)" data-id="{{ $p->id }}" data-nama-piutang="{{ $p->nama_piutang }}" data-harga="{{ $p->harga_jual }}">
                    <td>{{ $p->tgl ? \Carbon\Carbon::parse($p->tgl)->format('Y-m-d') : '' }}</td>
                    <td>{{ $p->tgl ? \Carbon\Carbon::parse($p->tgl)->format('H:i:s') : '' }}</td>
                    <td>{{ $p->id_pel }}</td>
                    <td>{{ number_format($p->harga_jual ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $p->transaksi }}</td>
                    <td>{{ $p->bayar }}</td>
                    <td>{{ $p->nama_piutang }}</td>
                    <td>{{ number_format($p->top_up ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($p->insentif ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($p->saldo ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $p->username }}</td>
                    <td>{{ $p->tgl_reralisasi ? \Carbon\Carbon::parse($p->tgl_reralisasi)->format('Y-m-d') : '' }}</td>
                    <td>{{ $p->jam_realisasi ? \Carbon\Carbon::parse($p->jam_realisasi)->format('H:i:s') : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
let selectedRow = null;
let allPiutangData = @json($piutang);

// Hitung total piutang berdasarkan nama piutang yang dipilih
function calculateTotalPiutang(namaPiutang) {
    if (!namaPiutang || namaPiutang === '') {
        document.getElementById('totalPiutang').value = '';
        return;
    }

    let total = 0;
    if (namaPiutang === 'ALL') {
        // Hitung total semua piutang
        allPiutangData.forEach(item => {
            total += parseInt(item.harga_jual) || 0;
        });
    } else {
        // Hitung total untuk nama piutang tertentu
        allPiutangData.forEach(item => {
            if (item.nama_piutang === namaPiutang) {
                total += parseInt(item.harga_jual) || 0;
            }
        });
    }

    document.getElementById('totalPiutang').value = new Intl.NumberFormat('id-ID').format(total);
}

// Event listener untuk perubahan nama piutang
document.getElementById('namaPiutang').addEventListener('input', function() {
    calculateTotalPiutang(this.value);
});

// Event listener untuk perubahan nama piutang (change event)
document.getElementById('namaPiutang').addEventListener('change', function() {
    calculateTotalPiutang(this.value);
});

function selectRow(row) {
    // Hapus selected class dari semua row
    document.querySelectorAll('#piutangTable tbody tr').forEach(r => {
        r.classList.remove('selected');
    });
    
    // Tambahkan selected class ke row yang dipilih
    row.classList.add('selected');
    selectedRow = row;
    
    const cells = row.getElementsByTagName('td');
    const rowData = row.dataset;

    const namaPiutang = cells[6].innerText.trim();
    const harga = cells[3].innerText.trim().replace(/\./g, '');
    const pembayaran = cells[5].innerText.trim();
    const nama = cells[10].innerText.trim(); 

    document.getElementById('namaPiutang').value = namaPiutang;
    document.getElementById('nama').value = nama;
    document.getElementById('harga').value = harga;
    calculateTotalPiutang(namaPiutang);

    const selectPembayaran = document.getElementById('pembayaran');
    selectPembayaran.value = pembayaran;
}

function updateData() {
    if (!selectedRow) {
        alert("Silakan pilih data dari tabel terlebih dahulu.");
        return;
    }

    const rowId = selectedRow.dataset.id;
    const namaPiutang = document.getElementById('namaPiutang').value;
    const nama = document.getElementById('nama').value;
    const harga = document.getElementById('harga').value;
    const pembayaran = document.getElementById('pembayaran').value;

    // Kirim update ke server
    fetch(`/plnPiutang/${rowId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nama_piutang: namaPiutang,
            harga_jual: harga.replace(/\./g, ''),
            bayar: pembayaran,
            username: nama
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message) {
            // Update tampilan tabel
            const cells = selectedRow.getElementsByTagName('td');
            cells[3].innerText = new Intl.NumberFormat('id-ID').format(parseInt(harga.replace(/\./g, '')));
            cells[5].innerText = pembayaran;
            cells[6].innerText = namaPiutang;
            cells[10].innerText = nama;
            
            // Update data di array
            const index = allPiutangData.findIndex(item => item.id == rowId);
            if (index !== -1) {
                allPiutangData[index].nama_piutang = namaPiutang;
                allPiutangData[index].harga_jual = parseInt(harga.replace(/\./g, ''));
                allPiutangData[index].bayar = pembayaran;
                allPiutangData[index].username = nama;
            }
            
            calculateTotalPiutang(namaPiutang);
            alert("Data berhasil diperbarui!");
        } else {
            alert("Gagal memperbarui data.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan saat memperbarui data.");
    });
}
</script>

<style>
.alert-dummy {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 15px;
}
.alert-dummy code {
    background: #f9f2f4;
    color: #c7254e;
    padding: 2px 5px;
    border-radius: 4px;
}
</style>

</x-layouts.app>
