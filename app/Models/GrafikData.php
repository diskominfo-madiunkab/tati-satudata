<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrafikData extends Model
{
    use HasFactory;
    protected $table = 'grafik_data';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function header()
    {
        return $this->belongsTo(VisualHeader::class);
    }

    public function kategoriHeader()
    {
        return $this->belongsTo(VisualHeader::class, 'kategori', 'id');
    }

    public function axisXHeader()
    {
        return $this->belongsTo(VisualHeader::class, 'axis_x', 'id');
    }

    public function axisYHeader()
    {
        return $this->belongsTo(VisualHeader::class, 'axis_y', 'id');
    }
}
