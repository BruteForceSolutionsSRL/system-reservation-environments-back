<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPerson extends Model
{
    use HasFactory;
    protected $table = 'person_notification';
    
    public function receptor()
    {
        return $this->belongsToMany(Person::class); 
    }

    public function notification()
    {
        return $this->belongsToMany(Notification::class);
    }
}
