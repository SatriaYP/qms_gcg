<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\QueueDokter;
class QueueDokterController extends Controller
{
    public function index($spesialis)
    {
        // Ambil seluruh data dari tabel queue_dokter
        $queues = DB::table('queue_dokter')->where('spesialis','=',$spesialis)->where('status_antrian', '!=', 'Sedang di Dalam')->where('status_antrian', '!=', 'Selesai di dokter')->orderBy('id_queue_dokter', 'asc')->get();
        $queue_proses = DB::table('queue_dokter')->where('spesialis','=',$spesialis)->where('status_antrian', '=', 'Sedang di Dalam')->get();
        
        // Bagi data menjadi tiga bagian: kiri, tengah, kanan
        $leftQueue = $queues->slice(0, 10);      // 10 data pertama
        $centerQueue = $queues->slice(10, 10);   // 10 data berikutnya
        $rightQueue = $queues->slice(20, 10);    // 10 data setelahnya

        // Kirim data ke view
        return view('queue_dokter.index', compact('leftQueue', 'centerQueue', 'rightQueue', 'spesialis', 'queue_proses'));
    }
    public function store(Request $request, $spesialis)
    {
        $request->validate([
            'queue_number_dokter' => 'required|string|max:6',
        ]);
        
        $queueNumber = $request->queue_number_dokter;

        // Tambahkan data ke tabel
        DB::table('queue_dokter')->insert([
            'queue_number_dokter' => $queueNumber,
            'spesialis' => $spesialis,
            'status_antrian' => "Menunggu dipanggil",
            'date' => Carbon::now()->addHours(7), // Waktu +7 jam
        ]);

        return response()->json(['success' => true, 'message' => 'Nomor antrian berhasil ditambahkan.']);
    }

    public function delete_queue($queue_number_delete)
    {
        $queue = QueueDokter::where('queue_number_dokter', $queue_number_delete)->first();

        if ($queue) {
            $queue->delete();
            return response()->json([
                'success' => true,
                'message' => "Nomor antrian $queue_number_delete berhasil dihapus",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Nomor antrian $queue_number_delete tidak ditemukan",
            ], 404);
        }
    }
    public function update_queue($queue_number)
    {
        $queue = QueueDokter::where('queue_number_dokter','=' ,$queue_number)->first();

        if ($queue) {
            $queue->update([
                'status_antrian' => "Sedang di Dalam",
            ]);
            return response()->json([
                'success' => true,
                'message' => "Nomor antrian $queue_number berhasil di update status dipanggil",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Nomor antrian $queue_number tidak ditemukan",
            ], 404);
        }
    }
    public function close_dokter($queue_number)
    {
        $queue = QueueDokter::where('queue_number_dokter','=' ,$queue_number)->first();

        if ($queue) {
            $queue->update([
                'status_antrian' => "Selesai di Dokter",
            ]);
            return response()->json([
                'success' => true,
                'message' => "Nomor antrian $queue_number berhasil di update status selesai di dokter",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Nomor antrian $queue_number tidak ditemukan",
            ], 404);
        }
    }
}
