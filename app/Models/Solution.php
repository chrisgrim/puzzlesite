<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    // Define the fields that are mass assignable
    protected $fillable = [
        'user_id',
        'puzzle_id',
        'guesses',
        'solved',
    ];

    // Define that 'guesses' is a JSON field
    protected $casts = [
        'guesses' => 'array',
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
