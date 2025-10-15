<x-layouts.app title="Tutup Kas">
    <!-- Include Navbar -->
    <x-navbar />

    <section class="dashboard-section">
        <div class="container-fluid">
            <!-- Header -->
            <div class="dashboard-header">
                <h1>TUTUP KAS</h1>
                <p>PT. Kupang Tour & Travel</p>
                <div class="current-date" id="currentDate"></div>
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
                                <div class="metric-value">Rp 25.430.000</div>
                                <div class="metric-change positive">+12%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Piutang</h3>
                                <div class="metric-value">Rp 8.750.000</div>
                                <div class="metric-change negative">-5%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Pengeluaran</h3>
                                <div class="metric-value">Rp 3.210.000</div>
                                <div class="metric-change positive">+8%</div>
                            </div>
                            <div class="metric-item">
                                <h3>Refund</h3>
                                <div class="metric-value">Rp 1.450.000</div>
                                <div class="metric-change negative">-15%</div>
                            </div>
                        </div>

                        <!-- Row 2: Transfer Bank -->
                        <div class="metric-row">
                            <div class="metric-item">
                                <h3>Transfer BCA</h3>
                                <div class="metric-value">Rp 12.350.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BRI</h3>
                                <div class="metric-value">Rp 5.670.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BNI</h3>
                                <div class="metric-value">Rp 3.890.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Transfer BTN</h3>
                                <div class="metric-value">Rp 1.240.000</div>
                            </div>
                        </div>

                        <!-- Row 3: Setoran Bank -->
                        <div class="metric-row">
                            <div class="metric-item">
                                <h3>Setoran BCA</h3>
                                <div class="metric-value">Rp 15.820.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran BRI</h3>
                                <div class="metric-value">Rp 6.540.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran BNI</h3>
                                <div class="metric-value">Rp 4.310.000</div>
                            </div>
                            <div class="metric-item">
                                <h3>Setoran Mandiri</h3>
                                <div class="metric-value">Rp 2.980.000</div>
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
                                <div class="airline-value">Rp 45.230.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Garuda</h3>
                                <div class="airline-value">Rp 38.750.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>QGCorner</h3>
                                <div class="airline-value">Rp 22.180.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Lion</h3>
                                <div class="airline-value">Rp 67.890.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Sriwijaya</h3>
                                <div class="airline-value">Rp 15.420.000</div>
                                <div class="airline-status warning">Limited</div>
                            </div>
                            <div class="airline-item">
                                <h3>Transnusa</h3>
                                <div class="airline-value">Rp 28.560.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Pelni</h3>
                                <div class="airline-value">Rp 33.910.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>Air Asia</h3>
                                <div class="airline-value">Rp 19.670.000</div>
                                <div class="airline-status positive">Active</div>
                            </div>
                            <div class="airline-item">
                                <h3>DLU</h3>
                                <div class="airline-value">Rp 12.340.000</div>
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
                                <div class="agent-value">Rp 18.750.000</div>
                                <div class="agent-detail">
                                    <span class="detail-item">Tiket: 45</span>
                                    <span class="detail-item">Piutang: Rp 3.2M</span>
                                </div>
                            </div>
                            <div class="agent-item">
                                <h3>Cash</h3>
                                <div class="agent-value">Rp 12.430.000</div>
                                <div class="agent-detail">
                                    <span class="detail-item">Transaksi: 128</span>
                                    <span class="detail-item">Hari Ini: Rp 2.1M</span>
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
                    <div class="summary-value">Rp 67.890.000</div>
                    <div class="summary-change positive">+18% dari bulan lalu</div>
                </div>
                <div class="summary-card total-balance">
                    <h3>TOTAL SALDO</h3>
                    <div class="summary-value">Rp 284.530.000</div>
                    <div class="summary-change positive">+22% dari bulan lalu</div>
                </div>
                <div class="summary-card active-transactions">
                    <h3>TRANSAKSI AKTIF</h3>
                    <div class="summary-value">187</div>
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
        margin-bottom: 10px;
    }

    .current-date {
        font-size: 1rem;
        opacity: 0.8;
    }

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
    @media (max-width: 1024px) {
        .metric-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .airlines-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .dashboard-section {
            padding: 120px 15px 30px 15px;
        }
        
        .dashboard-header h1 {
            font-size: 2rem;
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
    // Update current date
    function updateCurrentDate() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        document.getElementById('currentDate').textContent = 
            now.toLocaleDateString('id-ID', options);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateCurrentDate();
        
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