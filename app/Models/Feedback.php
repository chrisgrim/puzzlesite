<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Define the fields that are mass assignable
    protected $fillable = [
        'user_id',
        'puzzle_id',
        'comment',
        'rating',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Puzzle
    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class);
    }
}
