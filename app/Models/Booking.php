<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'day' => 'datetime'
    ];

    use HasFactory;

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
    
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
