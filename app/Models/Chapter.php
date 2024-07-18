<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['title', 'order'];
    
    public function puzzles()
    {
        return $this->hasMany(Puzzle::class)->orderBy('order');
    }
}
