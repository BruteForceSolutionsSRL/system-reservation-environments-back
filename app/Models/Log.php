<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
