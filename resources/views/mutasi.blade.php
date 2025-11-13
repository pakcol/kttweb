<x-layouts.app title="Mutasi Airlines">

    <link rel="stylesheet" href="{{ asset('css/mutasi.css') }}">
    
    <section class="mutasi-airlines-section">
    <div class="mutasi-container">
        <h2>MUTASI AIRLINES</h2>
    
        <form action="{{ route('mutasi-airlines.store') }}" method="POST" class="mutasi-airlines">
            @csrf
            <div class="form-group">
                <label>TANGGAL</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>
    
            <div class="form-group">
                <label>TOP UP</label>
                <input type="number" name="topup" placeholder="Masukkan nominal" value="{{ old('topup') }}" min="0" step="1">
            </div>
    
            <div class="form-group">
                <label>BANK</label>
                <select name="bank" required>
                    <option value="">Pilih Bank</option>
                    <option value="bca" {{ old('bank') == 'bca' ? 'selected' : '' }}>BCA</option>
                    <option value="btn" {{ old('bank') == 'btn' ? 'selected' : '' }}>BTN</option>
                    <option value="bni" {{ old('bank') == 'bni' ? 'selected' : '' }}>BNI</option>
                    <option value="mandiri" {{ old('bank') == 'mandiri' ? 'selected' : '' }}>MANDIRI</option>
                    <option value="bri" {{ old('bank') == 'bri' ? 'selected' : '' }}>BRI</option>
                </select>
            </div>
    
            <div class="form-group">
                <label>BIAYA (Transaksi)</label>
                <input type="number" name="biaya" placeholder="Masukkan biaya transaksi" value="{{ old('biaya') }}" min="0" step="1">
            </div>
    
            <div class="form-group">
                <label>INSENTIF</label>
                <input type="number" name="insentif" placeholder="Masukkan insentif" value="{{ old('insentif') }}" min="0" step="1">
            </div>
    
            <div class="form-group">
                <label>KETERANGAN</label>
                <input type="text" name="keterangan" placeholder="Contoh: Pengeluaran tiket, bonus, dll" value="{{ old('keterangan') }}" maxlength="30">
                <small class="char-count">0/30 karakter</small>
            </div>
    
            <div class="form-group">
                <label>AIRLINES</label>
                <select name="airlines" required>
                    <option value="">Pilih Maskapai</option>
                    <option value="citilink" {{ old('airlines') == 'citilink' ? 'selected' : '' }}>Citilink</option>
                    <option value="garuda" {{ old('airlines') == 'garuda' ? 'selected' : '' }}>Garuda</option>
                    <option value="lion" {{ old('airlines') == 'lion' ? 'selected' : '' }}>Lion</option>
                    <option value="sriwijaya" {{ old('airlines') == 'sriwijaya' ? 'selected' : '' }}>Sriwijaya</option>
                    <option value="qgcorner" {{ old('airlines') == 'qgcorner' ? 'selected' : '' }}>QGCorner</option>
                    <option value="transnusa" {{ old('airlines') == 'transnusa' ? 'selected' : '' }}>Transnusa</option>
                    <option value="pelni" {{ old('airlines') == 'pelni' ? 'selected' : '' }}>Pelni</option>
                    <option value="airasia" {{ old('airlines') == 'airasia' ? 'selected' : '' }}>Air Asia</option>
                    <option value="dlu" {{ old('airlines') == 'dlu' ? 'selected' : '' }}>DLU</option>
                </select>
            </div>
    
            <button type="submit" class="btn-update">SIMPAN</button>
        </form>
    
        <!-- Tampilkan Pesan Sukses/Error -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
    
        <div class="table-section">
            <h3>Data Mutasi Airlines</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Top Up</th>
                        <th>Transaksi</th>
                        <th>Insentif</th>
                        <th>Saldo</th>
                        <th>Keterangan</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allData as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tgl)->format('d/m/Y') }}</td>
                        <td>{{ $row->jam }}</td>
                        <td>Rp {{ $row->top_up ? number_format($row->top_up, 0, ',', '.') : '0' }}</td>
                        <td>Rp {{ $row->transaksi ? number_format($row->transaksi, 0, ',', '.') : '0' }}</td>
                        <td>Rp {{ $row->insentif ? number_format($row->insentif, 0, ',', '.') : '0' }}</td>
                        <td>Rp {{ number_format($row->saldo, 0, ',', '.') }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td>{{ $row->username }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </section>
    
    <script>
    // Hitung karakter keterangan
    document.addEventListener('DOMContentLoaded', function() {
        const keteranganInput = document.querySelector('input[name="keterangan"]');
        const charCount = document.querySelector('.char-count');
        
        if (keteranganInput && charCount) {
            // Update karakter count saat input
            keteranganInput.addEventListener('input', function(e) {
                const count = e.target.value.length;
                charCount.textContent = `${count}/30 karakter`;
            });
            
            // Set initial count
            const initialCount = keteranganInput.value.length;
            charCount.textContent = `${initialCount}/30 karakter`;
        }
    });
    </script>
    
    <style>
    .alert {
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        font-weight: bold;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .char-count {
        font-size: 12px;
        color: #666;
        display: block;
        margin-top: 5px;
    }
    
    .text-center {
        text-align: center;
        padding: 10px;
    }
    </style>
    
    </x-layouts.app>