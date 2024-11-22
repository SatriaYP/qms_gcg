<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini
    protected $table = 'queue';
    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'queue_number',
        'date',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];

    // Tentukan apakah tabel ini memiliki kolom timestamps (created_at, updated_at)
    public $timestamps = false;
}
