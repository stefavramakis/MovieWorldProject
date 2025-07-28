<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('user');

        $userId = $request->get('user');

        // Prioritize movies from the given user (e.g., current user)
        if ($userId) {
            $query->orderByRaw("CASE WHEN user_id = ? THEN 0 ELSE 1 END", [$userId]);
        }

        // Handle sorting
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

    public function create()
    {
        return view('movies.create');
    }

    // Handle the form submission
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('movies.index')->with('success', 'Movie added successfully!');
    }

    public function react(Request $request, Movie $movie, $type)
    {
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
