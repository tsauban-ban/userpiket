<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PicketJournal extends Model
{
    protected $guarded = [];
     protected $casts = [
        'date' => 'date'
    ];

 public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    
    public function getBeforePhotoUrlAttribute()
    {
        return $this->before_photo 
            ? Storage::url($this->before_photo) 
            : null;
    }

    
    
    public function getAfterPhotoUrlAttribute()
    {
        return $this->after_photo 
            ? Storage::url($this->after_photo) 
            : null;
    }

    
    
    public function hasBeforePhoto()
    {
        return !is_null($this->before_photo);
    }

    
    
    public function hasAfterPhoto()
    {
        return !is_null($this->after_photo);
    }

    
    
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    
    
    public function getDayNameAttribute()
    {
        return Carbon::parse($this->date)->locale('id')->isoFormat('dddd');
    }

    
    
    public function scopeFilterByDay($query, $day)
    {
        if ($day) {
            return $query->whereRaw('DAYNAME(date) = ?', [$day]);
        }
        return $query;
    }

    
    
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}