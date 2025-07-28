```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The Movie model represents a movie entity in the application.
 * It defines the relationships between movies, users, and votes.
 */
class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', // The title of the movie.
        'description', // A brief description of the movie.
        'user_id', // The ID of the user who created the movie.
    ];

    /**
     * Get the user who created the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all votes associated with the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get all "like" votes for the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Vote::class)->where('type', 'like');
    }

    /**
     * Get all "hate" votes for the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hates()
    {
        return $this->hasMany(Vote::class)->where('type', 'hate');
    }

    /**
     * Get the count of "like" votes for the movie.
     *
     * @return int
     */
    public function getLikesCount()
    {
        return $this->votes()->where('type', 'like')->count();
    }

    /**
     * Get the count of "hate" votes for the movie.
     *
     * @return int
     */
    public function getHatesCount()
    {
        return $this->votes()->where('type', 'hate')->count();
    }
}
```
