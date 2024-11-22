<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Antrian</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 90%;
            max-width: 500px;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 3rem;
            margin: 10px 0;
            color: #333;
        }
        .qr-code {
            margin: 20px 0;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nomor Antrian Anda</h2>
        <h1>{{ $queue_number }}</h1>
        <div class="qr-code">{!! $qr_code !!}</div>
        <button id="printButton" onclick="printQRCode()">Cetak QR CODE</button>
        <form action="/antrian" method="GET">
            <button id="input_baru" type="submit">Input Antrian Baru</button>
        </form>
    </div>
    <script>
    function printQRCode() {
        // Menyembunyikan tombol "Cetak QR CODE" sebelum mencetak
        document.getElementById('printButton').style.display = 'none';
        document.getElementById('input_baru').style.display = 'none';

        // Memulai proses cetak
        window.print();

        // Menampilkan kembali tombol "Cetak QR CODE" setelah cetak selesai
        setTimeout(() => {
            document.getElementById('printButton').style.display = 'block';
            document.getElementById('input_baru').style.display = 'block';
        }, 1000); // Tambahkan sedikit delay untuk memastikan cetakan selesai
    }
</script>

</body>
</html>
