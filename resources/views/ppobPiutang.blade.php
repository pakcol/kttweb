<x-layouts.app title="PPOB - Piutang">
<link rel="stylesheet" href="{{ asset('css/plnPiutang.css') }}">

<section class="pln-piutang-container">
    <div class="piutang-form">
        <div class="form-left">
            {{-- NAMA --}}
            <label>Nama</label>
            <input type="text" id="nama" readonly>

            {{-- HARGA --}}
            <label>Harga</label>
            <input type="text" id="harga" readonly>

            {{-- PEMBAYARAN --}}
            <label for="jenis_bayar_id">Pembayaran</label>
            <select id="jenis_bayar_id" name="jenis_bayar_id" class="form-control" required>
                <option value="">-- Pilih Jenis Pembayaran --</option>
                @if(isset($jenisBayarNonPiutang) && $jenisBayarNonPiutang->count() > 0)
                    @foreach($jenisBayarNonPiutang as $jenis)
                        <option value="{{ $jenis->id }}">
                            {{ $jenis->jenis }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>Data jenis pembayaran tidak ditemukan</option>
                @endif
            </select>

            <div id="bankContainer">
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

        <div class="form-right">
            <button id="updateBtn" onclick="updateData()">BAYAR</button>
        </div>
    </div>

    <div class="table-section">
    <table id="piutangTable">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>ID Pelanggan</th>
                <th>Kategori PPOB</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($piutang as $row)
                <tr onclick="selectRow(this)"
                    data-id="{{ $row->id }}"
                    data-nama="{{ $row->nama }}"
                    data-id-pel="{{ $row->pembayaranOnline->id_pel ?? '' }}"
                    data-harga="{{ $row->harga_bayar }}"
                >
                    <td>{{ $row->tgl_issued?->format('d-m-Y') }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->pembayaranOnline->id_pel ?? '-' }}</td>
                    <td>{{ $row->pembayaranOnline->jenisPpob->jenis_ppob ?? '-' }}</td>
                    <td>{{ number_format($row->harga_bayar) }}</td>
                    <td><span class="hint">Klik baris</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;font-weight:600;">
                        DATA KOSONG
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</section>

<script>
let selectedRow = null;
let allPiutangData = @json($piutang);
const bankContainer = document.getElementById('bankContainer');
const jenisSelect = document.getElementById('jenis_bayar_id');
const bankInput  = document.getElementById('bank_id');

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

/* ================= TOTAL PIUTANG ================= */
function calculateTotalPiutang(namaPiutang) {
    if (!namaPiutang) {
        document.getElementById('totalPiutang').value = '';
        return;
    }

    let total = 0;

    if (namaPiutang === 'ALL') {
        allPiutangData.forEach(item => {
            total += parseInt(item.harga_bayar) || 0;
        });
    } else {
        allPiutangData.forEach(item => {
            if (item.nama === namaPiutang) {
                total += parseInt(item.harga_bayar) || 0;
            }
        });
    }

    document.getElementById('totalPiutang').value =
        new Intl.NumberFormat('id-ID').format(total);
}

// Event listener untuk perubahan nama piutang
document.getElementById('nama_piutang').addEventListener('input', function () {
    calculateTotalPiutang(this.value);
});

// Event listener untuk perubahan nama piutang (change event)
document.getElementById('nama_piutang').addEventListener('change', function () {
    calculateTotalPiutang(this.value);
});


function selectRow(row) {
    document.querySelectorAll('.pln-table tbody tr')
        .forEach(r => r.classList.remove('selected'));

    row.classList.add('selected');
    selectedRow = row;

    document.getElementById('nama').value = row.dataset.nama;
    document.getElementById('harga').value =
        new Intl.NumberFormat('id-ID').format(row.dataset.harga);

    document.getElementById('pembayaran').value =
        row.dataset.jenisBayar;

    calculateTotalPiutang(
        document.getElementById('nama_piutang').value
    );
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
