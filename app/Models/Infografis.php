<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infografis extends Model
{
    use HasFactory;
    protected $table = 'infografis';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(InfografisImage::class, 'infografis_id')->orderBy('urutan', 'asc');
    }
}
