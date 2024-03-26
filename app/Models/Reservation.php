<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_of_students', 
        'start_time', 
        'end_time', 
        'created_at',    
    ];
    
    protected $casts = [
        'date' => 'datetime', 
    ]; 

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'detail__classrooms', 'reservation_id', 'classroom_id');
    }
}
