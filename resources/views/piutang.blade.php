<x-layouts.app title="Piutang - PT. Kupang Tour & Travel">
<link rel="stylesheet" href="{{ asset('css/evi.css') }}">

<section class="evi-section">
    <div class="card-container">
        <form id="formEvi" method="POST" action="{{ route('evi.store') }}">
            @csrf
            <div class="form-header">
                <div class="form-group">
                    <label for="tgl">TANGGAL</label>
                    <input type="date" id="tgl" name="tgl" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="topup">TOP UP</label>
                    <input type="number" id="topup" name="topup" class="form-control" placeholder="Masukkan jumlah" required>
                </div>

                <div class="form-group">
                    <label for="keterangan">KETERANGAN</label>
                    <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan..." required>
                </div>

                <div class="form-group saldo-group">
                    <label for="saldo">SISA SALDO</label>
                    <input type="text" id="saldo" name="saldo" class="form-control saldo" readonly value="{{ $saldo ?? '' }}">
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
                <a href="{{ route('evi.export') }}" id="exportBtn" class="btn btn-green">EXPORT EXCEL</a>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table-evi">
            <thead>
                <tr>
                    <th>TGL_ISSUED</th>
                    <th>JAM</th>
                    <th>KODE BOOKING</th>
                    <th>AIRLINES</th>
                    <th>NAMA</th>
                    <th>RUTE1</th>
                    <th>TGL_FLIGHT1</th>
                    <th>RUTE2</th>
                    <th>TGL_FLIGHT2</th>
                    <th>HARGA</th>
                    <th>NTA</th>
                    <th>TOP_UP</th>
                    <th>SALDO</th>
                    <th>KETERANGAN</th>
                    <th>USER</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="dataEvi">
                @forelse ($data as $row)
                    <tr data-id="{{ $row->id }}">
                        <td>{{ $row->tgl_issued }}</td>
                        <td>{{ $row->jam }}</td>
                        <td>{{ $row->kode_booking }}</td>
                        <td>{{ $row->airlines }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->rute1 }}</td>
                        <td>{{ $row->tgl_flight1 }}</td>
                        <td>{{ $row->rute2 }}</td>
                        <td>{{ $row->tgl_flight2 }}</td>
                        <td>{{ number_format($row->harga) }}</td>
                        <td>{{ number_format($row->nta) }}</td>
                        <td>{{ number_format($row->top_up) }}</td>
                        <td>{{ number_format($row->saldo) }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td>{{ $row->user }}</td>
                        <td>
                            <button class="btn btn-delete" data-id="{{ $row->id }}">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="16" class="text-center">Tidak ada data</td></tr>
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
    let selectedId = null;

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) {
            selectedId = e.target.dataset.id;
            deleteModal.style.display = 'flex';
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
