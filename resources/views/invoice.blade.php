<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $data->nama }}</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="{{ asset('images/logo_7.png') }}" alt="Logo Kupang Travel" class="logo">
            <div class="header-text">
                <h1>PT. Kupang Tour & Travel</h1>
                <p>Jl. El Tari No.7, Kupang - NTT</p>
                <p>Telp: (0380) 123456 • Email: kupangtravel@gmail.com</p>
            </div>
        </div>

        <hr>

        <div class="invoice-body">
            <h2>INVOICE TIKET</h2>

            <div class="invoice-info">
                <p><strong>No. Invoice:</strong> INV-{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="invoice-grid">
                <div>
                    <p><strong>Tanggal Issued:</strong> {{ $data->tgl_issued }}</p>
                    <p><strong>Kode Booking:</strong> {{ $data->kode_booking }}</p>
                    <p><strong>Nama:</strong> {{ $data->nama }}</p>
                    <p><strong>Airlines:</strong> {{ $data->airlines }}</p>
                </div>

                <div>
                    <p><strong>Rute 1:</strong> {{ $data->rute1 }}</p>
                    <p><strong>Tanggal Flight 1:</strong> {{ $data->tgl_flight1 }}</p>
                    @if($data->rute2)
                    <p><strong>Rute 2:</strong> {{ $data->rute2 }}</p>
                    <p><strong>Tanggal Flight 2:</strong> {{ $data->tgl_flight2 }}</p>
                    @endif
                </div>
            </div>

            <hr>

            <div class="price-section">
                <table>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>NTA</th>
                        <td>Rp {{ number_format($data->nta, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Diskon</th>
                        <td>Rp {{ number_format($data->diskon, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Komisi</th>
                        <td>Rp {{ number_format($data->komisi, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Pembayaran</th>
                        <td>
                            @if(strtolower($data->pembayaran) === 'lunas')
                                <span class="status-lunas">Lunas</span>
                            @else
                                <span class="status-belum">{{ $data->pembayaran }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            @if($data->keterangan)
            <div class="note-section">
                <p><strong>Keterangan:</strong> {{ $data->keterangan }}</p>
            </div>
            @endif

            <div class="invoice-footer">
                <p class="printed-date">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                <div class="invoice-buttons">
                    <button id="btnBack" class="btn-back">⬅️ Kembali</button>
                    <button id="btnPrint" class="btn-print">Print Invoice</button>
                </div>
            </div>
        </div>
    </div>

    <div id="printModal" class="modal">
        <div class="modal-content">
            <p>Print <strong>Invoice</strong>?</p>
            <div class="modal-buttons">
                <button id="yesPrint" class="btn-yes">YES</button>
                <button id="noPrint" class="btn-no">NO</button>
            </div>
        </div>
    </div>

    <script>
        const printBtn = document.getElementById('btnPrint');
        const backBtn = document.getElementById('btnBack');
        const modal = document.getElementById('printModal');
        const yesPrint = document.getElementById('yesPrint');
        const noPrint = document.getElementById('noPrint');

        printBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        noPrint.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        yesPrint.addEventListener('click', () => {
            modal.style.display = 'none';
            window.print();
        });

        backBtn.addEventListener('click', () => {
            window.location.href = "{{ route('homeDb') }}";
        });
    </script>
</body>
</html>
