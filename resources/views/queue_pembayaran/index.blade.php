<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian Pembayaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 800px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        .queue-columns {
            display: flex;
            gap: 20px;
            width: 100%;
            justify-content: space-between;
        }
        .queue-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .queue-item {
            font-size: 1.2rem;
            background-color: #e9f5ee;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .speak-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .speak-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Antrian Pembayaran</h2>
        <div class="queue-columns">
            <div class="queue-column">
                @foreach ($leftQueue as $index => $queue)
                    <div class="queue-item" data-queue="{{ $queue->queue_number_pembayaran }}">
                        {{ $index + 1 }}. {{ $queue->queue_number_pembayaran }}
                    </div>
                @endforeach
            </div>
        </div>
        <button class="speak-button" id="speakButton">Panggil</button>
    </div>

    <script>
    document.getElementById("speakButton").addEventListener("click", function () {
        // Ambil elemen nomor antrian pertama
        const firstQueueItem = document.querySelector('.queue-item');
        if (firstQueueItem) {
            // Ambil nomor antrian asli dari atribut data-queue
            const queueNumber = firstQueueItem.getAttribute('data-queue');

            // Format teks untuk disebutkan
            const textToSpeak = `Nomor antrian ${queueNumber} silahkan menuju ruang poli umum`;

            // Inisialisasi SpeechSynthesisUtterance
            const utterance = new SpeechSynthesisUtterance(textToSpeak);

            // Cari suara dengan logat Indonesia
            const voices = window.speechSynthesis.getVoices();
            const indonesianVoice = voices.find(voice => 
                voice.lang === "id-ID" && voice.name.includes("Google")
            );

            // Jika suara ditemukan, atur ke utterance
            if (indonesianVoice) {
                utterance.voice = indonesianVoice;
            } else {
                console.warn("Suara bahasa Indonesia tidak tersedia. Pastikan browser mendukung TTS Indonesia.");
            }

            // Set bahasa ke Indonesia sebagai fallback
            utterance.lang = "id-ID";

            // Mainkan suara
            speechSynthesis.speak(utterance);
        } else {
            console.warn("Tidak ada antrian yang ditemukan.");
        }
    });

    // Pastikan daftar suara dimuat sebelum event
    window.speechSynthesis.onvoiceschanged = function () {
        console.log("Daftar suara yang tersedia:", window.speechSynthesis.getVoices());
    };
    </script>
</body>
</html>
