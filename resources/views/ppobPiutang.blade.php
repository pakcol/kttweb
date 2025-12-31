<x-layouts.app title="PLN - Piutang">
<link rel="stylesheet" href="{{ asset('css/plnPiutang.css') }}">

<section class="pln-piutang-container">
    <div class="piutang-form">
        <div class="form-left">
            {{-- NAMA PIUTANG --}}
            <label>Nama Piutang</label>
            <input 
                list="nama_piutang_list"
                id="nama_piutang"
                name="nama_piutang"
                placeholder="Ketik atau pilih nama piutang..."
            >

            <datalist id="nama_piutang_list">
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
                <table class="pln-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>ID Pelanggan</th>
                            <th>Kategori PPOB</th>
                            <th>Harga</th>
                            <th>Jenis Bayar</th>
                            <th>Bank</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ppob as $row)
                        <tr onclick="selectRow(this)"
                            data-id="{{ $row->id }}"
                            data-nama="{{ $row->nota->nama ?? '' }}"
                            data-id-pel="{{ $row->id_pel }}"
                            data-harga="{{ $row->harga_jual }}"
                            data-jenis-bayar="{{ $row->nota->jenisBayar->jenis ?? '' }}"
                            data-bank="{{ $row->nota->bank->name ?? '' }}"
                        >
                            <td>{{ $row->tgl }}</td>
                            <td>{{ $row->nota->nama ?? '-' }}</td>
                            <td>{{ $row->id_pel }}</td>
                            <td>{{ $row->ppobJenis->jenis_ppob ?? '-' }}</td>
                            <td>{{ number_format($row->harga_jual) }}</td>
                            <td>{{ $row->nota->jenisBayar->jenis ?? '-' }}</td>
                            <td>{{ $row->nota->bank->name ?? '-' }}</td>
                            <td><span class="hint">Klik baris</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="8">DATA KOSONG</td></tr>
                        @endforelse
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

let selectedRow = null;

function selectRow(row) {
    // highlight
    document.querySelectorAll('.piutang-table tr')
        .forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');

    selectedRow = row;

    // ambil data
    const harga = row.dataset.harga;
    const nama = row.dataset.nama;
    const jenisBayar = row.dataset.jenisBayar;

    // isi form
    document.getElementById('nama').value = nama;
    document.getElementById('harga').value =
        new Intl.NumberFormat('id-ID').format(harga);
    document.getElementById('pembayaran').value = jenisBayar;

    calculateTotalPiutang(document.getElementById('nama_piutang').value);
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
