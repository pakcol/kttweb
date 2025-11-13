<x-layouts.app title="Rekapitulasi Penjualan">
    <!-- Include Navbar -->
    <x-navbar />

    <section class="report-section">
        <div class="container-fluid">
            <!-- Header dengan Date Picker -->
            <div class="report-header">
                <h1>REKAPITULASI PENJUALAN</h1>
                <p>PT. Kupang Tour & Travel</p>
                
                <div class="date-controls">
                    <div class="date-picker-group">
                        <label for="startDate">Dari Tanggal:</label>
                        <input type="date" id="startDate" class="date-picker">
                    </div>
                    <div class="date-picker-group">
                        <label for="endDate">Sampai Tanggal:</label>
                        <input type="date" id="endDate" class="date-picker">
                    </div>
                    <button class="btn-tampil" onclick="tampilkanLaporan()">Tampil</button>
                    <button class="btn-export" onclick="exportExcel()">Export Excel</button>
                </div>
                
                <div class="current-period" id="currentPeriod"></div>
            </div>

            <!-- Main Report Grid -->
            <div class="report-grid">
                
                <!-- 1. PENJUALAN -->
                <div class="report-card">
                    <h2 class="card-title">PENJUALAN</h2>
                    <div class="table-container">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Airlines</th>
                                    <th>Penjualan</th>
                                </tr>
                            </thead>
                            <tbody id="penjualanTable">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td><strong>TOTAL</strong></td>
                                    <td><strong id="totalPenjualan">Rp 0</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- 2. NTA -->
                <div class="report-card">
                    <h2 class="card-title">NTA</h2>
                    <div class="table-container">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Airlines</th>
                                    <th>NTA</th>
                                </tr>
                            </thead>
                            <tbody id="ntaTable">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td><strong>TOTAL</strong></td>
                                    <td><strong id="totalNta">Rp 0</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- 3. RINCIAN -->
                <div class="report-card">
                    <h2 class="card-title">RINCIAN</h2>
                    <div class="table-container">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="rincianTable">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. PLN -->
                <div class="report-card">
                    <h2 class="card-title">PLN</h2>
                    <div class="table-container">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="plnTable">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <style>
    .report-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 100px 20px 50px 20px;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .report-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .report-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .report-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    /* Date Controls */
    .date-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .date-picker-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .date-picker-group label {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
    }

    .date-picker {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .date-picker:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.5);
    }

    .btn-tampil, .btn-export {
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 18px;
    }

    .btn-tampil {
        background: #4ade80;
        color: white;
    }

    .btn-tampil:hover {
        background: #22c55e;
        transform: translateY(-2px);
    }

    .btn-export {
        background: #f59e0b;
        color: white;
    }

    .btn-export:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .current-period {
        font-size: 1.1rem;
        font-weight: 600;
        opacity: 0.9;
        margin-top: 10px;
    }

    /* Report Grid */
    .report-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .report-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
        color: #ffd700;
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        padding-bottom: 10px;
    }

    .table-container {
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    .report-table th {
        background: rgba(255, 215, 0, 0.3);
        padding: 12px 15px;
        text-align: left;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .report-table td {
        padding: 10px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .report-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .total-row {
        background: rgba(255, 215, 0, 0.2) !important;
        font-weight: 700;
    }

    .total-row td {
        border-top: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .report-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .report-section {
            padding: 120px 15px 30px 15px;
        }
        
        .report-header h1 {
            font-size: 2rem;
        }
        
        .date-controls {
            flex-direction: column;
            gap: 10px;
        }
        
        .date-picker-group {
            width: 100%;
        }
        
        .date-picker {
            width: 100%;
        }
        
        .btn-tampil, .btn-export {
            width: 100%;
            margin-top: 5px;
        }
    }
    </style>

    <script>
    // Data dummy berdasarkan periode
    const dummyReportData = {
        '2024-01': {
            penjualan: {
                'Citilink': 12450000,
                'Garuda': 18760000,
                'QGCorner': 8560000,
                'Lion': 23450000,
                'Sriwijaya': 5670000,
                'Transnusa': 9230000,
                'Pelni': 14560000,
                'Air Asia': 6780000,
                'DLU': 3450000
            },
            nta: {
                'Citilink': 11205000,
                'Garuda': 16884000,
                'QGCorner': 7704000,
                'Lion': 21105000,
                'Sriwijaya': 5103000,
                'Transnusa': 8307000,
                'Pelni': 13104000,
                'Air Asia': 6102000,
                'DLU': 3105000
            },
            rincian: {
                'Penjualan': 98760000,
                'NTA': 88884000,
                'Diskon': 3450000,
                'Biaya': 1230000,
                'Insentif': 2560000,
                'Rugi / Laba': 1876000
            },
            pln: {
                'PLN': 4560000,
                'Sisa Saldo': 12345000
            }
        },
        '2024-02': {
            penjualan: {
                'Citilink': 14560000,
                'Garuda': 19870000,
                'QGCorner': 9230000,
                'Lion': 25670000,
                'Sriwijaya': 6230000,
                'Transnusa': 10560000,
                'Pelni': 15670000,
                'Air Asia': 7450000,
                'DLU': 4120000
            },
            nta: {
                'Citilink': 13104000,
                'Garuda': 17883000,
                'QGCorner': 8307000,
                'Lion': 23103000,
                'Sriwijaya': 5607000,
                'Transnusa': 9504000,
                'Pelni': 14103000,
                'Air Asia': 6705000,
                'DLU': 3708000
            },
            rincian: {
                'Penjualan': 112345000,
                'NTA': 101110500,
                'Diskon': 4120000,
                'Biaya': 1456000,
                'Insentif': 2987000,
                'Rugi / Laba': 2345000
            },
            pln: {
                'PLN': 5120000,
                'Sisa Saldo': 14567000
            }
        }
    };

    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    // Update displayed period
    function updateDisplayedPeriod(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        
        const periodText = `${start.toLocaleDateString('id-ID', options)} - ${end.toLocaleDateString('id-ID', options)}`;
        document.getElementById('currentPeriod').textContent = `Periode: ${periodText}`;
    }

    // Generate table for penjualan
    function generatePenjualanTable(data) {
        const tableBody = document.getElementById('penjualanTable');
        const total = Object.values(data).reduce((sum, value) => sum + value, 0);
        
        let html = '';
        Object.entries(data).forEach(([airline, amount]) => {
            html += `
                <tr>
                    <td>${airline}</td>
                    <td>${formatCurrency(amount)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        document.getElementById('totalPenjualan').textContent = formatCurrency(total);
    }

    // Generate table for NTA
    function generateNtaTable(data) {
        const tableBody = document.getElementById('ntaTable');
        const total = Object.values(data).reduce((sum, value) => sum + value, 0);
        
        let html = '';
        Object.entries(data).forEach(([airline, amount]) => {
            html += `
                <tr>
                    <td>${airline}</td>
                    <td>${formatCurrency(amount)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        document.getElementById('totalNta').textContent = formatCurrency(total);
    }

    // Generate table for rincian
    function generateRincianTable(data) {
        const tableBody = document.getElementById('rincianTable');
        
        let html = '';
        Object.entries(data).forEach(([keterangan, jumlah]) => {
            html += `
                <tr>
                    <td>${keterangan}</td>
                    <td>${formatCurrency(jumlah)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }

    // Generate table for PLN
    function generatePlnTable(data) {
        const tableBody = document.getElementById('plnTable');
        
        let html = '';
        Object.entries(data).forEach(([keterangan, jumlah]) => {
            html += `
                <tr>
                    <td>${keterangan}</td>
                    <td>${formatCurrency(jumlah)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }

    // Tampilkan laporan
    function tampilkanLaporan() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert('Pilih tanggal mulai dan tanggal akhir!');
            return;
        }
        
        if (startDate > endDate) {
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
            return;
        }
        
        // Untuk demo, kita gunakan bulan dari startDate sebagai key
        const monthKey = startDate.substring(0, 7); // YYYY-MM
        const data = dummyReportData[monthKey] || dummyReportData['2024-01'];
        
        // Update tampilan
        updateDisplayedPeriod(startDate, endDate);
        generatePenjualanTable(data.penjualan);
        generateNtaTable(data.nta);
        generateRincianTable(data.rincian);
        generatePlnTable(data.pln);
        
        console.log('Laporan ditampilkan untuk periode:', startDate, 'sampai', endDate);
    }

    // Export Excel (simulasi)
    function exportExcel() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert('Pilih tanggal mulai dan tanggal akhir terlebih dahulu!');
            return;
        }
        
        // Simulasi export Excel
        alert(`Export Excel berhasil untuk periode ${startDate} - ${endDate}\n\nFile akan didownload otomatis.`);
        console.log('Export Excel untuk periode:', startDate, 'sampai', endDate);
        
        // Di real implementation, ini akan mengarah ke endpoint export Excel
        // window.location.href = `/export-excel?start=${startDate}&end=${endDate}`;
    }

    // Set default dates (current month)
    function setDefaultDates() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        
        document.getElementById('startDate').value = formatDate(firstDay);
        document.getElementById('endDate').value = formatDate(lastDay);
    }

    // Format date to YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        setDefaultDates();
        tampilkanLaporan(); // Tampilkan laporan default saat pertama load
    });
    </script>

</x-layouts.app>