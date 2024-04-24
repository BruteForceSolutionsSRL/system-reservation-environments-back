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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
