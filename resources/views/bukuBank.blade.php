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
            <div class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bank</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banks as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/Y') }}</td>
                                <td>{{ $item->name }}</td>
                                <td>Rp {{ number_format($item->saldo, 2, ',', '.') }}</td>
                                <td>
                                    <button class="btn-delete" onclick="confirmDelete({{ $item->id }}, '{{ $item->bank }}')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Tambahkan form untuk hapus -->
                <form id="deleteForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="bank" id="deleteBank">
                </form>
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