<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Divisions extends Model
{
     protected $fillable = [
        'division_name',
    ];
      public function users()
    {
        return $this->hasMany(User::class);
    }
}
