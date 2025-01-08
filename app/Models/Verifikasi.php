<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';
    protected $guarded = ['id'];
    protected $casts = ['accepted' => 'boolean'];

    public function data()
    {
        return $this->belongsTo(Data::class, 'data_id');
    }

    public function scopeCategory($query, $category)
    {
        return $query->where(compact('category'));
    }
}
