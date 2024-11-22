<?php
use App\Http\Controllers\AntrianController;

// Halaman utama untuk melihat daftar antrian dan tombol untuk menambah antrian baru
Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');

// Menampilkan halaman pemilihan jenis pasien (dijalankan setelah klik "Tambah Antrian Baru")
Route::post('/antrian/pilih-jenis-pasien', [AntrianController::class, 'pilihJenisPasien'])->name('antrian.jenis_pasien');

// Menampilkan halaman pemilihan metode pembayaran setelah jenis pasien dipilih
Route::post('/antrian/pilih-metode-pembayaran', [AntrianController::class, 'pilihMetodePembayaran'])->name('antrian.jenis_pembayaran');
Route::post('/antrian/store', [AntrianController::class, 'storepilihMetodePembayaran'])->name('antrian.store_jenis_pembayaran');
Route::get('/antrian/result/{queue_number}/{current_number}', [AntrianController::class, 'result'])->name('antrian.result');

// Menghapus semua nomor antrian dari cookie
Route::get('/antrian/reset', [AntrianController::class, 'reset'])->name('antrian.reset');
Route::get('/antrian/daftar', [AntrianController::class, 'daftarAntrian'])->name('antrian.daftar');
Route::get('/antrian/antri_dokter/{spesialis}', [AntrianController::class, 'antri_dokter'])->name('antrian.antri_dokter');
Route::get('/antrian/queue_farmasi', [AntrianController::class, 'antri_farmasi'])->name('antrian.antri_farmasi');
Route::get('/antrian/close_farmasi/{queue_number}', [AntrianController::class, 'close_farmasi'])->name('antrian.close_farmasi');
Route::get('/antrian/unclose_farmasi/{queue_number}', [AntrianController::class, 'unclose_farmasi'])->name('antrian.unclose_farmasi');
Route::get('/antrian/delete_pendaftaran/{queue_number_delete}', [AntrianController::class, 'delete_pendaftaran']);

use App\Http\Controllers\QueueDokterController;
Route::get('/antrian/delete_dokter/{queue_number_delete}', [QueueDokterController::class, 'delete_queue']);
Route::get('/antrian/update_dokter/{queue_number}', [QueueDokterController::class, 'update_queue']);
Route::get('/antrian/queue_dokter/{spesialis}', [QueueDokterController::class, 'index']);
Route::get('/antrian/close_dokter/{queue_number}', [QueueDokterController::class, 'close_dokter']);
Route::post('/antrian/queue_dokter/{spesialis}', [QueueDokterController::class, 'store']);
use App\Http\Controllers\QueuePembayaranController;
Route::get('/antrian/antri_pembayaran', [AntrianController::class, 'antri_pembayaran'])->name('antrian.antri_pembayaran');
Route::get('/antrian/delete_pembayaran/{queue_number_delete}', [QueuePembayaranController::class, 'delete_queue']);
Route::get('/antrian/queue_pembayaran', [QueuePembayaranController::class, 'index']);
Route::post('/antrian/queue_pembayaran', [QueuePembayaranController::class, 'store']);
