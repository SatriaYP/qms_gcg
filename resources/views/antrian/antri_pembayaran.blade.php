<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            pointer-events: none; /* Nonaktifkan klik di seluruh halaman */
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 500px;
            text-align: center;
            pointer-events: auto; /* Aktifkan klik hanya di dalam container */
        }
        h2 {
            color: #4CAF50;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 1.2rem;
            border: 2px solid #ccc;
            border-radius: 8px;
            outline: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h2>Silahkan Scan Barcode QRCode Anda</h2>
        <input type="text" id="scan-input" maxlength="5" autofocus>
    </div>

    <script>
        document.getElementById("scan-input").focus();

        document.getElementById("scan-input").addEventListener("input", function () {
            const inputField = this;
            if (inputField.value.length === 5) {
                const queueNumber = inputField.value;

                // Kirim data ke server menggunakan AJAX
                fetch('/antrian/queue_pembayaran', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ queue_number_pembayaran: queueNumber })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Nomor antrian berhasil ditambahkan.');
                        inputField.value = ''; // Kosongkan field setelah berhasil
                    } else {
                        alert('Gagal menambahkan nomor antrian.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan, silakan coba lagi.');
                });
            }
        });

        document.addEventListener("click", function (event) {
            const inputField = document.getElementById("scan-input");
            if (event.target !== inputField) {
                inputField.focus();
            }
        });
    </script>
</body>
</html>
