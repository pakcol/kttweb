<x-layouts.app title="Subagent - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/evi.css') }}">

<section class="evi-section">
    <div class="card-container">
        <form id="formEvi" method="POST" action="{{ route('subagent.topup') }}">
            @csrf
            <div class="form-header">
                <div class="form-group">
                <select name="subagent_id" id="subagent_id" required>
                    <option value="">-- Pilih Subagent --</option>

                    @forelse($subagents as $subagent)
                        <option 
                            value="{{ $subagent->id }}"
                            data-saldo="{{ $subagent->saldo }}"
                >
                            {{ $subagent->nama }}
                        </option>
                    @empty
                        <option value="" disabled>Data subagent belum tersedia</option>
                    @endforelse
                </select>
            </div>

                <div class="form-group">
                    <label for="nominal">TOP UP</label>
                    <input type="number" id="nominal" name="nominal" class="form-control" placeholder="Masukkan jumlah" required>
                </div>

                                
                {{-- JENIS BAYAR --}}
                <div class="form-group">
                    <select name="jenis_bayar_id" id="jenis_bayar_id" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        @foreach($jenisBayar as $jb)
                            <option value="{{ $jb->id }}">
                                {{ $jb->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BANK --}}
                <div class="form-group" id="bankContainer" style="display:none;">
                    <select name="bank_id" id="bank_id">
                        <option value="">-- Pilih Bank --</option>
                        @foreach($bank as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group saldo-group">
                    <label for="saldo">SISA SALDO</label>
                    <input type="text" id="saldoSubagent" name="saldo" class="form-control saldo" readonly>
                </div>

                <div class="btn-group">
                    <button type="submit" id="saveBtn" class="btn btn-red">SAVE</button>
                </div>
            </div>
        </form>

        <div class="filter-section">
            <div class="form-group">
                <label for="dari">DARI TANGGAL</label>
                <input type="date" id="dari" class="form-control">
            </div>

            <div class="form-group">
                <label for="sampai">SAMPAI TANGGAL</label>
                <input type="date" id="sampai" class="form-control">
            </div>

            <div class="btn-group">
                <button id="tampilBtn" class="btn btn-blue">TAMPIL</button>
                <a href="{{ route('subagent.export') }}" id="exportBtn" class="btn btn-green">EXPORT EXCEL</a>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table-evi">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Subagent</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody id="dataEvi">
                @forelse($histories as $row)
                <tr>
                    <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $row->subagent->name ?? '-' }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>Rp {{ number_format($row->debit,0,',','.') }}</td>
                    <td>Rp {{ number_format($row->kredit,0,',','.') }}</td>
                    <td>Rp {{ number_format($row->saldo,0,',','.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Belum ada data history subagent</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</section>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Yakin ingin menghapus data ini?</h3>
        <div class="modal-btn">
            <button id="yesDelete" class="btn btn-red">YES</button>
            <button id="noDelete" class="btn btn-blue">NO</button>
        </div>
    </div>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <h3>Data berhasil dihapus!</h3>
        <div class="modal-btn">
            <button id="okSuccess" class="btn btn-green">OK</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    const successModal = document.getElementById('successModal');

    const subagentSelect = document.getElementById('subagent_id');
    const saldoInput = document.getElementById('saldoSubagent');

    const jenisBayar = document.getElementById('jenis_bayar_id');
    const bankContainer = document.getElementById('bankContainer');
    const bankSelect = document.getElementById('bank_id');

    let selectedId = null;

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) {
            selectedId = e.target.dataset.id;
            deleteModal.style.display = 'flex';
        }
    });

    subagentSelect.addEventListener('change', function () {
        const saldo = this.options[this.selectedIndex].dataset.saldo || 0;
        saldoInput.value = 'Rp ' + Number(saldo).toLocaleString('id-ID');
    });

    jenisBayar.addEventListener('change', function () {
        if (this.value === '1') { // BANK
            bankContainer.style.display = 'block';
            bankSelect.required = true;
        } else {
            bankContainer.style.display = 'none';
            bankSelect.required = false;
            bankSelect.value = '';
        }
    });

    document.getElementById('yesDelete').onclick = function() {
        fetch(`/evi/${selectedId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            document.querySelector(`tr[data-id='${selectedId}']`).remove();
            deleteModal.style.display = 'none';
            successModal.style.display = 'flex';
        });
    };

    document.getElementById('noDelete').onclick = function() {
        deleteModal.style.display = 'none';
    };

    document.getElementById('okSuccess').onclick = function() {
        successModal.style.display = 'none';
    };


    document.getElementById('tampilBtn').onclick = function() {
        const dari = document.getElementById('dari').value;
        const sampai = document.getElementById('sampai').value;

        fetch(`/evi/search?dari=${dari}&sampai=${sampai}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('dataEvi');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="16" class="text-center">Tidak ada data</td></tr>';
                } else {
                    data.forEach(row => {
                        tbody.innerHTML += `
                            <tr data-id="${row.id}">
                                <td>${row.tgl_issued}</td>
                                <td>${row.jam}</td>
                                <td>${row.kode_booking}</td>
                                <td>${row.airlines}</td>
                                <td>${row.nama}</td>
                                <td>${row.rute1}</td>
                                <td>${row.tgl_flight1}</td>
                                <td>${row.rute2}</td>
                                <td>${row.tgl_flight2}</td>
                                <td>${row.harga}</td>
                                <td>${row.nta}</td>
                                <td>${row.top_up}</td>
                                <td>${row.saldo}</td>
                                <td>${row.keterangan}</td>
                                <td>${row.user}</td>
                                <td><button class="btn btn-delete" data-id="${row.id}">Delete</button></td>
                            </tr>`;
                    });
                }
            });
    };
});
</script>
</x-layouts.app>
