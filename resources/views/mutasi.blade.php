<x-layouts.app title="Mutasi Airlines">

    <link rel="stylesheet" href="{{ asset('css/mutasi.css') }}">
    
    <section class="mutasi-tiket-section">
    <div class="mutasi-container">
        <h2>MUTASI AIRLINES</h2>
    
        <form action="{{ route('mutasi-tiket.topup') }}" method="POST" class="mutasi-tiket">
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
                <label for="jenis_bayar_id">JENIS PEMBAYARAN*</label>
                <select id="jenis_bayar_id" name="jenis_bayar_id" class="text-uppercase" required>
                    <option value="">-- Pilih Jenis Pembayaran --</option>
                    @if(isset($jenisBayar) && $jenisBayar->count() > 0)
                        @foreach($jenisBayar as $jenis)
                            <option value="{{ $jenis->id }}">
                                {{ $jenis->jenis }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Data jenis pembayaran tidak ditemukan</option>
                    @endif
                </select>
            </div>
            
            <div class="form-group" id="bankContainer" style="display: none;">
                <label for="bank_id">BANK</label>
                <select id="bank_id" name="bank_id" class="text-uppercase">
                    <option value="">-- Pilih Bank --</option>
                    @if(isset($bank) && $bank->count() > 0)
                        @foreach($bank as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label>JENIS TIKET</label>
                <select id="jenis_tiket_id" name="jenis_tiket_id" class="text-uppercase" required>
                    <option value="">-- Pilih Jenis Tiket --</option>
                    @if(isset($jenisTiket) && $jenisTiket->count() > 0)
                        @foreach($jenisTiket as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->name_jenis }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>Data jenis tiket tidak ditemukan</option>
                    @endif
                </select>
            </div>
    
            <div class="form-group">
                <label>KETERANGAN</label>
                <input type="text" name="keterangan" placeholder="Contoh: Pengeluaran tiket, bonus, dll" value="{{ old('keterangan') }}" maxlength="30">
                <small class="char-count">0/30 karakter</small>
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
    
        <form method="GET">
            <select name="jenis_tiket_id" onchange="this.form.submit()">
                <option value="">-- Pilih Jenis Tiket --</option>
                @foreach ($jenisTiket as $j)
                    <option value="{{ $j->id }}"
                        {{ $jenisTiketId == $j->id ? 'selected' : '' }}>
                        {{ $j->name_jenis }}
                    </option>
                @endforeach
            </select>
        </form>

        @if ($mutasi->isEmpty())
    <div class="text-center mt-3 fw-bold">
        Belum ada mutasi untuk jenis tiket ini
    </div>
@else

<div class="table-section">
    <h3>Riwayat Mutasi Airlines</h3>

    <div class="table-responsive">
        <table class="mutasi-table">
            <thead>
                <tr>
                    <th class="col-date">Tanggal</th>
                    <th class="col-trx text-center">Transaksi</th>
                    <th class="col-desc text-right">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mutasi as $m)
                <tr>
                    <td class="col-date">
                        {{ \Carbon\Carbon::parse($m['tanggal'])->format('d-m-Y') }}
                    </td>

                    <td class="col-trx text-center">
                        @if ($m['transaksi'] > 0)
                            <span class="trx-plus">
                                +Rp {{ number_format($m['transaksi'], 0, ',', '.') }}
                            </span>
                        @else
                            <span class="trx-minus">
                                -Rp {{ number_format(abs($m['transaksi']), 0, ',', '.') }}
                            </span>
                        @endif
                    </td>

                    <td class="col-desc text-right">
                        {{ $m['keterangan'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px; font-weight:700; color:#004d73;">
        Saldo Tiket:
        Rp {{ number_format($saldoTiket, 0, ',', '.') }}
    </div>
</div>

@endif


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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenisBayar = document.getElementById('jenis_bayar_id');
            const bankContainer = document.getElementById('bankContainer');
            const bankSelect = document.getElementById('bank_id');

            function toggleBank() {
                const jenis = jenisBayar.value;

                // Asumsi: 1 = TRANSFER / BANK
                if (jenis === '1') {
                    bankContainer.style.display = 'block';
                    bankSelect.required = true;
                } else {
                    bankContainer.style.display = 'none';
                    bankSelect.required = false;
                    bankSelect.value = '';
                }
            }

            jenisBayar.addEventListener('change', toggleBank);
            toggleBank(); // initial state
        });
    </script>
    
    </x-layouts.app>