<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory;
    // Slots are noly soft deleted to avoid bookings being orphaned.
    use SoftDeletes;

    protected $fillable = [
        'start',
        'end'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('H:i');
    }


    public function config()
    {
        return $this->belongsTo(Config::class);
    }
}
