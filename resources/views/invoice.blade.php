<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - PT. Kupang Tour & Travel</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>
    <div class="invoice-container">
        <div class="header-top">
            <div class="header-left">
                <img src="{{ asset('images/logo_7.png') }}" alt="Logo Kupang Travel">
            </div>
            <div class="header-right">
                <h2>PT. Kupang Tour & Travel</h2>
                <p>Jl. Garuda No. 04, Kupang - NTT</p>
                <p>Telp/Fax : 081237481987 - 081339005472 / (0380) 8438795</p>
                <p>Email: kupang_tt@yahoo.com</p>
            </div>
        </div>

        <hr>

        <div class="invoice-title">INVOICE TIKET PESAWAT</div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Pesawat</th>
                    <th>Tanggal</th>
                    <th>Rute</th>
                    <th>Nama Penumpang</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tikets as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->kode_booking }}</td>
                    <td>{{ $data->airlines }}</td>
                    <td>{{ $data->tgl_flight1 }}</td>
                    <td>{{ $data->rute1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total-section">
            <tr>
                <td class="label">Sub Total :</td>
                <td class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Issued Fee :</td>
                <td class="value">Rp {{ number_format($issued_fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Biaya Materai :</td>
                <td class="value">Rp {{ number_format($materai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Total :</strong></td>
                <td class="value"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <div class="footer-section">
            <p><strong>Terbilang:</strong> {{ $terbilang }}</p>
            <p><strong>Pembayaran melalui transfer ke:</strong></p>
            <p><strong>Bank Mandiri</strong> a/n Ladyana Elie</p>
            <p>No. Rekening: <strong>161.0001078158</strong></p>
        </div>

        <div class="signature-section">
            <div class="signature-left">
                <p>Hormat Kami,</p>
                <br><br><br>
                <p><strong>PT. Kupang Tour & Travel</strong></p>
            </div>
        </div>

        <p class="printed-date">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

        <div class="invoice-buttons">
            <button id="btnBack" class="btn-back">Kembali</button>
            <button id="btnPrint" class="btn-print">Print</button>
        </div>
    </div>

    <script>
        document.getElementById('btnPrint').addEventListener('click', () => {
            window.print();
        });

        document.getElementById('btnBack').addEventListener('click', () => {
            window.location.href = "{{ route('homeDb') }}";
        });
    </script>
</body>
</html>
