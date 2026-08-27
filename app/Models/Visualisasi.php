<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visualisasi extends Model
{
    use HasFactory;

    protected $table = 'visualisasis';

    protected $fillable = [
        'title',
        'tableau_url',
        'content',
    ];
}
