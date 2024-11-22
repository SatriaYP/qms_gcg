<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueDokter extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini
    protected $table = 'queue_dokter';
    public $primaryKey = 'id_queue_dokter';
    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'queue_number_dokter',
        'date',
        'status_antrian',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];

    // Tentukan apakah tabel ini memiliki kolom timestamps (created_at, updated_at)
    public $timestamps = false;
}
