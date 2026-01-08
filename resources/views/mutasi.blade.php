<x-layouts.app title="Mutasi Tiket">

    <link rel="stylesheet" href="{{ asset('css/mutasi.css') }}">
    
    <section class="mutasi-tiket-section">
    <div class="mutasi-container">
        <h2>MUTASI TIKET</h2>
    
        <form action="{{ route('mutasi-tiket.topup') }}" method="POST" class="mutasi-tiket">
            @csrf
            <div class="form-group">
                <label>TANGGAL</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group refund-toggle">
                <label>
                    <input type="checkbox" id="is_refund" name="is_refund" value="1">
                    REFUND TIKET
                </label>
            </div>
    
            <div class="form-group">
                <label id="labelTopup">TOP UP</label>
                <input type="number" name="topup" id="nominal" placeholder="Masukkan nominal">
            </div>

            <div class="form-group" id="refundBookingContainer" style="display:none;">
                <label>KODE BOOKING</label>
                <select name="kode_booking" id="refund_kode_booking">
                    <option value="">-- Pilih Kode Booking --</option>
                    @foreach($tiketRefund as $t)
                        <option 
                            value="{{ $t->kode_booking }}"
                            data-jenis="{{ $t->jenisTiket->name_jenis }}"
                        >
                            {{ $t->kode_booking }}
                        </option>
                    @endforeach
                </select>
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
    
        <div class="filter-mutasi">
    <form method="GET" class="filter-mutasi-form">
        <label for="filter_jenis_tiket">FILTER JENIS TIKET</label>
        <select id="filter_jenis_tiket"
                name="jenis_tiket_id"
                onchange="this.form.submit()"
                class="text-uppercase">
            <option value="">-- Pilih Jenis Tiket --</option>
            @foreach ($jenisTiket as $j)
                <option value="{{ $j->id }}"
                    {{ $jenisTiketId == $j->id ? 'selected' : '' }}>
                    {{ $j->name_jenis }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<style>
/* ===============================
   FILTER MUTASI - COMPACT
================================ */
.filter-mutasi {
    margin: 35px auto 25px; /* lebih dekat & ringan */
    display: flex;
    justify-content: center;
}

.filter-mutasi-form {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 14px; /* DIPERKECIL */
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.filter-mutasi-form label {
    font-size: 12px; /* lebih kecil */
    font-weight: 600;
    color: #004d73;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

.filter-mutasi-form select {
    min-width: 160px; /* DIPERKECIL */
    padding: 6px 10px; /* DIPERKECIL */
    border-radius: 8px;
    border: 1.4px solid #c2e0f2;
    background: #ffffff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
}

.filter-mutasi-form select:focus {
    border-color: #5ac8fa;
    outline: none;
    box-shadow: 0 0 4px rgba(90, 200, 250, 0.3);
}
</style>




        @if ($mutasi->isEmpty())
    <div class="text-center mt-3 fw-bold">
        Belum ada mutasi untuk jenis tiket ini
    </div>
@else

<div class="table-section">
    <h3>Riwayat Mutasi Tiket</h3>

    <div class="table-responsive">
        <table class="mutasi-table">
            <thead>
                <tr>
                    <th class="col-date">Tanggal</th>
                    <th class="col-trx text-center">Transaksi</th>
                    <th class="col-trx text-center">Saldo</th>
                    <th class="col-desc text-right">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mutasi as $m)
                <tr>
                    <td class="col-date">
                        {{ \Carbon\Carbon::parse($m->tanggal)->format('d/m/Y') }}
                    </td>

                    <td class="col-trx text-center">
                        @if ($m->transaksi > 0)
                            <span class="trx-plus">
                                +Rp {{ number_format($m->transaksi, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="trx-minus">
                                -Rp {{ number_format(abs($m->transaksi), 0, ',', '.') }}
                            </span>
                        @endif
                    </td>
                    <td class="col-trx text-center {{ $m->saldo < 0 ? 'text-danger' : '' }}">
                        {{ ($m->saldo < 0 ? '-' : '') . 'Rp ' . number_format(abs($m->saldo), 0, ',', '.') }}
                    </td>
                    <td class="col-desc text-right">
                        {{ $m->keterangan }}
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
        const isRefund = document.getElementById('is_refund');
        const labelTopup = document.getElementById('labelTopup');
        const jenisBayar = document.getElementById('jenis_bayar_id');
        const bankContainer = document.getElementById('bankContainer');
        const refundBooking = document.getElementById('refundBookingContainer');
        const keterangan = document.querySelector('input[name="keterangan"]');
        const kodeBookingSelect = document.getElementById('refund_kode_booking');
        
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

        isRefund.addEventListener('change', function () {

            if (this.checked) {
                // MODE REFUND
                labelTopup.innerText = 'REFUND';

                jenisBayar.closest('.form-group').style.display = 'none';
                jenisBayar.required = false;

                bankContainer.style.display = 'none';

                refundBooking.style.display = 'block';

                keterangan.readOnly = true;

            } else {
                // MODE TOP UP NORMAL
                labelTopup.innerText = 'TOP UP';

                jenisBayar.closest('.form-group').style.display = 'block';
                jenisBayar.required = true;

                refundBooking.style.display = 'none';

                keterangan.readOnly = false;
                keterangan.value = '';
            }
        });

        kodeBookingSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const jenis = selected.dataset.jenis;
            const kode = this.value;

            if (kode && jenis) {
                keterangan.value = `Refund Tiket ${jenis} ${kode}`;
            }
        });

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