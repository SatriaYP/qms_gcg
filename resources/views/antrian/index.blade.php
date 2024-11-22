<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Klinik</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f8;
            color: #333;
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
            width: 90%;
            max-width: 500px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            font-size: 1.5rem;
            padding: 12px;
            background-color: #e9f5ee;
            color: #333;
            margin-bottom: 10px;
            border-radius: 8px;
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
        .secondary-button {
            background-color: #f44336;
            margin-top: 20px;
        }
        .secondary-button:hover {
            background-color: #e53935;
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
        
        @if (!empty($queue_numbers))
            <ul>
                @foreach ($queue_numbers as $queue_number)
                    <li>{{ $queue_number }}</li>
                @endforeach
            </ul>
        @else
            <p style="font-size: 1.2rem;">Belum ada nomor antrian.</p>
        @endif

        <form action="/antrian/pilih-jenis-pasien" method="POST">
            @csrf
            <button type="submit">Tambah Antrian Baru</button>
        </form>

        <form action="/antrian/reset" method="GET">
            <button type="submit" class="secondary-button">Reset Semua Antrian</button>
        </form>
    </div>
</body>
</html>
