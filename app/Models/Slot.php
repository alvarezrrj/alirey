<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

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
