<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedPicketJournal extends Model
{
    
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}