<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandarData extends Model
{
    use HasFactory;

    protected $table = 'standar_data';
    protected $guarded = ['id'];

    public function data(): BelongsTo
    {
        return $this->belongsTo(Data::class);
    }
}
