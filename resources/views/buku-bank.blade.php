<x-layouts.app title="Buku Bank">
    <link rel="stylesheet" href="{{ asset('css/bukuBank.css') }}">
    
    <section class="buku-bank-container">
        <div class="buku-bank-card">
            <h2 class="title">Buku Bank</h2>
    
            <!-- Form Input Data -->
            <form action="{{ route('buku-bank.setor') }}" method="POST" id="form-setor">
                @csrf
                <div class="form-section">
                    <div class="form-left">
                        <label for="tanggal">Tanggal</label>
                        <input type="datetime-local" id="tanggal" name="tanggal" value="{{ date('Y-m-d\TH:i') }}" required>
                        @error('tanggal') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="bank_id">BANK</label>
                        <select id="bank_id" name="bank_id" class="text-uppercase">
                            <option value="">-- Pilih Bank --</option>
                            @if(isset($banks) && $banks->count() > 0)
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('bank') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="nominal_display">Nominal Setor</label>
                        <div style="position: relative;">
                            <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#555; font-weight:600; pointer-events:none;">Rp</span>
                            <input 
                                type="text" 
                                id="nominal_display" 
                                placeholder="0,00"
                                autocomplete="off"
                                style="padding-left: 36px; text-align: right;"
                                required
                            >
                            <!-- Input hidden yang dikirim ke controller -->
                            <input type="hidden" id="nominal" name="nominal">
                        </div>
                        @error('nominal') <span class="error">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="form-right">
                        <button type="submit" class="btn-action save">Setor</button>
                        <button type="button" class="btn-action delete" onclick="resetForm()">Hapus</button>
                    </div>
                </div>
            </form>
    
            <hr class="divider">
    
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
    
            <!-- Tabel Data -->
            <form method="GET" action="{{ route('buku-bank.index') }}" class="filter-form">
                <label for="bank_filter">Pilih Bank</label>
                <select name="bank_id" id="bank_filter" onchange="this.form.submit()">
                    @foreach ($bankList as $bank)
                        <option value="{{ $bank->id }}" {{ $bank->id == $bankId ? 'selected' : '' }}>
                            {{ $bank->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <style>
                .filter-form {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    margin-bottom: 16px;
                }
                .filter-form label {
                    font-size: 14px;
                    font-weight: 600;
                    color: #1f3a3a;
                    white-space: nowrap;
                }
                .filter-form select {
                    padding: 8px 14px;
                    min-width: 140px;
                    border-radius: 10px;
                    border: 1.5px solid #cfe6f3;
                    background-color: #ffffff;
                    font-size: 14px;
                    font-weight: 500;
                    color: #333;
                    cursor: pointer;
                    transition: all 0.25s ease;
                }
                .filter-form select:focus {
                    outline: none;
                    border-color: #2d9cdb;
                    box-shadow: 0 0 0 3px rgba(45, 156, 219, 0.25);
                }
                .filter-form select:hover {
                    border-color: #2d9cdb;
                }
                .btn-delete {
                    background-color: #dc3545;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 3px;
                    cursor: pointer;
                    font-size: 12px;
                }
                .btn-delete:hover { background-color: #c82333; }
                .text-center { text-align: center; }
            </style>

            <div class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Kredit</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuBank as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $item->keterangan }}</td>

                                {{-- DEBIT = UANG MASUK --}}
                                <td class="text-right text-success">
                                    @if ($item->debit > 0)
                                        Rp {{ number_format($item->debit, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- KREDIT = UANG KELUAR --}}
                                <td class="text-right text-danger">
                                    @if ($item->kredit > 0)
                                        Rp {{ number_format($item->kredit, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-right {{ $item->saldo < 0 ? 'text-danger' : '' }}">
                                    @if ($item->saldo < 0)
                                        -Rp {{ number_format(abs($item->saldo), 2, ',', '.') }}
                                    @else
                                        Rp {{ number_format($item->saldo, 2, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </section>
    
    <script>
    /**
     * Format angka ke format Rupiah dengan pemisah ribuan titik dan koma desimal
     * Contoh: 1500000.50 → "1.500.000,50"
     */
    function formatRupiah(value) {
        // Pisahkan bagian integer dan desimal
        let parts = value.toString().split('.');
        let intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        let decPart = parts[1] !== undefined ? ',' + parts[1].padEnd(2, '0').slice(0, 2) : ',00';
        return intPart + decPart;
    }

    /**
     * Hapus semua karakter non-digit dan non-koma dari input display,
     * lalu simpan nilai raw (angka desimal) ke hidden input.
     */
    const displayInput = document.getElementById('nominal_display');
    const hiddenInput  = document.getElementById('nominal');

    displayInput.addEventListener('input', function () {
        // Ambil raw: hapus semua kecuali digit dan koma pertama
        let raw = this.value.replace(/[^\d,]/g, '');

        // Hanya boleh satu koma
        let parts = raw.split(',');
        if (parts.length > 2) {
            raw = parts[0] + ',' + parts.slice(1).join('');
            parts = raw.split(',');
        }

        // Batasi desimal maksimal 2 digit
        if (parts[1] !== undefined) {
            parts[1] = parts[1].slice(0, 2);
        }

        // Format ribuan di bagian integer
        let intFormatted = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        this.value = parts[1] !== undefined
            ? intFormatted + ',' + parts[1]
            : intFormatted;

        // Simpan ke hidden input dengan titik sebagai desimal (untuk PHP)
        let rawValue = parts[0].replace(/\./g, '');
        if (parts[1] !== undefined) {
            rawValue += '.' + parts[1];
        }
        hiddenInput.value = rawValue || '';
    });

    displayInput.addEventListener('blur', function () {
        if (!hiddenInput.value) return;
        let num = parseFloat(hiddenInput.value);
        if (!isNaN(num)) {
            this.value = formatRupiah(num.toFixed(2));
            hiddenInput.value = num.toFixed(2);
        }
    });

    // Validasi form sebelum submit
    document.getElementById('form-setor').addEventListener('submit', function (e) {
        if (!hiddenInput.value || parseFloat(hiddenInput.value) <= 0) {
            e.preventDefault();
            alert('Nominal setor harus diisi dan lebih dari 0.');
            displayInput.focus();
        }
    });

    function resetForm() {
        document.getElementById('form-setor').reset();
        displayInput.value = '';
        hiddenInput.value  = '';
    }
    </script>

</x-layouts.app>
