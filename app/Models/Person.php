<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class);
    }
}
