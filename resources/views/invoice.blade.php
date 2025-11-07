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
        <div class="invoice-number">
    <table style="width:100%; border-collapse:collapse; line-height: 1.8;">
        <tr>
            <td style="width:50%;">
                <strong>Tanggal :</strong>
                <span id="invoiceDate"></span>
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                <strong>No. Invoice :</strong>
                {{ $invoice_number ?? 'INV-' . str_pad($invoiceId, 5, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                <strong>Nama Customer :</strong>
                <input type="text" id="customerName" placeholder="Masukkan nama customer" 
                    style="width:300px; border:none; outline:none; margin-left:5px;">
            </td>
        </tr>
    </table>
</div>


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
                    <td>{{ $data->kodeBooking }}</td>
                    <td>{{ $data->airlines }}</td>
                    <td>{{ $data->tglFlight1 }}</td>
                    <td>{{ $data->rute1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- BAGIAN TOTAL -->
        <table class="total-section" 
       style="width: 40%; border-collapse: collapse; margin-top: 20px; float: right; font-size: 14px;">
    <tr>
        <td style="text-align: right; padding-right: 10px; width: 60%;">
            <strong>Sub Total :</strong>
        </td>
        <td style="border: 1px solid #000; padding: 5px 10px; text-align: right;">
            Rp {{ number_format($subtotal, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td style="text-align: right; padding-right: 10px;">
            <strong>Biaya Materai :</strong>
        </td>
        <td style="border: 1px solid #000; padding: 5px 10px; text-align: right;">
            <input 
                type="text" 
                id="materaiInput" 
                value="{{ $materai }}" 
                style="width: 100%; border: none; outline: none; text-align: right;"
                inputmode="numeric"
                pattern="[0-9]*"
            >
        </td>
    </tr>
    <tr>
        <td style="text-align: right; padding-right: 10px;">
            <strong>Issued Fee :</strong>
        </td>
        <td style="border: 1px solid #000; padding: 5px 10px; text-align: right;">
            <input 
                type="text" 
                id="issuedFeeInput" 
                value="{{ $issued_fee ?? 25000 }}" 
                style="width: 100%; border: none; outline: none; text-align: right;"
                inputmode="numeric"
                pattern="[0-9]*"
            >
        </td>
    </tr>
    <tr>
        <td style="text-align: right; padding-right: 10px;">
            <strong>Total :</strong>
        </td>
        <td style="border: 1px solid #000; padding: 5px 10px; text-align: right;">
            <strong id="totalValue">Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </td>
    </tr>
</table>



        <!-- TERBILANG -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr>
                <td style="width: 10%; vertical-align: top; white-space: nowrap;">
                    <strong>Terbilang :</strong>
                </td>
                <td style="border-bottom: 1px solid #000; padding-bottom: 2px;" id="terbilangText">
                    {{ strtoupper($terbilang) }}
                </td>
            </tr>
        </table>

        <!-- BAGIAN TRANSFER -->
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top; width: 60%;">
                    <p><strong>Pembayaran melalui transfer ke:</strong><br>
                        Bank <input type="text" id="bankName" placeholder="Nama Bank" style="border:none; outline:none; width:150px;">
                        a/n <input type="text" id="accountName" placeholder="Nama Pemilik" style="border:none; outline:none; width:200px;"><br>
                        No. Rekening: <input type="text" id="accountNumber" placeholder="Nomor Rekening" style="border:none; outline:none; width:200px;">
                    </p>
                </td>
                <td style="text-align: right; vertical-align: top; width: 40%;">
                    <p><strong>Hormat Kami,</strong></p>
                    <br><br><br>
                    <p><strong>PT. Kupang Tour & Travel</strong></p>
                </td>
            </tr>
        </table>

        <p id="printedTime" style="text-align:right; font-size:12px; margin-top:20px;">
            Dicetak pada: <span id="realtime"></span>
        </p>

        <div class="invoice-buttons">
            <button id="btnBack" class="btn-back">Kembali</button>
            <button id="btnPrint" class="btn-print">Print</button>
        </div>
    </div>

    <script>
    document.getElementById('btnPrint').addEventListener('click', () => window.print());
    document.getElementById('btnBack').addEventListener('click', () => window.location.href = "{{ route('homeDb') }}");

    document.addEventListener('DOMContentLoaded', function() {
        const materaiInput = document.getElementById('materaiInput');
        const issuedInput = document.getElementById('issuedFeeInput');
        const totalValue = document.getElementById('totalValue');
        const terbilangField = document.getElementById('terbilangText');
        const subtotal = {{ $subtotal }};

        // Format rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Konversi angka ke teks terbilang
        function terbilangJS(angka) {
            const satuan = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
            angka = Math.floor(angka);
            let hasil = "";

            if (angka < 12) hasil = satuan[angka];
            else if (angka < 20) hasil = satuan[angka - 10] + " BELAS";
            else if (angka < 100) hasil = terbilangJS(Math.floor(angka / 10)) + " PULUH " + terbilangJS(angka % 10);
            else if (angka < 200) hasil = "SERATUS " + terbilangJS(angka - 100);
            else if (angka < 1000) hasil = terbilangJS(Math.floor(angka / 100)) + " RATUS " + terbilangJS(angka % 100);
            else if (angka < 2000) hasil = "SERIBU " + terbilangJS(angka - 1000);
            else if (angka < 1000000) hasil = terbilangJS(Math.floor(angka / 1000)) + " RIBU " + terbilangJS(angka % 1000);
            else if (angka < 1000000000) hasil = terbilangJS(Math.floor(angka / 1000000)) + " JUTA " + terbilangJS(angka % 1000000);
            return hasil.replace(/\s+/g, ' ').trim();
        }

        // Update total & terbilang
        function updateTotal() {
            const materai = parseInt(materaiInput.value) || 0;
            const issued = parseInt(issuedInput.value) || 0;
            const total = subtotal + materai + issued;
            totalValue.textContent = formatRupiah(total);
            terbilangField.textContent = terbilangJS(total).toUpperCase() + " RUPIAH";
        }

        const now = new Date();
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        document.getElementById('invoiceDate').textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

        function updateTime() {
            const now = new Date();
            const formatted = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')}`;
            document.getElementById('realtime').textContent = formatted;
        }
        setInterval(updateTime, 1000);
        updateTime();

        materaiInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); updateTotal(); }});
        issuedInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); updateTotal(); }});
        materaiInput.addEventListener('input', updateTotal);
        issuedInput.addEventListener('input', updateTotal);
    });
    </script>
</body>
</html>
