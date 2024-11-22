<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Farmasi</title>
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
            display: flex;
            justify-content: space-between;
            width: 90%;
            max-width: 1000px;
            gap: 20px;
        }
        .section {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            width: 45%;
            text-align: center;
            height: 400px;
            overflow-y: auto;
        }
        h2 {
            color: #4CAF50;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        li {
            padding: 10px;
            margin: 5px 0;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        li:hover {
            background-color: #e1f5fe;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section" id="left-section">
            <h2>Antrian Obat (Diproses)</h2>
            <ul id="processing-list">
            @foreach($queues_belum_selesai as $qbs)
                <li>{{ $qbs->queue_number_farmasi }}</li>
            @endforeach
            </ul>
        </div>
        <div class="section" id="right-section">
            <h2>Obat Selesai Diproses</h2>
            <ul id="processed-list">
            @foreach($queues_selesai as $qs)
                <li>{{ $qs->queue_number_farmasi }}</li>
            @endforeach
            </ul>
        </div>
    </div>

    <script>
        // Fungsi untuk memindahkan antrian
        function moveItem(event, sourceListId, targetListId) {
            const item = event.target;
            const sourceList = document.getElementById(sourceListId);
            const targetList = document.getElementById(targetListId);

            // Hapus item dari list sumber dan tambahkan ke list target
            sourceList.removeChild(item);
            targetList.appendChild(item);
        }

        // Fungsi untuk mengirim API
        function sendAPI(url) {
            fetch(url, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Gagal memproses API: ' + (data.message || 'Error tidak diketahui'));
                    } else {
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghubungi API.');
                });
        }

        // Event listener untuk list processing (daftar kiri)
        document.getElementById("processing-list").addEventListener("click", function(event) {
            if (event.target.tagName === "LI") {
                const queueNumber = event.target.textContent;
                const url = `/antrian/close_farmasi/${queueNumber}`;
                sendAPI(url);
                moveItem(event, "processing-list", "processed-list");
            }
        });
        // Event listener untuk list processing (daftar kiri)
        document.getElementById("processed-list").addEventListener("click", function(event) {
            if (event.target.tagName === "LI") {
                const queueNumber = event.target.textContent;
                const url = `/antrian/unclose_farmasi/${queueNumber}`;
                sendAPI(url);
                moveItem(event, "processed-list", "processing-list");
            }
        });
    </script>
</body>
</html>
