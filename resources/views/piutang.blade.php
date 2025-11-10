<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Data Piutang</h2>

        <form id="formPiutang" method="POST" action="{{ route('piutang.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_piutang">NAMA PIUTANG</label>
                    <select id="nama_piutang" name="nama_piutang" class="form-control">
                        <option value="">Pilih Nama Piutang</option>
                        @foreach($namaPiutangList as $nama)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Total Piutang Belum Dibayar</label>
                    <input type="text" id="piutang_belum_dibayar" name="piutang_belum_dibayar" readonly>
                </div>

                <div class="form-group">
                    <label>Total Piutang</label>
                    <input type="number" id="total_piutang" name="total_piutang" placeholder="Masukkan total piutang" readonly>
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" id="harga" name="harga" placeholder="Masukkan harga" readonly>
                </div>

                <div class="form-group">
                    <label>Total Diskon</label>
                    <input type="number" id="total_diskon" name="total_diskon" placeholder="Masukkan total diskon" readonly>
                </div>

                <div class="form-group">
                    <label>Diskon</label>
                    <input type="number" id="diskon" name="diskon" placeholder="Masukkan diskon" readonly>
                </div>

                <div class="form-group">
                    <label>Kode Booking</label>
                    <input type="text" id="kode_booking" name="kode_booking" placeholder="Masukkan kode booking" readonly>
                </div>

                <div class="form-group">
                    <label>Komisi</label>
                    <input type="number" id="komisi" name="komisi" placeholder="Masukkan komisi" readonly>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama" readonly>
                </div>

                <div class="form-group">
                    <label for="pembayaran">PEMBAYARAN</label>
                    <select id="pembayaran" name="pembayaran" class="form-control">
                        <option>LUNAS</option>
                        <option>BCA</option>
                        <option>BNI</option>
                        <option>BTN</option>
                        <option>MANDIRI</option>
                        <option>BRI</option>
                    </select>
                </div>
            </div>

            <div class="btn-actions">
                <button type="submit" class="btn-update">Update</button>
                <button type="button" class="btn-print">Cetak Invoice</button>
            </div>
        </form>
    </div>

    <div class="card-table">
        <h3>Data Piutang</h3>
        <table class="table-piutang">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Komentar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr data-id="{{ $item->id }}" onclick="selectRow(this)">
                    <td>{{ $item->kodeBooking }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->diskon ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        @if($item->tglRealisasi)
                            <span class="status-lunas">Lunas</span>
                        @else
                            <span class="status-belum-lunas">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
let allPiutangData = @json($data);

// Fungsi untuk menghitung total piutang berdasarkan nama piutang
function calculateTotalPiutang(namaPiutang) {
    if (!namaPiutang) {
        document.getElementById('piutang_belum_dibayar').value = '';
        document.getElementById('total_piutang').value = '';
        return;
    }

    let totalPiutang = 0;
    let totalBelumDibayar = 0;

    allPiutangData.forEach(item => {
        if (item.namaPiutang === namaPiutang) {
            totalPiutang += parseInt(item.harga) || 0;
            // Jika belum ada tglRealisasi, berarti belum dibayar
            if (!item.tglRealisasi) {
                totalBelumDibayar += parseInt(item.harga) || 0;
            }
        }
    });

    document.getElementById('total_piutang').value = totalPiutang;
    document.getElementById('piutang_belum_dibayar').value = new Intl.NumberFormat('id-ID').format(totalBelumDibayar);
}

// Event listener untuk perubahan nama piutang
document.getElementById('nama_piutang').addEventListener('change', function() {
    const namaPiutang = this.value;
    
    if (!namaPiutang) {
        // Reset form jika tidak ada yang dipilih
        resetForm();
        return;
    }

    // Filter data berdasarkan nama piutang
    const filteredData = allPiutangData.filter(item => item.namaPiutang === namaPiutang);
    
    if (filteredData.length > 0) {
        // Ambil data pertama untuk mengisi form (atau bisa diubah sesuai kebutuhan)
        const firstData = filteredData[0];
        
        // Isi form dengan data pertama
        document.getElementById('kode_booking').value = firstData.kodeBooking || '';
        document.getElementById('nama').value = firstData.nama || '';
        document.getElementById('harga').value = firstData.harga || 0;
        document.getElementById('diskon').value = firstData.diskon || 0;
        document.getElementById('komisi').value = firstData.komisi || 0;
        
        // Hitung total diskon (jika ada multiple data, bisa dijumlahkan)
        let totalDiskon = 0;
        filteredData.forEach(item => {
            totalDiskon += parseInt(item.diskon) || 0;
        });
        document.getElementById('total_diskon').value = totalDiskon;
        
        // Hitung total piutang
        calculateTotalPiutang(namaPiutang);
        
        // Filter dan update tabel
        filterTable(namaPiutang);
    } else {
        resetForm();
    }
});

// Fungsi untuk filter tabel berdasarkan nama piutang
function filterTable(namaPiutang) {
    const rows = document.querySelectorAll('.table-piutang tbody tr');
    rows.forEach(row => {
        const rowData = allPiutangData.find(item => item.id == row.dataset.id);
        if (rowData && rowData.namaPiutang === namaPiutang) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fungsi untuk reset form
function resetForm() {
    document.getElementById('kode_booking').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('harga').value = '';
    document.getElementById('diskon').value = '';
    document.getElementById('komisi').value = '';
    document.getElementById('total_diskon').value = '';
    document.getElementById('total_piutang').value = '';
    document.getElementById('piutang_belum_dibayar').value = '';
    
    // Tampilkan semua row
    document.querySelectorAll('.table-piutang tbody tr').forEach(row => {
        row.style.display = '';
    });
}

// Fungsi untuk select row dari tabel
function selectRow(row) {
    // Hapus selected class dari semua row
    document.querySelectorAll('.table-piutang tbody tr').forEach(r => {
        r.classList.remove('selected');
    });
    
    // Tambahkan selected class
    row.classList.add('selected');
    
    const rowId = row.dataset.id;
    const rowData = allPiutangData.find(item => item.id == rowId);
    
    if (rowData) {
        // Set nama piutang di select
        document.getElementById('nama_piutang').value = rowData.namaPiutang || '';
        
        // Isi form dengan data dari row yang dipilih
        document.getElementById('kode_booking').value = rowData.kodeBooking || '';
        document.getElementById('nama').value = rowData.nama || '';
        document.getElementById('harga').value = rowData.harga || 0;
        document.getElementById('diskon').value = rowData.diskon || 0;
        document.getElementById('komisi').value = rowData.komisi || 0;
        
        // Hitung total diskon untuk semua data dengan nama piutang yang sama
        const filteredData = allPiutangData.filter(item => item.namaPiutang === rowData.namaPiutang);
        let totalDiskon = 0;
        filteredData.forEach(item => {
            totalDiskon += parseInt(item.diskon) || 0;
        });
        document.getElementById('total_diskon').value = totalDiskon;
        
        // Hitung total piutang
        calculateTotalPiutang(rowData.namaPiutang);
        
        // Filter tabel
        if (rowData.namaPiutang) {
            filterTable(rowData.namaPiutang);
        }
    }
}
</script>

<style>
.table-piutang tbody tr {
    cursor: pointer;
}
.table-piutang tbody tr:hover {
    background-color: #f5f5f5;
}
.table-piutang tbody tr.selected {
    background-color: #e3f2fd;
}
.status-belum-lunas {
    color: #f44336;
    font-weight: bold;
}
</style>

</x-layouts.app>
