<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom_Type extends Model
{
    use HasFactory;
    protected $fillable = [
        'description'
    ]; 
}
