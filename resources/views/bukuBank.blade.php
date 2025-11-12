<x-layouts.app title="Buku Bank">
    <link rel="stylesheet" href="{{ asset('css/bukuBank.css') }}">
    
    <section class="buku-bank-container">
        <div class="buku-bank-card">
            <h2 class="title">Buku Bank</h2>
    
            <!-- Form Input Data -->
            <form action="{{ route('buku-bank.store') }}" method="POST">
                @csrf
                <div class="form-section">
                    <div class="form-left">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                        @error('tanggal') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="bank">Bank</label>
                        <select id="bank" name="bank" required>
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA" {{ old('bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="BTN" {{ old('bank') == 'BTN' ? 'selected' : '' }}>BTN</option>
                            <option value="BNI" {{ old('bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="MANDIRI" {{ old('bank') == 'MANDIRI' ? 'selected' : '' }}>MANDIRI</option>
                            <option value="BRI" {{ old('bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                        </select>
                        @error('bank') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="debit">Debit</label>
                        <input type="text" id="debit" name="debit" value="{{ old('debit') }}" placeholder="Masukkan jumlah debit" oninput="formatCurrency(this)" required>
                        @error('debit') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="kredit">Kredit</label>
                        <input type="text" id="kredit" name="kredit" value="{{ old('kredit') }}" placeholder="Masukkan jumlah kredit" oninput="formatCurrency(this)" required>
                        @error('kredit') <span class="error">{{ $message }}</span> @enderror
    
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" placeholder="Masukkan keterangan" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan') <span class="error">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="form-right">
                        <button type="submit" class="btn-action save">Simpan</button>
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
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allData as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/Y') }}</td>
                                <td>{{ $item->bank }}</td>
                                <td>Rp {{ $item->debit ? number_format($item->debit, 2, ',', '.') : '-' }}</td>
                                <td>Rp {{ $item->credit ? number_format($item->credit, 2, ',', '.') : '-' }}</td>
                                <td>Rp {{ number_format($item->saldo, 2, ',', '.') }}</td>
                                <td>{{ $item->keterangan }}</td>
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
    // Fungsi konfirmasi hapus
    function confirmDelete(id, bank) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            // Set form action dan input values
            const form = document.getElementById('deleteForm');
            form.action = `/buku-bank/${id}`;
            document.getElementById('deleteBank').value = bank;
            
            // Submit form
            form.submit();
        }
    }

    // Fungsi untuk hapus multiple data (jika perlu checkbox)
    function deleteSelected() {
        const selectedIds = [];
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
        
        if (checkboxes.length === 0) {
            alert('Pilih data yang akan dihapus');
            return;
        }
        
        if (confirm(`Apakah Anda yakin ingin menghapus ${checkboxes.length} data?`)) {
            checkboxes.forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });
            
            const bank = document.getElementById('bank').value; // atau dari data yang dipilih
            
            // Kirim request AJAX untuk hapus multiple
            fetch('/buku-bank/delete-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedIds,
                    bank: bank
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
    }
    // Fungsi untuk format currency
    function formatCurrency(input) {
        // Hapus karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format dengan titik sebagai pemisah ribuan
        if (value.length > 0) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        input.value = value;
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