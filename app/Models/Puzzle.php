<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'solution',
        'difficulty',
        'order',
        'chapter_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
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