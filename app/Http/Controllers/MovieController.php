<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for handling movie-related actions.
 */
class MovieController extends Controller
{
    /**
     * Display a list of movies.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\View\View The view displaying the list of movies.
     */
    public function index(Request $request)
    {
        $query = Movie::with('user');

        $userId = $request->get('user');

        // Prioritize movies from the given user (e.g., current user)
        if ($userId) {
            $query->orderByRaw("CASE WHEN user_id = ? THEN 0 ELSE 1 END", [$userId]);
        }

        // Handle sorting based on the 'sort' query parameter
        switch ($request->get('sort')) {
            case 'likes':
                $query->withCount('likes')->orderByDesc('likes_count');
                break;
            case 'hates':
                $query->withCount('hates')->orderByDesc('hates_count');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $movies = $query->get();

        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new movie.
     *
     * @return \Illuminate\View\View The view displaying the movie creation form.
     */
    public function create()
    {
        return view('movies.create');
    }

    /**
     * Store a newly created movie in the database.
     *
     * @param Request $request The HTTP request object containing form data.
     * @return \Illuminate\Http\RedirectResponse Redirects to the movie list with a success message.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create a new movie record
        Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('movies.index')->with('success', 'Movie added successfully!');
    }

    /**
     * Handle user reactions (like or hate) to a movie.
     *
     * @param Request $request The HTTP request object.
     * @param Movie $movie The movie being reacted to.
     * @param string $type The type of reaction ('like' or 'hate').
     * @return \Illuminate\Http\RedirectResponse Redirects back to the previous page.
     */
    public function react(Request $request, Movie $movie, $type)
    {
        // Validate the reaction type
        if (!in_array($type, ['like', 'hate'])) {
            abort(400, 'Invalid reaction type.');
        }

        $userId = auth()->id();

        // Prevent users from voting on their own movies
        if ($movie->user_id === $userId) {
            return back()->with('error', 'You cannot vote on your own movie.');
        }

        $existingVote = $movie->votes()->where('user_id', $userId)->first();

        if ($existingVote) {
            if ($existingVote->type === $type) {
                // Same reaction again: remove the vote
                $existingVote->delete();
            } else {
                // Change the vote
                $existingVote->update(['type' => $type]);
            }
        } else {
            // No vote yet — create it
            $movie->votes()->create([
                'user_id' => $userId,
                'type' => $type,
            ]);
        }

        return back();
    }
}
