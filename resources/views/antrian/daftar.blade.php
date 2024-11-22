<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nomor Antrian</title>
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
            width: 90%;
            max-width: 800px;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }
        #currentProcessing {
            font-size: 1.5rem;
            color: #FF5733;
            text-align: center;
            margin-bottom: 20px;
        }
        .queue-columns {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .queue-column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .queue-item {
            font-size: 25px;
            background-color: #e9f5ee;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 150px;
            height: 30px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
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
        <h2>Daftar Nomor Antrian</h2>

        <div id="currentProcessing">
            Sedang Diproses: <span id="processingQueueNumber">Belum ada yang diproses</span>
        </div>

        @if (!empty($queue_numbers))
            <div class="queue-columns">
                @foreach (array_chunk($queue_numbers, 10) as $column)
                    <div class="queue-column">
                        @foreach ($column as $index => $queue_number)
                            <div class="queue-item" data-queue="{{ $queue_number }}">
                                {{ $loop->parent->index * 10 + $index + 1 }}. {{ $queue_number }}
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <button id="speakButton">Panggil</button>
        @else
            <p style="font-size: 1.2rem; text-align: center;">Tidak ada nomor antrian yang tersimpan.</p>
        @endif
    </div>

    <script>
        document.getElementById("speakButton").addEventListener("click", function () {
            // Ambil elemen nomor antrian pertama
            const firstQueueItem = document.querySelector('.queue-item');
            if (firstQueueItem) {
                // Ambil nomor antrian asli dari atribut data-queue
                const queueNumber = firstQueueItem.getAttribute('data-queue');

                // Format teks untuk disebutkan
                const textToSpeak = `Nomor antrian ${queueNumber}, silakan menuju ke poli.`;

                // Perbarui label sedang diproses
                document.getElementById('processingQueueNumber').textContent = queueNumber;

                // Buat audio untuk suara bel
                const bellSound = new Audio('/assets/bel.mp3'); // Ganti dengan path file suara bel

                // Mainkan suara bel dulu
                bellSound.play().then(() => {
                    // Tambahkan jeda 2 detik sebelum Text-to-Speech
                    setTimeout(() => {
                        // Setelah jeda, lakukan Text-to-Speech
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

                        // Mainkan text-to-speech
                        speechSynthesis.speak(utterance);

                        // Hapus elemen pertama setelah panggilan
                        firstQueueItem.remove();
                    }, 2000); // Jeda 2 detik
                }).catch(err => {
                    console.error("Error memainkan suara bel:", err);
                });
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
