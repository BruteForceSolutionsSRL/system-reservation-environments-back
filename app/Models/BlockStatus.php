<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockStatus extends Model
{
    use HasFactory;
    public function classroomStatus()
    {
        return $this->hasMany(Block::class);
    }
}
