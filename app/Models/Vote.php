<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The Vote model represents a user's reaction (like or hate) to a movie.
 * It defines the relationships between votes, users, and movies.
 */
class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', // The ID of the user who cast the vote.
        'movie_id', // The ID of the movie being voted on.
        'type', // The type of vote ('like' or 'hate').
    ];

    /**
     * Create a new vote record.
     *
     * @param array $array The attributes for the new vote.
     * @return void
     */
    public static function create(array $array) {}

    /**
     * Get the user who cast the vote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movie that the vote is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
