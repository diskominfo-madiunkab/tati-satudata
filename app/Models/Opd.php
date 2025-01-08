<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Data;
use Illuminate\Support\Facades\Auth;

class Opd extends Model
{
    use HasFactory;

    protected $table = 'opds';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }

    public function data_opd()
    {
        return $users = Opd::Select('*')
            ->get();
    }

    public static function get_opd()
    {
        return Opd::where('id', '=', Auth::user()->opd_id)->get();
    }

    public function get_namaopd()
    {
        return Opd::where('id', '=', Auth::user()->opd_id)->select('nama_opd')->get();
    }
}
