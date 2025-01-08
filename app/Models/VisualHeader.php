<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisualHeader extends Model
{
    use HasFactory;
    protected $table = 'visual_headers';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function namatabel()
    {
        return $this->belongsTo(VisualTable::class);
    }

    public function isi()
    {
        return $this->hasMany(VisualIsi::class);
    }

    public function grafik()
    {
        return $this->hasMany(GrafikData::class);
    }

    public function grafikDataKategori()
    {
        return $this->hasMany(GrafikData::class, 'kategori', 'id');
    }

    public function grafikDataAxisX()
    {
        return $this->hasMany(GrafikData::class, 'axis_x', 'id');
    }

    public function grafikDataAxisY()
    {
        return $this->hasMany(GrafikData::class, 'axis_y', 'id');
    }
}
