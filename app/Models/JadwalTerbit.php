<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTerbit extends Model
{
    use HasFactory;

    protected $table = 'jadwal_terbits';

    protected $fillable = [
        'judul_buku',
        'penyusun',
        'tahun',
        'rencana_terbit',
        'frekuensi_terbit',
        'status_terbit',
        'file_pdf',
        'cover',
        'deskripsi',
    ];
}
