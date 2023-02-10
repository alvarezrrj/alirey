<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'day'
    ];

    protected $casts = [
        'day' => 'datetime',
    ];

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
