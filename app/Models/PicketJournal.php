<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicketJournal extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'activity',
        'description',
        'status',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}