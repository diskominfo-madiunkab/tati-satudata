<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetadataIndikator extends Model
{
    use HasFactory;

    protected $table = 'metadata_indikator';
    protected $guarded = ['id'];

    public function data(): BelongsTo
    {
        return $this->belongsTo(Data::class);
    }
}
