<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <style>
        body, .container {
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
            margin-bottom: 20px;
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
        button:not(:last-child) {
            margin-bottom: 15px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="{{ route('antrian.store_jenis_pembayaran') }}" method="POST">
            @csrf
            <h2>Pilih Metode Pembayaran</h2>
            <input type="hidden" name="jenis_pasien" value="{{ $jenis_pasien }}">
            <button type="submit" name="metode_pembayaran" value="0">Asuransi</button>
            <button type="submit" name="metode_pembayaran" value="1">BPJS</button>
            <button type="submit" name="metode_pembayaran" value="2">Mandiri</button>
        </form>
    </div>
</body>
</html>
