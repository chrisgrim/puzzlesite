<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    // Define the fields that are mass assignable
    protected $fillable = [
        'title',
        'description',
        'solution',
        'difficulty',
        'chapter_id',
        'order'
    ];

    public function getRouteKeyName()
    {
        return 'order'; 
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
