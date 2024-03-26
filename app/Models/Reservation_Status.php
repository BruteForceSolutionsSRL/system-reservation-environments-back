<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reservation_Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'description', 
    ]; 
    public function findByStatus(Builder $builder, string $text): Reservation_Status 
    {
        return null; // use the where function to retrieve the exact data. 
    }
}
