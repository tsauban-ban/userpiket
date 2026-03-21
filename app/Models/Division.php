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

    
    
    public function picketJournals()
    {
        return $this->hasMany(PicketJournal::class);
    }
}
