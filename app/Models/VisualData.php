<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisualData extends Model
{
    use HasFactory;
    protected $table = 'visual_data';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function data()
    {
        return $this->belongsTo(Data::class);
    }
}
