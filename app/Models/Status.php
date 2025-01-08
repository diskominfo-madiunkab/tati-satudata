<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Data;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
