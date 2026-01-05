<x-layouts.app title="Buku Bank">
    <link rel="stylesheet" href="{{ asset('css/bukuBank.css') }}">
    
    <section class="buku-bank-container">
        <div class="buku-bank-card">
            <h2 class="title">Buku Bank</h2>
    
            <!-- Form Input Data -->
            <form action="{{ route('buku-bank.topup') }}" method="POST">
                @csrf
                <div class="form-section">
                    <div class="form-left">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
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
    
                        <label for="nominal">Nominal Top Up</label>
                        <input type="number" id="nominal" name="nominal" value="{{ old('nominal') }}" placeholder="Masukkan jumlah nominal top up" required>
                        @error('nominal') <span class="error">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="form-right">
                        <button type="submit" class="btn-action save">Top Up</button>
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
                <label for="bank_id">Pilih Bank</label>
                <select name="bank_id" id="bank_id" onchange="this.form.submit()">
                    @foreach ($bankList as $bank)
                        <option value="{{ $bank->id }}" {{ $bank->id == $bankId ? 'selected' : '' }}>
                            {{ $bank->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th class="text-right">Kredit</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuBank as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td class="text-right text-success">
                                    @if ($item->kredit > 0)
                                        Rp {{ number_format($item->kredit, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right text-danger">
                                    @if ($item->debit > 0)
                                        Rp {{ number_format($item->debit, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right {{ $item->saldo < 0 ? 'text-danger' : '' }}">
                                    @if ($item->saldo < 0)
                                        -Rp {{ number_format(abs($item->saldo), 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($item->saldo, 0, ',', '.') }}
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
    // Fungsi untuk format currency
    function formatCurrency(input) {
        
        // Format dengan titik sebagai pemisah ribuan
        if (value.length > 0) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        input.value = input.value.replace(/[^\d]/g, '');
    }
    
    // Fungsi reset form
    function resetForm() {
        document.querySelector('form').reset();
    }
    </script>
    <style>
        .btn-delete {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
}

.btn-delete:hover {
    background-color: #c82333;
}

.text-center {
    text-align: center;
}
        </style>
    </x-layouts.app>