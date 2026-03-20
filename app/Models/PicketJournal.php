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

    // Accessor untuk URL foto sebelum
    public function getBeforePhotoUrlAttribute()
    {
        return $this->before_photo 
            ? Storage::url($this->before_photo) 
            : null;
    }

    // Accessor untuk URL foto sesudah
    public function getAfterPhotoUrlAttribute()
    {
        return $this->after_photo 
            ? Storage::url($this->after_photo) 
            : null;
    }

    // Cek apakah ada foto sebelum
    public function hasBeforePhoto()
    {
        return !is_null($this->before_photo);
    }

    // Cek apakah ada foto sesudah
    public function hasAfterPhoto()
    {
        return !is_null($this->after_photo);
    }

    // Accessor untuk format tanggal Indonesia
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    // Accessor untuk hari dalam bahasa Indonesia
    public function getDayNameAttribute()
    {
        return Carbon::parse($this->date)->locale('id')->isoFormat('dddd');
    }

    // Scope untuk filter berdasarkan hari
    public function scopeFilterByDay($query, $day)
    {
        if ($day) {
            return $query->whereRaw('DAYNAME(date) = ?', [$day]);
        }
        return $query;
    }

    // Scope untuk pencarian
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