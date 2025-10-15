<x-layouts.app title="Piutang - PT. Kupang Tour & Travel">

<section class="piutang-section">
    <div class="form-container">
        <h2>PIUTANG</h2>
        <div class="current-time" id="currentDateTime"></div>

        <form id="piutangForm">
            @csrf
            <input type="hidden" id="piutangId" name="piutang_id">

            <div class="input-grid">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="form-group">
                        <label for="nama_piutang">NAMA PIUTANG</label>
                        <select id="nama_piutang" name="nama_piutang" class="text-uppercase" required>
                            <option value="">Pilih Nama Piutang</option>
                            <option value="JOHN_DOE">JOHN DOE</option>
                            <option value="JANE_SMITH">JANE SMITH</option>
                            <option value="BOB_WILSON">BOB WILSON</option>
                            <option value="ALICE_BROWN">ALICE BROWN</option>
                            <option value="CHARLIE_DAVIS">CHARLIE DAVIS</option>
                        </select>
                    </div>
                    
                    <div class="summary-card">
                        <h4>Total Piutang</h4>
                        <div class="summary-value" id="totalPiutang">Rp 0</div>
                    </div>
                    
                    <div class="summary-card">
                        <h4>Total Diskon</h4>
                        <div class="summary-value" id="totalDiskon">Rp 0</div>
                    </div>
                </div>

                {{-- Kolom Tengah --}}
                <div>
                    <div class="form-group">
                        <label for="kode_booking">KODE BOOKING</label>
                        <input type="text" id="kode_booking" name="kode_booking" class="text-uppercase" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">NAMA</label>
                        <input type="text" id="nama" name="nama" class="text-uppercase" readonly>
                    </div>
                    <div class="form-group">
                        <label for="harga">HARGA</label>
                        <input type="number" id="harga" name="harga" value="0" step="0.01" readonly>
                    </div>
                    <div class="form-group">
                        <label for="diskon">DISKON</label>
                        <input type="number" id="diskon" name="diskon" value="0" step="0.01" readonly>
                    </div>
                    <div class="form-group">
                        <label for="komisi">KOMISI</label>
                        <input type="number" id="komisi" name="komisi" value="0" step="0.01" readonly>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="form-group">
                        <label for="pembayaran_piutang">PEMBAYARAN PIUTANG</label>
                        <select id="pembayaran_piutang" name="pembayaran_piutang" class="text-uppercase" required>
                            <option value="">Pilih Pembayaran</option>
                            <option value="LUNAS">LUNAS</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="BTN">BTN</option>
                            <option value="MANDIRI">MANDIRI</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>

                    <div class="summary-card total-belum-bayar">
                        <h4>Total Piutang Belum Dibayar</h4>
                        <div class="summary-value" id="totalBelumBayar">Rp 0</div>
                    </div>

                    <div class="button-group">
                        <button type="button" id="btnUpdate" class="btn-hijau">Update</button>
                        <button type="button" class="btn-merah" id="btnCetakInvoice">Cetak Invoice</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Tabel Data Piutang -->
<div class="table-card">
    <h3>Data Piutang</h3>
    <table id="piutangTable">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Nama</th>
                <th>Nama Piutang</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Komisi</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Tanggal Piutang</th>
            </tr>
        </thead>
        <tbody id="piutangTableBody">
            <!-- Data akan diisi oleh JavaScript -->
        </tbody>
    </table>
</div>

<link rel="stylesheet" href="{{ asset('css/piutang.css') }}">

<script>
// Data Dummy Piutang
const dummyPiutangData = {
    'JOHN_DOE': {
        totalPiutang: 12500000,
        totalDiskon: 500000,
        totalBelumBayar: 12000000,
        items: [
            { id: 1, kode_booking: 'BOOK001', nama: 'JOHN DOE', harga: 2500000, diskon: 100000, komisi: 150000, pembayaran: 'PIUTANG', tanggal: '15/01/2024' },
            { id: 2, kode_booking: 'BOOK002', nama: 'JANE SMITH', harga: 3000000, diskon: 150000, komisi: 180000, pembayaran: 'PIUTANG', tanggal: '16/01/2024' },
            { id: 3, kode_booking: 'BOOK003', nama: 'BOB WILSON', harga: 7000000, diskon: 250000, komisi: 420000, pembayaran: 'LUNAS', tanggal: '17/01/2024' }
        ]
    },
    'JANE_SMITH': {
        totalPiutang: 8500000,
        totalDiskon: 300000,
        totalBelumBayar: 8500000,
        items: [
            { id: 4, kode_booking: 'BOOK004', nama: 'ALICE BROWN', harga: 4500000, diskon: 200000, komisi: 270000, pembayaran: 'PIUTANG', tanggal: '18/01/2024' },
            { id: 5, kode_booking: 'BOOK005', nama: 'CHARLIE DAVIS', harga: 4000000, diskon: 100000, komisi: 240000, pembayaran: 'PIUTANG', tanggal: '19/01/2024' }
        ]
    },
    'BOB_WILSON': {
        totalPiutang: 5200000,
        totalDiskon: 200000,
        totalBelumBayar: 0,
        items: [
            { id: 6, kode_booking: 'BOOK006', nama: 'DAVID MILLER', harga: 5200000, diskon: 200000, komisi: 312000, pembayaran: 'LUNAS', tanggal: '20/01/2024' }
        ]
    },
    'ALICE_BROWN': {
        totalPiutang: 0,
        totalDiskon: 0,
        totalBelumBayar: 0,
        items: []
    },
    'CHARLIE_DAVIS': {
        totalPiutang: 0,
        totalDiskon: 0,
        totalBelumBayar: 0,
        items: []
    }
};

// Format currency
function formatCurrency(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function updateDateTime() {
    const now = new Date();
    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID');
    const jam = now.toLocaleTimeString('id-ID', { hour12: false });
    document.getElementById('currentDateTime').textContent = `${hari}, ${tanggal} | ${jam}`;
}
setInterval(updateDateTime, 1000);
updateDateTime();

// Fungsi untuk mengisi tabel dengan data
function populateTable(data) {
    const tableBody = document.getElementById('piutangTableBody');
    tableBody.innerHTML = '';

    data.items.forEach((item, index) => {
        const row = document.createElement('tr');
        row.setAttribute('data-id', item.id);
        row.setAttribute('data-nama-piutang', item.nama_piutang);
        
        const statusClass = item.pembayaran === 'PIUTANG' ? 'status-belum-lunas' : 'status-lunas';
        const statusText = item.pembayaran === 'PIUTANG' ? 'BELUM LUNAS' : 'LUNAS';

        row.innerHTML = `
            <td><input type="checkbox" class="chkPiutang" value="${item.id}"></td>
            <td>${index + 1}</td>
            <td>${item.kode_booking}</td>
            <td>${item.nama}</td>
            <td>${document.getElementById('nama_piutang').value}</td>
            <td>${formatCurrency(item.harga)}</td>
            <td>${formatCurrency(item.diskon)}</td>
            <td>${formatCurrency(item.komisi)}</td>
            <td>${item.pembayaran}</td>
            <td>
                <span class="status-badge ${statusClass}">${statusText}</span>
            </td>
            <td>${item.tanggal}</td>
        `;
        
        tableBody.appendChild(row);
    });

    // Tambahkan event listeners ke row baru
    addRowEventListeners();
}

// Update summary ketika nama piutang dipilih
document.getElementById('nama_piutang').addEventListener('change', function() {
    const selectedUser = this.value;
    
    if (selectedUser && dummyPiutangData[selectedUser]) {
        const data = dummyPiutangData[selectedUser];
        
        document.getElementById('totalPiutang').textContent = formatCurrency(data.totalPiutang);
        document.getElementById('totalDiskon').textContent = formatCurrency(data.totalDiskon);
        document.getElementById('totalBelumBayar').textContent = formatCurrency(data.totalBelumBayar);
        
        // Isi tabel dengan data
        populateTable(data);
    } else {
        // Reset jika tidak ada data
        document.getElementById('totalPiutang').textContent = 'Rp 0';
        document.getElementById('totalDiskon').textContent = 'Rp 0';
        document.getElementById('totalBelumBayar').textContent = 'Rp 0';
        document.getElementById('piutangTableBody').innerHTML = '';
    }
    
    // Reset form
    document.getElementById('piutangForm').reset();
    document.getElementById('piutangId').value = '';
});

// Fungsi untuk menambahkan event listeners ke row
function addRowEventListeners() {
    let selectedPiutangRow = null;
    
    document.querySelectorAll('#piutangTable tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            // Hanya proses jika row sesuai dengan nama piutang yang dipilih
            const selectedUser = document.getElementById('nama_piutang').value;
            
            if (selectedUser) {
                document.querySelectorAll('#piutangTable tr').forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
                selectedPiutangRow = this;

                const data = Array.from(this.children).map(td => td.innerText);
                document.getElementById('piutangId').value = this.dataset.id;
                document.getElementById('kode_booking').value = data[2];
                document.getElementById('nama').value = data[3];
                document.getElementById('harga').value = parseFloat(data[5].replace(/\./g, '').replace('Rp ', '')) || 0;
                document.getElementById('diskon').value = parseFloat(data[6].replace(/\./g, '').replace('Rp ', '')) || 0;
                document.getElementById('komisi').value = parseFloat(data[7].replace(/\./g, '').replace('Rp ', '')) || 0;
                
                // Set pembayaran berdasarkan status
                const status = data[9].includes('LUNAS') ? 'LUNAS' : 'PIUTANG';
                document.getElementById('pembayaran_piutang').value = status;
            } else {
                alert('Pilih Nama Piutang terlebih dahulu!');
            }
        });
    });
}

// Update data piutang (simulasi)
document.getElementById('btnUpdate').addEventListener('click', function() {
    const piutangId = document.getElementById('piutangId').value;
    const pembayaran = document.getElementById('pembayaran_piutang').value;
    
    if (!piutangId) {
        alert('Pilih data piutang dari tabel terlebih dahulu!');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin mengupdate status pembayaran?')) {
        // Simulasi update data
        console.log('Updating piutang:', { piutangId, pembayaran });
        
        // Update data dummy (dalam implementasi real, ini akan ke database)
        const selectedUser = document.getElementById('nama_piutang').value;
        const item = dummyPiutangData[selectedUser].items.find(item => item.id == piutangId);
        
        if (item) {
            item.pembayaran = pembayaran;
            
            // Recalculate totals
            recalculateTotals(selectedUser);
            
            // Refresh table
            populateTable(dummyPiutangData[selectedUser]);
            
            alert('Status pembayaran berhasil diupdate!');
            
            // Reset form
            document.getElementById('piutangForm').reset();
            document.getElementById('piutangId').value = '';
        }
    }
});

// Fungsi untuk menghitung ulang total
function recalculateTotals(username) {
    const data = dummyPiutangData[username];
    let totalPiutang = 0;
    let totalDiskon = 0;
    let totalBelumBayar = 0;
    
    data.items.forEach(item => {
        totalPiutang += item.harga;
        totalDiskon += item.diskon;
        if (item.pembayaran === 'PIUTANG') {
            totalBelumBayar += item.harga;
        }
    });
    
    data.totalPiutang = totalPiutang;
    data.totalDiskon = totalDiskon;
    data.totalBelumBayar = totalBelumBayar;
    
    // Update display
    document.getElementById('totalPiutang').textContent = formatCurrency(totalPiutang);
    document.getElementById('totalDiskon').textContent = formatCurrency(totalDiskon);
    document.getElementById('totalBelumBayar').textContent = formatCurrency(totalBelumBayar);
}

// Cetak invoice (simulasi)
document.getElementById('btnCetakInvoice').addEventListener('click', function() {
    const piutangId = document.getElementById('piutangId').value;
    const selectedUser = document.getElementById('nama_piutang').value;
    
    if (!piutangId) {
        alert('Pilih data piutang dari tabel terlebih dahulu!');
        return;
    }
    
    if (confirm('Apakah Anda ingin mencetak invoice untuk piutang ini?')) {
        // Simulasi cetak invoice
        alert('Fitur cetak invoice akan membuka halaman invoice di tab baru');
        console.log('Cetak invoice untuk:', { piutangId, selectedUser });
        
        // Dalam implementasi real: window.open(`/piutang/invoice/${piutangId}`, '_blank');
    }
});

// Multi-select dengan CTRL
document.addEventListener('click', function(e) {
    if (e.ctrlKey || e.metaKey) {
        const row = e.target.closest('tr');
        if (row && row.parentElement.tagName === 'TBODY') {
            row.classList.toggle('selected');
        }
    }
});

// Inisialisasi data default
document.addEventListener('DOMContentLoaded', function() {
    // Pilih user pertama sebagai default
    document.getElementById('nama_piutang').value = 'JOHN_DOE';
    document.getElementById('nama_piutang').dispatchEvent(new Event('change'));
});
</script>

</x-layouts.app>