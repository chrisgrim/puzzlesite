<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_completed_puzzle_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
