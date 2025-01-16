<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisualTable extends Model
{
    use HasFactory;
    protected $table = 'visual_tables';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function header()
    {
        return $this->hasMany(VisualHeader::class, 'id_namatabel');
    }
}
