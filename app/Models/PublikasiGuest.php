<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublikasiGuest extends Model
{
    use HasFactory;

    protected $table = 'publikasi_guests';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
