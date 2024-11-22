<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AntrianController extends Controller
{
    public function index()
    {
        $queue_numbers = Queue::pluck('queue_number')->toArray();
        return view('antrian.pilih_jenis_pasien', compact('queue_numbers'));
    }

    public function pilihJenisPasien(Request $request)
    {
        $jenis_pasien = $request->jenis_pasien;
        $request->session()->put('jenis_pasien', $jenis_pasien);
        return view('antrian.pilih_metode_pembayaran', compact('jenis_pasien'));
    }

    public function pilihMetodePembayaran(Request $request)
    {
        $jenis_pasien = $request->jenis_pasien;
        $request->session()->put('jenis_pasien', $jenis_pasien);
        return view('antrian.pilih_metode_pembayaran', compact('jenis_pasien'));
    }

    public function storepilihMetodePembayaran(Request $request)
{
    $jenis_pasien = $request->session()->get('jenis_pasien');
    $metode_pembayaran = $request->input('metode_pembayaran');

    // Waktu sekarang + 7 jam
    $current_time_plus_7 = Carbon::now()->addHours(7)->toDateString();

    // Ambil data pertama dari tabel queue
    $first_queue = Queue::orderBy('id', 'asc')->first();

    // Cek apakah data pertama ada dan apakah tanggalnya sama dengan current_time_plus_7
    if ($first_queue && Carbon::parse($first_queue->date)->toDateString() !== $current_time_plus_7) {
        // Jika tanggalnya tidak sama, hapus semua data pada tabel queue
        Queue::truncate();
        $current_number = 1;
    } else {
        // Ambil nomor antrian terakhir dari database atau mulai dari 1 jika tidak ada
        $last_queue = Queue::orderBy('id', 'desc')->first();

        if ($last_queue) {
            $last_number = (int)substr($last_queue->queue_number, -3);
            $current_number = $last_number + 1;
        } else {
            $current_number = 1;
        }
    }

    do {
        // Format nomor antrian: A1001
        $queue_number = $jenis_pasien . $metode_pembayaran . str_pad($current_number, 3, '0', STR_PAD_LEFT);

        // Cek apakah nomor antrian sudah ada di tabel queue atau queue_pembayaran
        $exists_in_queue = Queue::where('queue_number', $queue_number)->exists();
        $exists_in_queue_pembayaran = DB::table('queue_pembayaran')->where('queue_number_pembayaran', $queue_number)->exists();

        if ($exists_in_queue || $exists_in_queue_pembayaran) {
            $current_number++;
        } else {
            break;
        }
    } while (true);

    // Simpan nomor antrian ke database dengan waktu +7 jam
    Queue::create([
        'queue_number' => $queue_number,
        'date' => Carbon::now()->addHours(7),
    ]);

    return redirect()->route('antrian.result', ['queue_number' => $queue_number, 'current_number' => $current_number]);
}


    public function reset()
    {
        // Hapus semua nomor antrian dari database
        Queue::truncate();
        return redirect('/antrian');
    }
    public function antri_dokter($spesialis)
    {
        $spesialis_temp = $spesialis;
        // Hapus semua nomor antrian dari database
        return view('antrian.antri_dokter', compact('spesialis_temp'));
    }
    public function antri_farmasi()
    {
        $queues_selesai = DB::table('queue_farmasi')->where('status_antrian', '=', 'Selesai di Farmasi')->orderBy('id_queue_farmasi', 'asc')->get();
        $queues_belum_selesai = DB::table('queue_farmasi')->where('status_antrian', '=', 'Proses di Farmasi')->orderBy('id_queue_farmasi', 'asc')->get();
        // Hapus semua nomor antrian dari database
        return view('antrian.antri_farmasi', compact('queues_selesai', 'queues_belum_selesai'));
    }
    public function antri_pembayaran()
    {
        // Hapus semua nomor antrian dari database
        return view('antrian.antri_pembayaran');
    }

    public function result($queue_number)
    {
        $qr_code = QrCode::generate($queue_number);
        return view('antrian.result', compact('queue_number', 'qr_code'));
    }

    public function daftarAntrian()
    {
        // Waktu sekarang + 7 jam
        $current_time_plus_7 = Carbon::now()->addHours(7)->toDateString();

        // Ambil data pertama dari tabel queue
        $first_queue = Queue::orderBy('id', 'asc')->first();

        // Cek apakah data pertama ada dan apakah tanggalnya sama dengan current_time_plus_7
        if ($first_queue && Carbon::parse($first_queue->date)->toDateString() !== $current_time_plus_7) {
            // Jika tanggalnya tidak sama, hapus semua data pada tabel queue
            Queue::truncate();
            $current_number = 1;
        } else {
            // Ambil nomor antrian terakhir dari database atau mulai dari 1 jika tidak ada
            $last_queue = Queue::orderBy('id', 'desc')->first();

            if ($last_queue) {
                $last_number = (int)substr($last_queue->queue_number, -3);
                $current_number = $last_number + 1;
            } else {
                $current_number = 1;
            }
        }
        $queue_numbers = Queue::pluck('queue_number')->toArray();
        return view('antrian.daftar', compact('queue_numbers'));
    }

    public function delete_pendaftaran($queue_number_delete)
    {
        $queue = Queue::where('queue_number', $queue_number_delete)->first();

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
    public function close_farmasi($queue_number_farmasi)
{
    $updated = DB::table('queue_farmasi')
        ->where('queue_number_farmasi', $queue_number_farmasi)
        ->update(['status_antrian' => "Selesai di Farmasi"]);

    if ($updated) {
        return response()->json([
            'success' => true,
            'message' => "Nomor antrian $queue_number_farmasi berhasil di update status selesai di farmasi",
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => "Nomor antrian $queue_number_farmasi tidak ditemukan",
        ], 404);
    }
}

public function unclose_farmasi($queue_number_farmasi)
{
    $updated = DB::table('queue_farmasi')
        ->where('queue_number_farmasi', $queue_number_farmasi)
        ->update(['status_antrian' => "Proses di Farmasi"]);

    if ($updated) {
        return response()->json([
            'success' => true,
            'message' => "Nomor antrian $queue_number_farmasi berhasil di update status selesai di farmasi",
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => "Nomor antrian $queue_number_farmasi tidak ditemukan",
        ], 404);
    }
}
}
