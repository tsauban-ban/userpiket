<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $guarded = [];
    
      public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }

    // Relasi ke Schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
