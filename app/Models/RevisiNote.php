<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiNote extends Model
{
    use HasFactory;

    protected $table = 'revisi_notes';

    protected $fillable = [
        'data_id',
        'user_id',
        'tahapan',
        'catatan',
        'status_sebelumnya',
        'status_sesudahnya',
    ];

    public function data()
    {
        return $this->belongsTo(Data::class, 'data_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
