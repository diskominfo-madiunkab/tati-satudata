<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class ActivityLog extends Model
{
    use LogsActivity;
    use HasFactory;
    protected $table = 'activitylog';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
