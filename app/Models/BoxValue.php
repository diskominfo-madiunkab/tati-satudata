<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxValue extends Model
{
    use HasFactory;
    protected $table = 'box_values';
    protected $guarded = [];

    public function data()
    {
        return $this->belongsTo(Data::class, 'data_id');
    }
}
