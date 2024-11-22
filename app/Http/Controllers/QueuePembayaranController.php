<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\QueuePembayaran;
class QueuePembayaranController extends Controller
{
    public function index()
    {
        // Ambil seluruh data dari tabel queue_pembayaran
        $queues = DB::table('queue_pembayaran')->orderBy('id_queue_pembayaran', 'asc')->get();
        
        // Bagi data menjadi tiga bagian: kiri, tengah, kanan
        $leftQueue = $queues->slice(0, 10);      // 10 data pertama
        $centerQueue = $queues->slice(10, 10);   // 10 data berikutnya
        $rightQueue = $queues->slice(20, 10);    // 10 data setelahnya

        // Kirim data ke view
        return view('queue_pembayaran.index', compact('leftQueue', 'centerQueue', 'rightQueue'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'queue_number_pembayaran' => 'required|string|max:6',
        ]);

        $queueNumber = $request->queue_number_pembayaran;

        // Tambahkan data ke tabel
        DB::table('queue_pembayaran')->insert([
            'queue_number_pembayaran' => $queueNumber,
            'date' => Carbon::now()->addHours(7), // Waktu +7 jam
        ]);
        return response()->json(['success' => true, 'message' => 'Nomor antrian berhasil ditambahkan.']);
    }

    public function delete_queue($queue_number_delete)
    {
        $queue = QueuePembayaran::where('queue_number_pembayaran', $queue_number_delete)->first();

        if ($queue) {
            $queue->delete();
            DB::table('queue_farmasi')->insert([
                'queue_number_farmasi' => $queue_number_delete,
                'status_antrian' => 'Proses di Farmasi',
                'date' => Carbon::now()->addHours(7), // Waktu +7 jam
            ]);
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
}
