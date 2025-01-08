<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisualIsi extends Model
{
    use HasFactory;
    protected $table = 'visual_isis';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function namatabelisi()
    {
        return $this->belongsTo(VisualTable::class);
    }

    public function headerisi()
    {
        return $this->belongsTo(VisualIsi::class);
    }
}
