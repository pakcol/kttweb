<x-layouts.app title="Cash Flow">
    <!-- Include Navbar -->
    <x-navbar />

    <section class="dashboard-section">
        <div class="container-fluid">
            <!-- Header dengan Single Date Picker -->
            <div class="dashboard-header">
                <h1>CASH FLOW</h1>
                <p>PT. Kupang Tour & Travel</p>
                
                <div class="date-controls">
                    <div class="date-picker-group">
                        <label for="selectedDate">Pilih Tanggal:</label>
                        <input type="date" id="selectedDate" class="date-picker" onchange="updateDisplayedDate()">
                    </div>
                </div>
                
                <div class="current-date" id="displayedDate"></div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                
                <!-- 1. PENJUALAN -->
                <div class="dashboard-card sales-card">
                    <h2 class="card-title">PENJUALAN</h2>
                    <div class="card-content">
                        <!-- Row 1: Penjualan, Piutang, Pengeluaran, Refund -->
                        <div class="metric-row">
                            <div class="metric-item">
                                <h3>Penjualan</h3>
                                <div class="metric-value">Rp <span id="penjualanValue">25.430.000</span></div>
                                <div class="metric-change positive">+12%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Piutang</h3>
                                <div class="metric-value">Rp <span id="piutangValue">8.750.000</span></div>
                                <div class="metric-change negative">-5%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Pengeluaran</h3>
                                <div class="metric-value">Rp <span id="pengeluaranValue">3.210.000</span></div>
                                <div class="metric-change positive">+8%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Refund</h3>
                                <div class="metric-value">Rp <span id="refundValue">1.450.000</span></div>
                                <div class="metric-change negative">-15%</div>
                            </div>
                        </div>

                        <!-- Row 2: Transfer Bank -->
                        <div class="metric-row">
                            <div class="metric-item">
                                <h3>Transfer BCA</h3>
                                <div class="metric-value">Rp <span id="transferBca">12.350.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BRI</h3>
                                <div class="metric-value">Rp <span id="transferBri">5.670.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BNI</h3>
                                <div class="metric-value">Rp <span id="transferBni">3.890.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BTN</h3>
                                <div class="metric-value">Rp <span id="transferBtn">1.240.000</span></div>
                            </div>
                        </div>

                        <!-- Row 3: Setoran Bank -->
                        <div class="metric-row">
                            <div class="metric-item">
                                <h3>Setoran BCA</h3>
                                <div class="metric-value">Rp <span id="setoranBca">15.820.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran BRI</h3>
                                <div class="metric-value">Rp <span id="setoranBri">6.540.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran BNI</h3>
                                <div class="metric-value">Rp <span id="setoranBni">4.310.000</span></div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran Mandiri</h3>
                                <div class="metric-value">Rp <span id="setoranMandiri">2.980.000</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. SALDO AIRLINES -->
                <div class="dashboard-card airlines-card">
                    <h2 class="card-title">SALDO AIRLINES</h2>
                    <div class="card-content">
                        <div class="airlines-grid">
                            <div class="airline-item">
                                <h3>Citilink</h3>
                                <div class="airline-value">Rp <span id="citilinkValue">45.230.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Garuda</h3>
                                <div class="airline-value">Rp <span id="garudaValue">38.750.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>QGCorner</h3>
                                <div class="airline-value">Rp <span id="qgValue">22.180.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Lion</h3>
                                <div class="airline-value">Rp <span id="lionValue">67.890.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Sriwijaya</h3>
                                <div class="airline-value">Rp <span id="sriwijayaValue">15.420.000</span></div>
                                <div class="airline-status warning">Limited</div>
                            </div>
                            <div class="airline-item">
                                <h3>Transnusa</h3>
                                <div class="airline-value">Rp <span id="transnusaValue">28.560.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Pelni</h3>
                                <div class="airline-value">Rp <span id="pelniValue">33.910.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Air Asia</h3>
                                <div class="airline-value">Rp <span id="airasiaValue">19.670.000</span></div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>DLU</h3>
                                <div class="airline-value">Rp <span id="dluValue">12.340.000</span></div>
                                <div class="airline-status warning">Limited</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. SALDO SUB AGENT -->
                <div class="dashboard-card agent-card">
                    <h2 class="card-title">SALDO SUB AGENT</h2>
                    <div class="card-content">
                        <div class="agent-grid">
                            <div class="agent-item">
                                <h3>EVI</h3>
                                <div class="agent-value">Rp <span id="eviValue">18.750.000</span></div>
                                <div class="agent-detail">
                                    <span class="detail-item">Tiket: <span id="eviTiket">45</span></span>
                                    <span class="detail-item">Piutang: Rp <span id="eviPiutang">3.2</span>M</span>
                                </div>
                            </div>
                            <div class="agent-item">
                                <h3>Cash</h3>
                                <div class="agent-value">Rp <span id="cashValue">12.430.000</span></div>
                                <div class="agent-detail">
                                    <span class="detail-item">Transaksi: <span id="cashTransaksi">128</span></span>
                                    <span class="detail-item">Hari Ini: Rp <span id="cashHariIni">2.1</span>M</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Summary Section -->
            <div class="summary-section">
                <div class="summary-card total-sales">
                    <h3>TOTAL PENJUALAN</h3>
                    <div class="summary-value">Rp <span id="totalPenjualan">67.890.000</span></div>
                    <div class="summary-change positive">+18% dari bulan lalu</div>
                </div>
                <div class="summary-card total-balance">
                    <h3>TOTAL SALDO</h3>
                    <div class="summary-value">Rp <span id="totalSaldo">284.530.000</span></div>
                    <div class="summary-change positive">+22% dari bulan lalu</div>
                </div>
                <div class="summary-card active-transactions">
                    <h3>TRANSAKSI AKTIF</h3>
                    <div class="summary-value"><span id="totalTransaksi">187</span></div>
                    <div class="summary-change positive">+15 transaksi</div>
                </div>
            </div>

        </div>
    </section>

    <style>
    .dashboard-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 100px 20px 50px 20px;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .dashboard-header p {
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
    }

    .date-picker-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .date-picker-group label {
        font-size: 1rem;
        font-weight: 600;
        opacity: 0.9;
    }

    .date-picker {
        padding: 10px 15px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        min-width: 200px;
    }

    .date-picker:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.5);
        transform: scale(1.05);
    }

    .date-picker:hover {
        transform: scale(1.05);
    }

    .current-date {
        font-size: 1.2rem;
        font-weight: 600;
        opacity: 0.9;
        margin-top: 10px;
    }

    /* Sisanya CSS tetap sama */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-card {
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

    .metric-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .metric-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .metric-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }

    .metric-item h3 {
        font-size: 0.9rem;
        margin-bottom: 8px;
        opacity: 0.9;
        font-weight: 600;
    }

    .metric-value {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .metric-change {
        font-size: 0.8rem;
        font-weight: 600;
    }

    .positive {
        color: #4ade80;
    }

    .negative {
        color: #f87171;
    }

    .warning {
        color: #fbbf24;
    }

    /* Airlines Grid */
    .airlines-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .airline-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .airline-item:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.2);
    }

    .airline-item h3 {
        font-size: 1rem;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .airline-value {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .airline-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
    }

    /* Agent Grid */
    .agent-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .agent-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .agent-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }

    .agent-item h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .agent-value {
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: #ffd700;
    }

    .agent-detail {
        display: flex;
        justify-content: space-around;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .detail-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
    }

    /* Summary Section */
    .summary-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        max-width: 1200px;
        margin: 30px auto 0 auto;
    }

    .summary-card {
        background: rgba(255, 255, 255, 0.15);
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .summary-card h3 {
        font-size: 1rem;
        margin-bottom: 15px;
        opacity: 0.9;
        font-weight: 600;
    }

    .summary-value {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: #ffd700;
    }

    .summary-change {
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-section {
            padding: 120px 15px 30px 15px;
        }
        
        .dashboard-header h1 {
            font-size: 2rem;
        }
        
        .date-picker {
            min-width: 150px;
        }
        
        .metric-row {
            grid-template-columns: 1fr;
        }
        
        .airlines-grid {
            grid-template-columns: 1fr;
        }
        
        .agent-grid {
            grid-template-columns: 1fr;
        }
        
        .summary-section {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    // Data dummy berdasarkan tanggal (simulasi)
    const dummyData = {
        '2024-01-15': {
            penjualan: '25.430.000',
            piutang: '8.750.000',
            pengeluaran: '3.210.000',
            refund: '1.450.000',
            transferBca: '12.350.000',
            transferBri: '5.670.000',
            transferBni: '3.890.000',
            transferBtn: '1.240.000',
            setoranBca: '15.820.000',
            setoranBri: '6.540.000',
            setoranBni: '4.310.000',
            setoranMandiri: '2.980.000',
            citilink: '45.230.000',
            garuda: '38.750.000',
            qg: '22.180.000',
            lion: '67.890.000',
            sriwijaya: '15.420.000',
            transnusa: '28.560.000',
            pelni: '33.910.000',
            airasia: '19.670.000',
            dlu: '12.340.000',
            evi: '18.750.000',
            eviTiket: '45',
            eviPiutang: '3.2',
            cash: '12.430.000',
            cashTransaksi: '128',
            cashHariIni: '2.1',
            totalPenjualan: '67.890.000',
            totalSaldo: '284.530.000',
            totalTransaksi: '187'
        },
        '2024-01-20': {
            penjualan: '28.560.000',
            piutang: '7.890.000',
            pengeluaran: '2.980.000',
            refund: '1.230.000',
            transferBca: '14.250.000',
            transferBri: '6.340.000',
            transferBni: '4.120.000',
            transferBtn: '1.450.000',
            setoranBca: '16.780.000',
            setoranBri: '7.210.000',
            setoranBni: '4.890.000',
            setoranMandiri: '3.450.000',
            citilink: '47.890.000',
            garuda: '40.120.000',
            qg: '23.450.000',
            lion: '69.780.000',
            sriwijaya: '16.230.000',
            transnusa: '29.870.000',
            pelni: '35.210.000',
            airasia: '20.450.000',
            dlu: '13.120.000',
            evi: '19.560.000',
            eviTiket: '48',
            eviPiutang: '3.4',
            cash: '13.780.000',
            cashTransaksi: '135',
            cashHariIni: '2.3',
            totalPenjualan: '72.340.000',
            totalSaldo: '298.670.000',
            totalTransaksi: '195'
        },
        '2024-01-25': {
            penjualan: '31.780.000',
            piutang: '6.540.000',
            pengeluaran: '3.450.000',
            refund: '980.000',
            transferBca: '16.890.000',
            transferBri: '7.120.000',
            transferBni: '4.780.000',
            transferBtn: '1.670.000',
            setoranBca: '18.450.000',
            setoranBri: '8.340.000',
            setoranBni: '5.230.000',
            setoranMandiri: '3.890.000',
            citilink: '50.340.000',
            garuda: '42.780.000',
            qg: '25.120.000',
            lion: '72.450.000',
            sriwijaya: '17.890.000',
            transnusa: '31.230.000',
            pelni: '37.560.000',
            airasia: '22.340.000',
            dlu: '14.780.000',
            evi: '21.230.000',
            eviTiket: '52',
            eviPiutang: '3.6',
            cash: '15.120.000',
            cashTransaksi: '142',
            cashHariIni: '2.5',
            totalPenjualan: '78.910.000',
            totalSaldo: '315.230.000',
            totalTransaksi: '208'
        }
    };

    // Format tanggal Indonesia
    function formatIndonesianDate(dateString) {
        const date = new Date(dateString);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return date.toLocaleDateString('id-ID', options);
    }

    // Update displayed date and data
    function updateDisplayedDate() {
        const selectedDate = document.getElementById('selectedDate').value;
        const displayedDateElement = document.getElementById('displayedDate');
        
        // Update displayed date
        displayedDateElement.textContent = formatIndonesianDate(selectedDate);
        
        // Update data berdasarkan tanggal yang dipilih
        updateDataForDate(selectedDate);
    }

    // Update data berdasarkan tanggal
    function updateDataForDate(date) {
        const data = dummyData[date] || dummyData['2024-01-15']; // Fallback ke data default
        
        // Update semua nilai
        Object.keys(data).forEach(key => {
            const element = document.getElementById(key + 'Value') || document.getElementById(key);
            if (element) {
                element.textContent = data[key];
            }
        });
    }

    // Set default date to today
    function setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('selectedDate').value = today;
        updateDisplayedDate();
    }

    document.addEventListener('DOMContentLoaded', function() {
        setDefaultDate();
        
        // Add hover effects
        const metricItems = document.querySelectorAll('.metric-item, .airline-item, .agent-item');
        metricItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
    </script>

</x-layouts.app>