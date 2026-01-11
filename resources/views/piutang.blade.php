<x-layouts.app title="Data Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<div class="piutang-page">
<section class="piutang-section">
    <div class="card-form">
        <h2 class="form-title">Data Piutang</h2>
        <form method="POST" id="formPiutang">
            @csrf
            @method('PUT')

            <input type="hidden" name="mutasi_id" id="mutasi_id">

            <div class="form-group">
                <label>Nama Piutang</label>
                <select id="nama_piutang_select" class="form-control">
                    <option value="">ALL</option>
                    @foreach($piutang->pluck('nama_piutang')->unique()->filter() as $nama)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kode Booking</label>
                <input type="text" id="kode_booking" class="form-control">
            </div>

            <div class="form-group">
                <label>Nominal</label>
                <input type="number" id="nominal" class="form-control">
            </div>

            <div class="form-group">
                <label>Jenis Pembayaran</label>
                <select name="jenis_bayar_id" id="jenis_bayar_id" class="form-control" required>
                    @foreach($jenisBayarNonPiutang as $j)
                        <option value="{{ $j->id }}">{{ $j->jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="bankContainer" style="display:none">
                <label>Bank</label>
                <select name="bank_id" class="form-control">
                    <option value="">-- Pilih Bank --</option>
                    @foreach($bank as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Realisasi</label>
                <input type="date" name="tgl_bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <button class="btn btn-success">SIMPAN</button>
        </form>

    </div>

    <div class="card-table">
        <h3>Data Piutang</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Kode Booking</th>
                    <th>Airlines</th>
                    <th>Nominal</th>
                    <th>Rute</th>
                    <th>Tgl Flight</th>
                    <th>Rute 2</th>
                    <th>Tgl Flight 2</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($piutang as $row)
                    <tr 
                        data-id="{{ $row->id }}"
                        data-nama-piutang="{{ $row->nama_piutang }}"
                    >
                        <td>{{ $row->tiket->tgl_issued?->format('Y-m-d') }}</td>

                        <td>{{ $row->nama_piutang }}</td>
                        <td>{{ $row->tiket_kode_booking }}</td>

                        <td>{{ $row->tiket->jenisTiket->name_jenis ?? '-' }}</td>

                        <td>
                            {{ number_format($row->harga_bayar, 0, ',', '.') }}
                        </td>

                         <td>{{ $row->tiket?->rute ?? '-' }}</td>

                         <td>{{ $row->tiket?->tgl_flight?->format('Y-m-d') ?? '-' }}</td>

                         <td>{{ $row->tiket?->rute2 ?? '-' }}</td>

                         <td>{{ $row->tiket?->tgl_flight2?->format('Y-m-d') ?? '-' }}</td>    

                        <td>
                            <button 
                                class="btn btn-sm btn-primary btn-edit"
                                data-id="{{ $row->id }}"
                                data-kode="{{ $row->tiket_kode_booking }}"
                                data-nominal="{{ $row->harga_bayar }}"
                                data-nama-piutang="{{ $row->nama_piutang }}"
                            >
                                Bayar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
</div>

<script>
    document.getElementById('nama_piutang_select').addEventListener('change', function () {
        const selected = this.value;
        const rows = document.querySelectorAll('.table tbody tr');

        rows.forEach(row => {
            const nama = row.dataset.namaPiutang;

            if (!selected || selected === '') {
                // ALL
                row.style.display = '';
            } else {
                row.style.display = nama === selected ? '' : 'none';
            }
        });
    });
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const kode = btn.dataset.kode;
            const nominal = btn.dataset.nominal;
            const namaPiutang = btn.dataset.namaPiutang;

            // set hidden id
            document.getElementById('mutasi_id').value = id;

            // set kode booking
            document.getElementById('kode_booking').value = kode;

            // set nominal
            document.getElementById('nominal').value = nominal;

            // set dropdown nama piutang
            const selectNama = document.getElementById('nama_piutang_select');
            if (selectNama) {
                selectNama.value = namaPiutang;
                selectNama.dispatchEvent(new Event('change'));
            }

            // set action form
            document.getElementById('formPiutang').action =
                `/tiket/piutang/${id}`;
        });
    });


    document.getElementById('jenis_bayar_id').addEventListener('change', function () {
        document.getElementById('bankContainer').style.display =
            this.value === '1' ? 'block' : 'none' // asumsi 1 = BANK
    })


    let allPiutangData = @json($piutang);

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
