<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        // add others if needed, e.g. 'date_added', etc.
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function likes()
    {
        return $this->hasMany(Vote::class)->where('type', 'like');
    }

    public function hates()
    {
        return $this->hasMany(Vote::class)->where('type', 'hate');
    }

    public function getLikesCount()
    {
        return $this->votes()->where('type', 'like')->count();
    }

    public function getHatesCount()
    {
        return $this->votes()->where('type', 'hate')->count();
    }
}
