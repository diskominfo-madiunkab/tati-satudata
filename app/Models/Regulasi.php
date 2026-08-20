<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulasi extends Model
{
    use HasFactory;

    protected $table = 'regulasis';

    protected $fillable = [
        'judul',
        'nomor',
        'tahun',
        'tentang',
        'file_dokumen',
        'kategori',
        'status',
    ];
}
