<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicketSchedule extends Model
{
    use HasFactory;

    protected $table = 'picket_schedules';
    
    protected $fillable = [
        'user_id',
        'date',
        'day',
        'location',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}