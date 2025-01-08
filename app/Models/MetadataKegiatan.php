<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetadataKegiatan extends Model
{
    use HasFactory;

    protected $table = 'metadata_kegiatan';
    protected $guarded = ['id'];
    protected $casts = [
        'variabel_dikumpulkan' => 'array',
        'rencana_publikasi' => 'array',
    ];

    public function data(): BelongsTo
    {
        return $this->belongsTo(Data::class);
    }
}
