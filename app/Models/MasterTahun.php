<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTahun extends Model
{
    use HasFactory;
    protected $table = 'master_tahuns';
    protected $guarded = [];
}
