<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'day' => 'datetime',
        'pref_expiry' => 'datetime'
    ];

    use HasFactory;

    public function serializeDate(DateTimeInterface $date)
    {
        //return $date->format('d-m-Y');
        return $date->format('Y-m-d');
    }

    public function slot()
    {
        // A slot may have been soft deleted, hence withTrashed()
        return $this->belongsTo(Slot::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function therapist()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
