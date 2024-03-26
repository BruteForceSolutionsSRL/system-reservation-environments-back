<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'capacity', 
        'floor', 
        'block_id', 
        'classroom_type_id',
    ]; 

    public function reservations() 
    {
        return $this->belongstoMany(Reservation::class, 'detail__classrooms', 'classroom_id', 'reservation_id');
    }
}


